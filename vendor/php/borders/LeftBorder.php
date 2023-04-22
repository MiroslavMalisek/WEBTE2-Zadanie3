<?php

class LeftBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "left";
    private $myRight;

    function __construct(){
        $this->myRight = $this->boardX + $this->borderComponentSize;
    }

    function getPosition(){
        return $this->position;
    }

    function ballCrash($ball){
        if ($ball->getMyLeft() <= $this->myRight){
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }else return [$ball->getSpeedX(), $ball->getSpeedY()];
    }
}