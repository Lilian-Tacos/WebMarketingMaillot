<?php

namespace NewSoccerJersey\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use NewSoccerJersey\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{



    ////////////////////////////
    ////     Reading DB     ////
    ////////////////////////////



    /**
    * Returns all users from the database
    *
    * @return \NewSoccerJersey\Domain\User array
    */
    public function findAll() {
        $sql = "select * from user order by user_role, user_name";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $users = array();
        foreach ($result as $row) {
            $id = $row['user_id'];
            $users[$id] = $this->buildDomainObject($row);
        }
        return $users;
    }


    /**
    * Returns a user matching the supplied id.
    *
    * @param integer $id The user id.
    * @return \NewSoccerJersey\Domain\User|throws an exception if no matching user is found
    */
    public function find($id) {
        $sql = "select * from user where user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No user matching id " . $id);
    }



    /**
    * Returns a user matching the supplied mail.
    *
    * @param integer $mail The user mail.
    * @return \NewSoccerJersey\Domain\User|throws an exception if no matching user mail found
    */
    public function loadUserByUsername($mail) {
        $sql = "select * from user where user_mail=?";
        $row = $this->getDb()->fetchAssoc($sql, array($mail));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $mail));
    }
	
	public function existeDeja($mail) {
        $sql = "select * from user where user_mail=?";
        $row = $this->getDb()->fetchAssoc($sql, array($mail));

        if ($row)
            return true;
        else
            return false;
    }








    ////////////////////////////
    /////    Writing DB    /////
    ////////////////////////////



    /**
    * Saves the user's data into the database if user is not already in the database, otherwise, updates its informations
    *
    * @param User $user the user to save
    */
    public function save(User $user) {
        $userData = array(
            'user_name' => $user->getName(),
            'user_mail' => $user->getMail(),
            'user_address' => $user->getAddress(),
            'user_last_name' => $user->getLastName(),
            'user_postal_code' => $user->getPostalCode(),
            'user_city' => $user->getCity(),
            'user_salt' => $user->getSalt(),
            'user_password' => $user->getPassword(),
            'user_role' => $user->getRole()
            );

        if ($user->getId()) {
            // The user has already been saved : update it
            $this->getDb()->update('user', $userData, array('user_id' => $user->getId()));
        } else {
            // The user has never been saved : insert it
            $this->getDb()->insert('user', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
    * deletes the user mathing the given id from the database
    *
    * @param int $id the user's id
    */
    public function delete($id) {
        // Delete the user
        $this->getDb()->delete('user', array('user_id' => $id));
    }








    ////////////////////////////
    /////      Object      /////
    ////////////////////////////



    /**
    * {@inheritDoc}
    */
    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getMail());
    }


    /**
    * {@inheritDoc}
    */
    public function supportsClass($class) {
        return 'NewSoccerJersey\Domain\User' === $class;
    }


    /**
    * Creates a User object based on a DB row.
    *
    * @param array $row The DB row containing User data.
    * @return \NewSoccerJersey\Domain\User
    */
    protected function buildDomainObject($row) {
        $user = new User();

        $user->setId($row['user_id']);
        $user->setName($row['user_name']);
        $user->setMail($row['user_mail']);

        $user->setLastName($row['user_last_name']);
        $user->setAddress($row['user_address']);
        $user->setPostalCode($row['user_postal_code']);
        $user->setCity($row['user_city']);

        $user->setPassword($row['user_password']);
        $user->setSalt($row['user_salt']);
        $user->setRole($row['user_role']);
        return $user;
    }
    

}