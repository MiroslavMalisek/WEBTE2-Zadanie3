<?php

class UpperBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "upper";
    private $myBottom;
    private $myTop;
    private $myleft;
    private $myRight;
    private $crashedCornerTop = false;
    private $crashedCornerSide = false;
    private $aboveBottom = false;
    private $moreSide = false;

    function __construct(){
        $this->myBottom = $this->boardY + $this->borderComponentSize;
        $this->myTop = $this->boardY;
        $this->myleft = $this->boardX+2*($this->borderComponentSize+$this->gap);
        $this->myRight = $this->boardX+8*($this->borderComponentSize+$this->gap);

    }

    function getPosition(){
        return $this->position;
    }

    function ballCrashedBorder($ball){
        if ($ball->getMyTop() <= $this->myBottom){
            return true;
        }else false;
    }

    function ballSpeed($ball){
        return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
    }

    function ballCrashedCorner($ball){
        if ($this->aboveBottom){
            if (($ball->getMyRight() >= $this->myRight) || ($ball->getMyLeft() <= $this->myleft)){
                $this->crashedCornerSide = true;
                return true;
            }else{
                if ($ball->getMyTop() <= $this->myBottom){
                    $this->aboveBottom = true;
                }else{
                    $this->aboveBottom = false;
                }
                return false;
            }
        }elseif ($this->moreSide){
            if ($ball->getMyTop() <= $this->myBottom){
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
            if ($ball->getMyTop() <= $this->myBottom){
                $this->aboveBottom = true;
            }else{
                $this->aboveBottom = false;
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
        $this->aboveBottom = false;
        if ($this->crashedCornerTop){
            $this->crashedCornerTop = false;
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }elseif ($this->crashedCornerSide){
            $this->crashedCornerSide = false;
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }
    }

    function isGoal($ball){
        if (($ball->getMyTop() <= $this->myTop) && ($ball->getMyLeft() > $this->myleft) && ($ball->getMyRight() < $this->myRight)){
            return true;
        }else return false;
    }

}