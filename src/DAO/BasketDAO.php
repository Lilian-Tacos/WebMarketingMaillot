<?php


namespace NewSoccerJersey\DAO;

use NewSoccerJersey\Domain\Basket;

class BasketDAO extends DAO
{
    
    private $userDAO;
    private $jerseyDAO;

    ////////////////////////////
    ////     Reading DB     ////
    ////////////////////////////



    public function findAll(){
        $req = $this->getDb()->prepare("select * from basket;");
        $req->execute(array($user_id));
        $baskets = array();
        while ($row = $req->fetch()) {
            array_push($baskets, $this->buildDomainObject($row));
        }
        return $baskets;
    }


    public function findFromJersey($jersey_id){
        $req = $this->getDb()->prepare("select * from basket where jersey_id = ?;");
        $req->execute(array($jersey_id));
        $baskets = array();
        while ($row = $req->fetch()) {
            array_push($baskets, $this->buildDomainObject($row));
        }
        return $baskets;
    }


    public function findAllFromUser($user_id){
        $req = $this->getDb()->prepare("select * from basket where user_id = ?;");
        $req->execute(array($user_id));
        $baskets = array();
        while ($row = $req->fetch()) {
            array_push($baskets, $this->buildDomainObject($row));
        }
        return $baskets;
    }
	
	public function findOne($user_id, $jersey_id){
        $req = $this->getDb()->prepare("select * from basket where user_id = ? and jersey_id = ?;");
        $req->execute(array($user_id, $jersey_id));
        $baskets = array();
        while ($row = $req->fetch()) {
            array_push($baskets, $this->buildDomainObject($row));
        }
        return $baskets;
    }
	
	








    ////////////////////////////
    /////    Writing DB    /////
    ////////////////////////////


	public function upQuantity(Basket $basket){
		$req = $this->getDb()->prepare("update basket set quantite=? where jersey_id=? and user_id=?;");
        $req->execute(array($basket->getQuantity() + 1, $basket->getJersey()->getId(), $basket->getUser()->getId()));
	}
	
	public function setQuantity(Basket $basket, $quantity){
		$req = $this->getDb()->prepare("update basket set quantite=? where jersey_id=? and user_id=?;");
        $req->execute(array($quantity, $basket->getJersey()->getId(), $basket->getUser()->getId()));
	}
	
	public function delete(Basket $basket) {
		$req = $this->getDb()->prepare("delete from basket where jersey_id=? and user_id=?;");
        $req->execute(array($basket->getJersey()->getId(), $basket->getUser()->getId()));
    }

    public function save(Basket $basket) {
		
        $basketData = array(
            'jersey_id' => $basket->getJersey()->getId(),
            'user_id' => $basket->getUser()->getId(),
            'quantite' => $basket->getQuantity()
            );

        $this->getDb()->insert('basket', $basketData);
    }
    
    public function deleteByJersey($jerseyid)
    {
        $this->getDb()->delete("basket",array("jersey_id"=> $jerseyid));
    }
    
    public function deleteByUser($userid)
    {
        $this->getDb()->delete("basket",array("user_id"=> $userid));
    }








    ////////////////////////////
    /////      Object      /////
    ////////////////////////////


    public function setJerseyDAO(JerseyDAO $jerseyDAO) {
        $this->jerseyDAO = $jerseyDAO;
    }

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }



	public function buildDomainObject($row){
		$basket = new Basket();

        $basket->setQuantity($row['quantite']);

        if (array_key_exists('user_id', $row)){
            $userId = $row['user_id'];
            $user = $this->userDAO->find($userId);
            $basket->setUser($user);
        }
        if (array_key_exists('jersey_id', $row)){
            $jerseyId = $row['jersey_id'];
            $jersey = $this->jerseyDAO->findFromId($jerseyId);
            $basket->setJersey($jersey);

        }

		return $basket;
    }

}
