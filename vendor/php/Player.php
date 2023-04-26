<?php

class Player
{
    private $connection;
    private $position;
    private $boardX = 50;
    private $boardY = 50;
    private $gap = 0.5;
    private $x;
    private $y;
    private $compSize = 40;
    private $width;
    private $height;
    private $xLives;
    private $yLives;
    private $first;
    private $name;
    private $lives = 3;

    private $myTop;
    private $myLeft;
    private $myRight;
    private $myBottom;
    private $overEdge = false;
    private $inside = false;

    function __construct($connection, $position, $first){
        $this->connection = $connection;
        $this->position = $position;
        $this->first = $first;

        switch ($this->position->getPosition()){
            case "upper":
                $this->width = 80;
                $this->height = $this->compSize;
                $this->myBottom = $this->boardY + $this->compSize;
                break;
            case "bottom":
                $this->width = 80;
                $this->height = $this->compSize;
                $this->myTop = $this->boardY+9*($this->compSize+$this->gap);
                break;
            case "left":
                $this->width = $this->compSize;
                $this->height = 80;
                $this->myRight = $this->boardX + $this->compSize;
                break;
            case "right":
                $this->width = $this->compSize;
                $this->height = 80;
                $this->myLeft = $this->boardX+9*($this->compSize+$this->gap);
                break;
        }
    }

    function crashedBall($ball){
        if (!$this->overEdge){
            if ($this->position->getPosition() == "upper"){
                if ($this->checkCrashUpper($ball)){
                    return true;
                }else{
                    if ($ball->getMyTop() <= $this->myBottom){
                        $this->overEdge = true;
                    }else{
                        $this->overEdge = false;
                    }
                }
            }
            elseif ($this->position->getPosition() == "bottom"){
                if ($this->checkCrashBottom($ball)){
                    return true;
                }else{
                    if ($ball->getMyBottom() >= $this->myTop){
                        $this->overEdge = true;
                    }else{
                        $this->overEdge = false;
                    }
                }
            }
            elseif ($this->position->getPosition() == "left"){
                if ($this->checkCrashLeft($ball)){
                    return true;
                }else{
                    if ($ball->getMyLeft() <= $this->myRight){
                        $this->overEdge = true;
                    }else{
                        $this->overEdge = false;
                    }
                }
            }
            elseif ($this->position->getPosition() == "right"){
                if ($this->checkCrashRight($ball)){
                    return true;
                }else{
                    if ($ball->getMyRight() >= $this->myLeft){
                        $this->overEdge = true;
                    }else{
                        $this->overEdge = false;
                    }
                }
            }
        }
        if ($this->position->getPosition() == "upper"){
            if ($ball->getMyTop() <= $this->myBottom){
                $this->overEdge = true;
            }else{
                $this->overEdge = false;
            }
        }
        if ($this->position->getPosition() == "bottom"){
            if ($ball->getMyBottom() >= $this->myTop){
                $this->overEdge = true;
            }else{
                $this->overEdge = false;
            }
        }
        if ($this->position->getPosition() == "left"){
            if ($ball->getMyLeft() <= $this->myRight){
                $this->overEdge = true;
            }else{
                $this->overEdge = false;
            }
        }
        if ($this->position->getPosition() == "right"){
            if ($ball->getMyRight() >= $this->myLeft){
                $this->overEdge = true;
            }else{
                $this->overEdge = false;
            }
        }
        return false;
    }

    function ballSpeedPlayer($ball){
        $this->overEdge = false;
        if (($this->position->getPosition() == "upper") || ($this->position->getPosition() == "bottom")){
            return [$ball->getSpeedX(), ($ball->getSpeedY())*(-1)];
        }elseif (($this->position->getPosition() == "left") || ($this->position->getPosition() == "right")){
            return [($ball->getSpeedX())*(-1), $ball->getSpeedY()];
        }
    }

    private function checkCrashUpper($ball){
        if (($ball->getMyTop() <= $this->myBottom) && ($ball->getMyLeft() < ($this->x + $this->width)) && ($ball->getMyRight() > $this->x)){
            return true;
        }else{
            return false;
        }
    }
    private function checkCrashBottom($ball){
        if (($ball->getMyBottom() >= $this->myTop) && ($ball->getMyLeft() < ($this->x + $this->width)) && ($ball->getMyRight() > $this->x)){
            return true;
        }else{
            return false;
        }
    }
    private function checkCrashLeft($ball){
        if (($ball->getMyleft() <= $this->myRight) && ($ball->getMyTop() < ($this->y + $this->height)) && ($ball->getMyBottom() > $this->y)){
            return true;
        }else{
            return false;
        }
    }
    private function checkCrashRight($ball){
        if (($ball->getMyRight() >= $this->myLeft) && ($ball->getMyTop() < ($this->y + $this->height)) && ($ball->getMyBottom() > $this->y)){
            return true;
        }else{
            return false;
        }
    }

    function getConnection(){
        return $this->connection;
    }
    function setX($x){
        $this->x = $x;
    }
    function setY($y){
        $this->y = $y;
    }
    function getX(){
        return $this->x;
    }
    function getY(){
        return $this->y;
    }
    function getWidth(){
        return $this->width;
    }
    function getHeight(){
        return $this->height;
    }
    function setLivesX($xLives){
        $this->xLives = $xLives;
    }
    function setLivesY($yLives){
        $this->yLives = $yLives;
    }
    function getLivesX(){
        return $this->xLives;
    }
    function getLivesY(){
        return $this->yLives;
    }
    /*function setPosition($position){
        $this->position = $position;
    }*/
    function getPosition(){
        return $this->position;
    }
    function isFirst(){
        return $this->first;
    }
    function setFirst($first){
        $this->first = $first;
    }
    function setName($name){
        $this->name = $name;
    }
    function getName(){
        return $this->name;
    }

    function setLives($lives){
        $this->lives = $lives;
    }
    function getLives(){
        return $this->lives;
    }

}