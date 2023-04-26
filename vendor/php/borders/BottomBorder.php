<?php

class BottomBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "bottom";
    private $myTop;
    private $myBottom;
    private $myleft;
    private $myRight;
    private $crashedCornerTop = false;
    private $crashedCornerSide = false;
    private $underTop = false;
    private $moreSide = false;

    function __construct(){
        $this->myTop = $this->boardY+9*($this->borderComponentSize+$this->gap);
        $this->myBottom = $this->boardY+10*($this->borderComponentSize+$this->gap);
        $this->myleft = $this->boardX+2*($this->borderComponentSize+$this->gap);
        $this->myRight = $this->boardX+8*($this->borderComponentSize+$this->gap);
    }

    function getPosition(){
        return $this->position;
    }

    function ballCrashedBorder($ball){
        if ($ball->getMyBottom() >= $this->myTop) {
            return true;
        }else return false;
    }

    function ballSpeed($ball){
        return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
    }


    function ballCrashedCorner($ball){
        if ($this->underTop){
            if (($ball->getMyRight() >= $this->myRight) || ($ball->getMyLeft() <= $this->myleft)){
                $this->crashedCornerSide = true;
                return true;
            }else{
                if ($ball->getMyBottom() >= $this->myTop){
                    $this->underTop = true;
                }else{
                    $this->underTop = false;
                }
                return false;
            }
        }elseif ($this->moreSide){
            if ($ball->getMyBottom() >= $this->myTop){
                $this->crashedCornerTop = true;
                return true;
            }else{
                if (($ball->getMyRight() >= $this->myRight) || ($ball->getMyLeft() <= $this->myleft)){
                    $this->moreSide = true;
                }else{
                    $this->moreSide = false;
                }
                return false;
            }
        }else{
            if ($ball->getMyBottom() >= $this->myTop){
                $this->underTop = true;
            }else{
                $this->underTop = false;
            }
            if (($ball->getMyRight() >= $this->myRight) || ($ball->getMyLeft() <= $this->myleft)){
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
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }elseif ($this->crashedCornerSide){
            $this->crashedCornerSide = false;
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }
    }

    function isGoal($ball){
        if (($ball->getMyBottom() >= $this->myBottom) && ($ball->getMyLeft() > $this->myleft) && ($ball->getMyRight() < $this->myRight)){
            return true;
        }else return false;
    }


}