<?php

class RightBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "right";
    private $myLeft;

    function __construct(){
        $this->myLeft = $this->boardX+9*($this->borderComponentSize+$this->gap);
    }

    function getPosition(){
        return $this->position;
    }

    function ballCrash($ball){
        if ($ball->getMyRight() >= $this->myLeft){
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }else return [$ball->getSpeedX(), $ball->getSpeedY()];
    }
}