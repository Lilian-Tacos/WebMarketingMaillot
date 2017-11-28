<?php

namespace NewSoccerJersey\Domain;

class Jersey
{
  private $id;
  private $name;
  private $description;
  private $type;
  private $team;
  private $price;
  private $image;
  private $league;


  public function setId($id){
    $this->id = $id;
  }

  public function setName($name){
    $this->name = $name;
  }

  public function setDescription($description){
    $this->description = $description;
  }

  public function setType($type){
    $this->type = $type;
  }

  public function setTeam($team){
    $this->team = $team;
  }

  public function setPrice($price){
    $this->price = $price;
  }

  public function setImage($image){
    $this->image = $image;
  }

  public function setLeague($league){
    $this->league = $league;
  }

  public function getId(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }

  public function getDescription(){
    return $this->description;
  }

  public function getType(){
    return $this->type;
  }

  public function getTeam(){
    return $this->team;
  }

  public function getPrice(){
    return $this->price;
  }

  public function getImage(){
    return $this->image;
  }

  public function getLeague(){
    return $this->league;
  }

}