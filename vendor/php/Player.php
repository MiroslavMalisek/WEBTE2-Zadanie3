<?php

class Player
{
    private $connection;
    private $position;
    private $x;
    private $y;

    function __construct($connection, $position){
        $this->connection = $connection;
        $this->position = $position;
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
    /*function setPosition($position){
        $this->position = $position;
    }*/
    function getPosition(){
        return $this->position;
    }

}