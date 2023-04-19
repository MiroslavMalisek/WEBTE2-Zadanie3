<?php

class Player
{
    private $connection;
    private $x;
    private $y;

    function __construct($connection){
        $this->connection = $connection;
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

}