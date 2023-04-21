<?php

class Player
{
    private $connection;
    private $position;
    private $x;
    private $y;
    private $xLives;
    private $yLives;
    private $first;
    private $name;
    private $lives = 2;

    function __construct($connection, $position, $first){
        $this->connection = $connection;
        $this->position = $position;
        $this->first = $first;
    }

    function getConnection(){
        return $this->connection;
    }
    function setX($x){
        $this->x = $x;
    }
    function setY($y){
        $this->y = $y;
    }
    function getX(){
        return $this->x;
    }
    function getY(){
        return $this->y;
    }
    function setLivesX($xLives){
        $this->xLives = $xLives;
    }
    function setLivesY($yLives){
        $this->yLives = $yLives;
    }
    function getLivesX(){
        return $this->xLives;
    }
    function getLivesY(){
        return $this->yLives;
    }
    /*function setPosition($position){
        $this->position = $position;
    }*/
    function getPosition(){
        return $this->position;
    }
    function isFirst(){
        return $this->first;
    }
    function setFirst($first){
        $this->first = $first;
    }
    function setName($name){
        $this->name = $name;
    }
    function getName(){
        return $this->name;
    }

    function setLives($lives){
        $this->lives = $lives;
    }
    function getLives(){
        return $this->lives;
    }

}