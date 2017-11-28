<?php

namespace NewSoccerJersey\Domain;


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
  
    private $id;
    private $name;


    private $email;
    private $lastName;
    private $address;


    private $postalCode;
    private $city;
    private $password;
    private $salt;
    private $role;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getMail() {
        return $this->email;
    }

    public function setMail($email) {
        $this->email = $email;
    }



    public function getUsername() {
        return $this->email;
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






    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getRoles()
    {
        return array($this->getRole());
    }

    public function eraseCredentials() {
        // Nothing to do here
    }







     public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        /*		ON PEUT PAS S'APPELER Théo?????? Ni avoir un nom español?
		On accepte tous les caractères car un Chinois se nomme @ par exemple (http://www.liberation.fr/actualite/2007/08/16/un-bebe-nomme_10230)
		Sinon en France il y a N'golo Kanté donc faut au minimum les accents classiques, le ñ, le '...
		
		$metadata->addPropertyConstraint('name', new Assert\Regex(array(
            'pattern' => '/^[A-Za-z\- ]+$/',
        )));
        $metadata->addPropertyConstraint('lastName', new Assert\Regex(array(
            'pattern' => '/^[A-Za-z\- ]+$/',
        )));
		$metadata->addPropertyConstraint('city', new Assert\Regex(array(
            'pattern' => '/^[A-Za-z\- ]+$/',
        )));*/
        $metadata->addPropertyConstraint('postalCode', new Assert\Regex(array(
            'pattern' => '/^\d{5}$/',
        )));
    }


}