<?php

class BorderComponent
{
    private $width = 40;
    private $height = 40;
    private $x;
    private $y;
    private $ctx;

    function __construct($myGameArea, $x, $y){
        $this->ctx = $myGameArea->context;
        $this->x = $x;
        $this->y = $y;
        $this->ctx->fillStyle = "grey";
        $this->ctx->fillRect($this->x, $this->y, $this->width, $this->height);
    }
}