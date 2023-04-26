<?php

class LeftBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "left";
    private $myRight;
    private $myLeft;
    private $myTop;
    private $myBottom;
    private $crashedCornerTop = false;
    private $crashedCornerSide = false;
    private $underTop = false;
    private $moreSide = false;

    function __construct(){
        $this->myRight = $this->boardX + $this->borderComponentSize;
        $this->myLeft = $this->boardX;
        $this->myTop = $this->boardY+2*($this->borderComponentSize+$this->gap);
        $this->myBottom = $this->boardY+8*($this->borderComponentSize+$this->gap);

    }

    function getPosition(){
        return $this->position;
    }

    function ballCrashedBorder($ball){
        if ($ball->getMyLeft() <= $this->myRight){
            return true;
        }else return false;
    }

    function ballSpeed($ball){
        return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
    }

    function ballCrashedCorner($ball){
        if ($this->underTop){
            if (($ball->getMyTop() <= $this->myTop) || ($ball->getMyBottom() >= $this->myBottom)){
                $this->crashedCornerSide = true;
                return true;
            }else{
                if ($ball->getMyLeft() <= $this->myRight){
                    $this->underTop = true;
                }else{
                    $this->underTop = false;
                }
                return false;
            }
        }elseif ($this->moreSide){
            if ($ball->getMyLeft() <= $this->myRight){
                $this->crashedCornerTop = true;
                return true;
            }else{
                if (($ball->getMyTop() <= $this->myTop) || ($ball->getMyBottom() >= $this->myBottom)){
                    $this->moreSide = true;
                }else{
                    $this->moreSide = false;
                }
                return false;
            }
        }else{
            if ($ball->getMyLeft() <= $this->myRight){
                $this->underTop = true;
            }else{
                $this->underTop = false;
            }
            if (($ball->getMyTop() <= $this->myTop) || ($ball->getMyBottom() >= $this->myBottom)){
                $this->moreSide = true;
            }else{
                $this->moreSide = false;

            }
            return false;
        }
    }

    function ballSpeedCorner($ball){
        $this->moreSide = false;
        $this->underTop = false;
        if ($this->crashedCornerTop){
            $this->crashedCornerTop = false;
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }elseif ($this->crashedCornerSide){
            $this->crashedCornerSide = false;
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }
    }


    function isGoal($ball){
        if (($ball->getMyLeft() <= $this->myLeft) && ($ball->getMyTop() > $this->myTop) && ($ball->getMyBottom() < $this->myBottom)){
            return true;
        }else return false;
    }
}