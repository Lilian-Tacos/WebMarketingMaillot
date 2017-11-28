<?php

namespace NewSoccerJersey\Domain;

class League
{
  private $id;
  private $name;


  public function setId($id){
    $this->id = $id;
  }

  public function setName($name){
    $this->name = $name;
  }

  public function getName(){
    return $this->name;
  }

  public function getId(){
    return $this->id;
  }
}
