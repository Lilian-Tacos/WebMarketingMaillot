<?php

namespace NewSoccerJersey\DAO;

use NewSoccerJersey\Domain\Comment;

class CommentDAO extends DAO
{

    private $jerseyDAO;
    private $userDAO;





    ////////////////////////////
    ////     Reading DB     ////
    ////////////////////////////



    /**
    * Returns all comments on the given jersey.
    *
    * @param integer $jerseyId The jersey's id.
    * @return \NewSoccerJersey\Domain\Comment array
    */
    public function findAllFromJersey($jerseyId) {

        // The associated article is retrieved only once
        $jersey = $this->jerseyDAO->findFromId($jerseyId);

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select * from comment where jer_id=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($jerseyId));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setJersey($jersey);
            $comments[$comId] = $comment;
        }
        return $comments;
    }
    

    /**
    * Returns all comments of the database.
    *
    * @return \NewSoccerJersey\Domain\Comment array
    */
    public function findAll() {
        $sql = "select * from comment order by com_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }


    /**
    * Returns a comment matching the given id.
    *
    * @param int $id The comment's id
    * @return \NewSoccerJersey\Domain\Comment | throws an exception if no matching comment found
    */
    public function findFromId($id) {
        $sql = "select * from comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }







    ////////////////////////////
    /////    Writing DB    /////
    ////////////////////////////



    /**
    * Saves the comment's data into the database if comment is not already in the database, otherwise, updates its informations
    *
    * @param Comment $comment the comment to save
    */
    public function save(Comment $comment) {
        $commentData = array(
            'jer_id' => $comment->getJersey()->getId(),
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
            );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('comment', $commentData, array('com_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }



    /**
    * deletes the comment mathing the given id from the database
    *
    * @param int $id the comment's id
    */
    public function delete($id) {
        $this->getDb()->delete('comment', array('com_id' => $id));
    }

    /**
    * deletes all comments from the given user
    *
    * @param int $userId the user's id
    */
    public function deleteAllByUser($userId) {
        $this->getDb()->delete('comment', array('usr_id' => $userId));
    }

    /**
    * deletes all comments about the given jersey
    *
    * @param int $jerseyId the jersey's id
    */
    public function deleteAllByJersey($jerseyId) {
        $this->getDb()->delete('comment', array('jer_id' => $jerseyId));
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




    /**
    * Creates a Comment object based on a DB row.
    *
    * @param array $row The DB row containing Comment data.
    * @return \NewSoccerJersey\Domain\Comment
    */
    protected function buildDomainObject($row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('jer_id', $row)) {
            // Find and set the associated article
            $jerseyId = $row['jer_id'];
            $jersey = $this->jerseyDAO->findFromId($jerseyId);
            $comment->setJersey($jersey);
        }
        if (array_key_exists('usr_id', $row)) {
            // Find and set the associated author
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }
        
        return $comment;
    }
    


}