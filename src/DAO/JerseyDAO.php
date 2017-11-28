<?php


namespace NewSoccerJersey\DAO;

use NewSoccerJersey\Domain\Jersey;

class JerseyDAO extends DAO
{




    ////////////////////////////
    ////     Reading DB     ////
    ////////////////////////////


    /**
    * Returns all jerseys from the database
    *
    * @return \NewSoccerJersey\Domain\Jesrey array
    */
    public function findAll(){
        $sql = "select * from jersey order by jersey_id desc;";
        $result = $this->getDb()->fetchAll($sql);


        // Convert query result to an array of domain objects
        $jerseys = array();
        foreach ($result as $row) {
            $jerseyId = $row['jersey_id'];
            $jerseys[$jerseyId] = $this->buildDomainObject($row);;
        }
        return $jerseys;
    }


    /**
    * Returns all jerseys from the DB matching the given League
    *
    * @param int $id The league's id
    * @return \NewSoccerJersey\Domain\Jesrey array
    */
    public function findAllFromLeague($id){
        $sql = "select * from jersey where jersey_league=? order by jersey_id desc;";
        $result = $this->getDb()->fetchAll($sql, array($id));

        $jerseys = array();
        foreach($result as $row){
            $jerseyId = $row['jersey_id'];
            $jerseys[$jerseyId] = $this->buildDomainObject($row);
        }

        return $jerseys;
    }


    /**
    * Returns Jersey from the DB matching the given id
    *
    * @param int $id The jersey's id
    * @return \NewSoccerJersey\Domain\Jesrey | throws an exception if no matching jersey is found
    */
    public function findFromId($id){
        $sql = "select * from jersey where jersey_id=?;";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No jersey matching id " . $id);
    }








    ////////////////////////////
    /////    Writing DB    /////
    ////////////////////////////


    /**
    * Delete jersey from DB matching the gieven id
    *
    * @param int $id The jersey's id
    */
    public function delete($id) {
        $this->getDb()->delete('jersey', array('jersey_id' => $id));
    }

    /**
    * Saves the jersey's data into the database if jersey is not already in the database, otherwise, updates its informations
    *
    * @param Jersey $jersey the jersey to save
    */
    public function save(Jersey $jersey) {
        $jerseyData = array(
            'jersey_name' => $jersey->getName(),
            'jersey_desc' => $jersey->getDescription(),
            'jersey_type' => $jersey->getType(),
            'jersey_team' => $jersey->getTeam(),
            'jersey_price' => $jersey->getPrice(),
            );

        if ($jersey->getId()) {
            // The jersey has already been saved : update it
            $this->getDb()->update('jersey', $jerseyData, array('jersey_id' => $jersey->getId()));
        } else {
            // The jersey has never been saved : insert it
            $this->getDb()->insert('jersey', $jerseyData);
            // Get the id of the newly created jersey and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $jersey->setId($id);
        }
    }








    ////////////////////////////
    /////      Object      /////
    ////////////////////////////


    /**
    * Creates a Jersey object based on a DB row.
    *
    * @param array $row The DB row containing Jersey data.
    * @return \NewSoccerJersey\Domain\Jersey
    */
    public function buildDomainObject($row){
        $jersey = new Jersey();
        $jersey->setId($row['jersey_id']);
        $jersey->setName($row['jersey_name']);
        $jersey->setDescription($row['jersey_desc']);
        $jersey->setType($row['jersey_type']);
        $jersey->setTeam($row['jersey_team']);
        $jersey->setPrice($row['jersey_price']);
        $jersey->setImage($row['jersey_image']);
        $jersey->setLeague($row['jersey_league']);


        return $jersey;
    }


}
