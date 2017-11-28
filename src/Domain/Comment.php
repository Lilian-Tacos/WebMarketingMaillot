<?php

namespace NewSoccerJersey\Domain;

class Comment
{
    
    private $id;
    private $author;
    private $content;
    private $jersey;



    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getJersey() {
        return $this->jersey;
    }

    public function setJersey(Jersey $jersey) {
        $this->jersey = $jersey;
    }
}
