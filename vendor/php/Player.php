<?php

class Player
{
    private $connection;
    private $position;
    private $x;
    private $y;
    private $first;
    private $name;

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

}