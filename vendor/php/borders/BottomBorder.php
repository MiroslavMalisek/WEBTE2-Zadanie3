<?php

class BottomBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "bottom";
    private $myTop;

    function __construct(){
        $this->myTop = $this->boardY+9*($this->borderComponentSize+$this->gap);
    }

    function getPosition(){
        return $this->position;
    }

    function ballCrash($ball){
        if ($ball->getMyBottom() >= $this->myTop){
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }else return [$ball->getSpeedX(), $ball->getSpeedY()];
    }


}