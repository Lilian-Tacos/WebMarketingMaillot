<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new NewSoccerJersey\DAO\UserDAO($app['db']);
            }),
        ),
    ),
    
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
        array('^/user', 'ROLE_USER')
    ),

));


$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());






// Register services
$app['dao.league'] = $app->share(function ($app) {
    return new NewSoccerJersey\DAO\LeagueDAO($app['db']);
});

$app['dao.jersey'] = $app->share(function ($app) {
    return new NewSoccerJersey\DAO\JerseyDAO($app['db']);
});

$app['dao.user'] = $app->share(function ($app) {
    return new NewSoccerJersey\DAO\UserDAO($app['db']);
});

$app['dao.basket'] = $app->share(function ($app) {
    $basketDAO = new NewSoccerJersey\DAO\BasketDAO($app['db']);
    $basketDAO->setJerseyDAO($app['dao.jersey']);
    $basketDAO->setUserDAO($app['dao.user']);
    return $basketDAO;
});


$app['dao.comment'] = $app->share(function ($app) {
    $commentDAO = new NewSoccerJersey\DAO\CommentDAO($app['db']);
    $commentDAO->setJerseyDAO($app['dao.jersey']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
});

$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));


// Erreurs
$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = "Vous n'avez pas les droits d'accès suffisants.";
            break;
        case 404:
            $message = "La ressource demandée est introuvable.";
            break;
        default:
            $message = "Une erreur s'est produite.";
    }
	$leagues = $app['dao.league']->findAll();
    return $app['twig']->render('error.html.twig', array('message' => $message, 'leagues' => $leagues));
});
