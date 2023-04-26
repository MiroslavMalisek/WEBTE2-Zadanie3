<?php

class RightBorder
{
    private $borderComponentSize = 40;
    private $gap = 0.5;
    private $boardX = 50;
    private $boardY = 50;
    private $position = "right";
    private $myLeft;
    private $myRight;
    private $myTop;
    private $myBottom;
    private $crashedCornerTop = false;
    private $crashedCornerSide = false;
    private $aboveBottom = false;
    private $moreSide = false;

    function __construct(){
        $this->myLeft = $this->boardX+9*($this->borderComponentSize+$this->gap);
        $this->myRight = $this->boardX+10*($this->borderComponentSize+$this->gap);
        $this->myTop = $this->boardY+2*($this->borderComponentSize+$this->gap);
        $this->myBottom = $this->boardY+8*($this->borderComponentSize+$this->gap);

    }

    function getPosition(){
        return $this->position;
    }

    function ballCrashedBorder($ball){
        if ($ball->getMyRight() >= $this->myLeft){
            return true;
        }else return false;
    }

    function ballSpeed($ball){
        return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
    }

    function ballCrashedCorner($ball){
        if ($this->aboveBottom){
            if (($ball->getMyTop() <= $this->myTop) || ($ball->getMyBottom() >= $this->myBottom)){
                $this->crashedCornerSide = true;
                return true;
            }else{
                if ($ball->getMyRight() >= $this->myLeft){
                    $this->aboveBottom = true;
                }else{
                    $this->aboveBottom = false;
                }
                return false;
            }
        }elseif ($this->moreSide){
            if ($ball->getMyRight() >= $this->myLeft){
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
            if ($ball->getMyRight() >= $this->myLeft){
                $this->aboveBottom = true;
            }else{
                $this->aboveBottom = false;
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
        $this->aboveBottom = false;
        if ($this->crashedCornerTop){
            $this->crashedCornerTop = false;
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }elseif ($this->crashedCornerSide){
            $this->crashedCornerSide = false;
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }
    }

    function isGoal($ball){
        if (($ball->getMyRight() >= $this->myRight) && ($ball->getMyTop() > $this->myTop) && ($ball->getMyBottom() < $this->myBottom)){
            return true;
        }else return false;
    }
}