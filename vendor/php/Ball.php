<?php

class Ball
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $radius = 16;
    private $startX;
    private $startY;
    private $x;
    private $y;
    private $myTop;
    private $myBottom;
    private $myLeft;
    private $myRight;
    private $speedX = 0.5;
    private $speedY = 0.2;

    function __construct(){
        $this->startX = $this->boardX+5*($this->borderComponentSize+$this->gap);
        $this->startY = $this->boardY+5*($this->borderComponentSize+$this->gap);
        $this->x = $this->startX;
        $this->y = $this->startY;
        $this->myTop = $this->y - $this->radius;
        $this->myLeft = $this->x - $this->radius;
        $this->myBottom = $this->y + $this->radius;
        $this->myRight = $this->x + $this->radius;
    }

    function newPosition(){
        $this->x += $this->speedX;
        $this->y += $this->speedY;
        $this->myTop = $this->y - $this->radius;
        $this->myLeft = $this->x - $this->radius;
        $this->myBottom = $this->y + $this->radius;
        $this->myRight = $this->x + $this->radius;
    }


    function setSpeedX($speedX){
        $this->speedX = $speedX;
    }
    function setSpeedY($speedY){
        $this->speedY = $speedY;
    }

    function getX(){
        return $this->x;
    }
    function getY(){
        return $this->y;
    }

    function getSpeedX(){
        return $this->speedX;
    }
    function getSpeedY(){
        return $this->speedY;
    }

    function getMyTop(){
        return $this->myTop;
    }
    function getMyLeft(){
        return $this->myLeft;
    }
    function getMyRight(){
        return $this->myRight;
    }
    function getMyBottom(){
        return $this->myBottom;
    }


}