<?php

namespace NewSoccerJersey\Form\Task;


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


class UserInfoTask
{
  
    /**
     * @Assert\Regex("/^[A-Z][a-z\- ]+$/")
     */
    private $name;

    /**
     * @Assert\Regex("/^[A-Z][a-z\- ]+$/")
     */
    private $lastName;
    private $address;

    /**
     * @Assert\Regex("/^\d{5}$/")
     */
    private $postalCode;
    private $city;


    

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }



}