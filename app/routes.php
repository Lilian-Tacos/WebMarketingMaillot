<?php
use Symfony\Component\HttpFoundation\Request;
use NewSoccerJersey\Domain\Comment;
use NewSoccerJersey\Form\Type\CommentType;
use NewSoccerJersey\Domain\Jersey;
use NewSoccerJersey\Domain\User;
use NewSoccerJersey\Domain\Basket;

use NewSoccerJersey\Form\Type\JerseyType;
use NewSoccerJersey\Form\Type\UserType;
use NewSoccerJersey\Form\Type\SignupType;

use NewSoccerJersey\Form\Task\PasswordTask;
use NewSoccerJersey\Form\Task\UserInfoTask;

use NewSoccerJersey\Form\Type\PasswordType;
use NewSoccerJersey\Form\Type\UserInfoType;


//////////////////////////////////
/////        Generals        /////
//////////////////////////////////


// Home page
$app->get('/', function () use ($app) {
    $leagues = $app['dao.league']->findAll();
    return $app['twig']->render('index.html.twig', array('leagues' => $leagues));
  })->bind('home');

  
// League's jerseys page
$app->get('/league/{id}', function ($id) use ($app) {
    $leagues = $app['dao.league']->findAll();
    $jerseys = $app['dao.jersey']->findAllFromLeague($id);
    $leagueL = $app['dao.league']->findFromId($id);
    return $app['twig']->render('league.html.twig', array('leagues' => $leagues, 'leagueL' => $leagueL, 'jerseys' => $jerseys));
})->bind('league');

$app->get('/maillots', function () use ($app) {
    $leagues = $app['dao.league']->findAll();
    $jerseys = $app['dao.jersey']->findAll();
    return $app['twig']->render('jerseys.html.twig', array('leagues' => $leagues, 'jerseys' => $jerseys));
  })->bind('maillots');


// Jerseys details with comments
$app->match('/jersey/{id}', function ($id, Request $request) use ($app) {
    $leagues = $app['dao.league']->findAll();
    $jersey = $app['dao.jersey']->findFromId($id);
    $commentFormView = null;
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated : he can add comments
        $comment = new Comment();
        $comment->setJersey($jersey);
        $user = $app['user'];
        $comment->setAuthor($user);
        $commentForm = $app['form.factory']->create(new CommentType(), $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Votre commentaire a été ajouté avec succès.');
			$comment = new Comment();
			$comment->setJersey($jersey);
			$comment->setAuthor($user);
			$commentForm = $app['form.factory']->create(new CommentType(), $comment);
        }
        $commentFormView = $commentForm->createView();


		if ($request->getMethod() == 'POST' && !$request->request->count() == 1) {
			$baskets = $app['dao.basket']->findOne($user->getId(), $id);
			if (isset($baskets[0])){
				$app['dao.basket']->upQuantity($baskets[0]);
			}
			else {
				$basket = new Basket();
				$basket->setQuantity(1);
				$basket->setJersey($jersey);
				$basket->setUser($user);
				$app['dao.basket']->save($basket);
			}
			$app['session']->getFlashBag()->add('success', 'L\'article a été ajouté au panier !');
		}
    }
    $comments = $app['dao.comment']->findAllFromJersey($id);
    return $app['twig']->render('jersey.html.twig', array(
        'jersey' => $jersey, 
        'comments' => $comments,
        'commentForm' => $commentFormView,
        'leagues' => $leagues));
})->bind('jersey');






//////////////////////////////////
/////          User          /////
//////////////////////////////////


// Logins
$app->get('/login', function(Request $request) use ($app) {
    
    if ($app['security']->isGranted('ROLE_USER'))
        return $app->redirect('/');

    $leagues = $app['dao.league']->findAll();
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'leagues' => $leagues
    ));
})->bind('login');



// signup
$app->match('/signup', function(Request $request) use ($app) {

    if ($app['security']->isGranted('ROLE_USER'))
        return $app->redirect('/');

    $leagues = $app['dao.league']->findAll();

    $user = new User();
    $signupForm = $app['form.factory']->create(new SignupType(), $user);

    $signupForm->handleRequest($request);
    if ($signupForm->isSubmitted() && $signupForm->isValid()) {
		if ($app['dao.user']->existeDeja($user->getMail())){
			$app['session']->getFlashBag()->add('error', 'L\'adresse mail est déjà utilisée...');
		}
		else{
			// Set user's role at simple user
			$user->setRole('ROLE_USER');

			// generate a random salt value
			$salt = substr(md5(time()), 0, 23);
			$user->setSalt($salt);
			$plainPassword = $user->getPassword();
			// find the default encoder
			$encoder = $app['security.encoder.digest'];
			// compute the encoded password
			$password = $encoder->encodePassword($plainPassword, $user->getSalt());
			$user->setPassword($password); 
			$app['dao.user']->save($user);
			return $app-> redirect('login');
		}
    }
    return $app['twig']->render('signup.html.twig', array(
        'leagues' => $leagues,
        'title' => 'New user',
        'signupForm' => $signupForm->createView()));
})->bind('signup');


// Account page
$app->match('/user/account', function(Request $request) use ($app) {

    // redirect to home page if not authenticated
    if (!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        return $app->redirect("/");

    $leagues = $app['dao.league']->findAll();


    $user = $app['user'];


    $userInfoForm = $app['form.factory']->create(new UserInfoType(), $user);
    $userInfoForm->handleRequest($request);


    if ($userInfoForm->isSubmitted() && $userInfoForm->isValid()) {
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Vos informations ont été sauvegardées');
    }
    return $app['twig']->render('user/account.html.twig', array(
        'title' => 'Edit user',
        'userInfoForm' => $userInfoForm->createView(),
        'leagues' => $leagues));                       
})->bind('account');


// Password page
$app->match('/user/password', function(Request $request) use ($app) {

    // redirect to home page if not authenticated
    if (!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        return $app->redirect("/");

    $leagues = $app['dao.league']->findAll();


    $user = $app['user'];

    $passwordTask = new PasswordTask();
    $passwordForm = $app['form.factory']->create(new PasswordType(), $passwordTask);

    $passwordForm->handleRequest($request);

    if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

        $plainPassword = $passwordTask->getNewPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());

        // if old user password == old given password
        if ($user->getPassword() == $encoder->encodePassword($passwordTask->getOldPassword(), $user->getSalt())){
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'Le mot de passe a été modifié avec succès');
        }else{
            $app['session']->getFlashBag()->add('error', 'L\'ancien mot de passe n\'est pas le bon.');

        }

    }
    return $app['twig']->render('user/password.html.twig', array(
        'title' => 'Edit user',
        'passwordForm' => $passwordForm->createView(),
        'leagues' => $leagues));                       
})->bind('password');


// Basket 
$app->get('/user/panier', function() use ($app) {

    // redirect to home page if not authenticated
    if (!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        return $app->redirect("/");

    $leagues = $app['dao.league']->findAll();
    $baskets = $app['dao.basket']->findAllFromUser($app['user']->getId());
	$price = 0;
	$total = 0;
	for ($i=0; $i<count($baskets);$i++){
		$price += $baskets[$i]->getJersey()->getPrice() * $baskets[$i]->getQuantity();
		$total += $baskets[$i]->getQuantity();
	}
    return $app['twig']->render('user/basket.html.twig', array('leagues' => $leagues, 'baskets' => $baskets, 'price' => $price, 'total' => $total ));
})->bind('basket');


// Edition basket
$app->match('/user/panier/edit', function(Request $request) use ($app) {

    // redirect to home page if not authenticated
    if (!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
        return $app->redirect("/");

    $leagues = $app['dao.league']->findAll();
    $baskets = $app['dao.basket']->findAllFromUser($app['user']->getId());
	if ($request->getMethod() == 'POST') {
		for ($i=0; $i<count($baskets);$i++){
			$id=$baskets[$i]->getJersey()->getId();
			if(isset($_POST["suppr$id"])){
				$app['dao.basket']->delete($baskets[$i]);
			}
			elseif(isset($_POST["quantite$id"]) && $_POST["quantite$id"]>0){
				$app['dao.basket']->setQuantity($baskets[$i],$_POST["quantite$id"]);
			}
		}
		$app['session']->getFlashBag()->add('success', 'Le panier a été modifié.');
	}
	$baskets = $app['dao.basket']->findAllFromUser($app['user']->getId());
    return $app['twig']->render('user/edit_basket.html.twig', array('leagues' => $leagues, 'baskets' => $baskets));
})->bind('basket_edit');






//////////////////////////////////
/////         Admins         /////
//////////////////////////////////


// Admin home page
$app->get('/admin', function() use ($app) {
    $leagues = $app['dao.league']->findAll();
    $jersey = $app['dao.jersey']->findAll();
    $comments = $app['dao.comment']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin/admin.html.twig', array(
        'jersey' => $jersey,
        'comments' => $comments,
        'users' => $users,
        'leagues' => $leagues ));
})->bind('admin');


// Edit an existing jersey
$app->match('/admin/jersey/{id}/edit', function($id, Request $request) use ($app) {
    $jersey = $app['dao.jersey']->findFromId($id);
    $jerseyForm = $app['form.factory']->create(new JerseyType(), $jersey);
    $jerseyForm->handleRequest($request);
    $leagues = $app['dao.league']->findAll();
    if ($jerseyForm->isSubmitted() && $jerseyForm->isValid()) {
        $app['dao.jersey']->save($jersey);
        $app['session']->getFlashBag()->add('success', 'Le maillot a été correctement modifié.');
    }
    return $app['twig']->render('admin/jersey_form.html.twig', array(
        'title' => 'Modifier un maillot',
        'jerseyForm' => $jerseyForm->createView(),
        'leagues' => $leagues
        ));
})->bind('admin_jersey_edit');


// Remove a jersey
$app->get('/admin/jersey/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.comment']->deleteAllByJersey($id);
    // Delete all basket with this jersey
    $app['dao.basket']->deleteByJersey($id);
    // Delete the jersey
    $app['dao.jersey']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le maillot a été supprimé avec succès.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_jersey_delete');


// Edit an existing comment
$app->match('/admin/comment/{id}/edit', function($id, Request $request) use ($app) {
    $leagues = $app['dao.league']->findAll();

    $comment = $app['dao.comment']->findFromId($id);
    $commentForm = $app['form.factory']->create(new CommentType(), $comment);
    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Le maillot a été corectement modifié.');
    }
    return $app['twig']->render('admin/comment_form.html.twig', array(
        'title' => 'Modifier un commentaire',
        'commentForm' => $commentForm->createView(),
        'leagues' => $leagues));
})->bind('admin_comment_edit');


// Remove a comment
$app->get('/admin/comment/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le commentaire a été supprimé avec succès.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_comment_delete');


// Add a user
$app->match('/admin/user/add', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    $leagues = $app['dao.league']->findAll();
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.digest'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Utilisateur créé avec succès.');
    }
    return $app['twig']->render('admin/user_form.html.twig', array(
        'title' => 'Nouvel utilisateur',
        'userForm' => $userForm->createView(),
        'leagues' => $leagues
    ));
})->bind('admin_user_add');


// Edit an existing user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
    $leagues = $app['dao.league']->findAll();
    $user = $app['dao.user']->find($id);
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password); 
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'Utilisateur corectement modifié');
    }
    return $app['twig']->render('admin/user_form.html.twig', array(
        'title' => 'Modifier un utilisateur',
        'userForm' => $userForm->createView(),
        'leagues' => $leagues));                       
})->bind('admin_user_edit');


// Remove a user
$app->get('/admin/user/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.comment']->deleteAllByUser($id);
    //Delete basket
    $app['dao.basket']->deleteByUser($id);
    // Delete the user
    $app['dao.user']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Utlisateur supprimé avec succès.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_user_delete');
