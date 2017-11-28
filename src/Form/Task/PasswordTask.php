<?php

namespace NewSoccerJersey\Form\Task;

//use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class PasswordTask
{
    
    private $oldPassword;
    private $newPassword;


    

    public function getOldPassword() {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword() {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
    }


    
}