<?php

class UpperBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "upper";
    private $myBottom;

    function __construct(){
        $this->myBottom = $this->boardY + $this->borderComponentSize;
    }

    function getPosition(){
        return $this->position;
    }

    function ballCrash($ball){
        if ($ball->getMyTop() <= $this->myBottom){
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }else return [$ball->getSpeedX(), $ball->getSpeedY()];
    }

}