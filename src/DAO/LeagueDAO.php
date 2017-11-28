<?php


namespace NewSoccerJersey\DAO;

use NewSoccerJersey\Domain\League;

class LeagueDAO extends DAO
{





  ////////////////////////////
  ////     Reading DB     ////
  ////////////////////////////



  /**
  * Returns all leagues from the database
  *
  * @return \NewSoccerJersey\Domain\League array
  */
  public function findAll(){
    $sql = "select * from league order by league_id desc;";
    $result = $this->getDb()->fetchAll($sql);


    // Convert query result to an array of domain objects
    $leagues = array();
    foreach ($result as $row) {
      $leagueId = $row['league_id'];
      $leagues[$leagueId] = $this->buildDomainObject($row);
    }
    return $leagues;
  }


  /**
  * Returns a League matching the given id
  *
  * @param int $id The league's id
  * @return \NewSoccerJersey\Domain\League | throws an exception if no matching league is found
  */
  public function findFromId($id){
    $sql = "select * from league where league_id=?;";
    $row = $this->getDb()->fetchAssoc($sql, array($id));

    if ($row)
      return $this->buildDomainObject($row);
    else
      throw new \Exception("No league matching id " . $id);
  }








  ////////////////////////////
  /////    Writing DB    /////
  ////////////////////////////











  ////////////////////////////
  /////      Object      /////
  ////////////////////////////



  /**
  * Creates a League object based on a DB row.
  *
  * @param array $row The DB row containing League data.
  * @return \NewSoccerJersey\Domain\League
  */
  public function buildDomainObject($row){
    $league = new League();
    $league->setId($row['league_id']);
    $league->setName($row['league_name']);

    return $league;
  }

}
