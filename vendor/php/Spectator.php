<?php

class Spectator
{
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }

    function getConnection(){
        return $this->connection;
    }

}