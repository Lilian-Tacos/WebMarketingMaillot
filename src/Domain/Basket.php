<?php

namespace NewSoccerJersey\Domain;

class Basket
{
  private $user;
  private $jersey;
  private $quantity;

  public function setQuantity($qt){
    $this->quantity = $qt;
  }

  public function getQuantity(){
    return $this->quantity;
  }

  public function setUser($user){
    $this->user = $user;
  }

  public function setJersey($jersey){
    $this->jersey = $jersey;
  }

  public function getJersey(){
    return $this->jersey;
  }

  public function getUser(){
    return $this->user;
  }
}
