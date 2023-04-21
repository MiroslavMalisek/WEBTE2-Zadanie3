class PlayerComponent{
    constructor(myGameArea, lives, color, width, height, x, y, xLives, yLives) {
        this.lives = lives;
        this.color = color;
        this.width = width;
        this.height = height;
        this.x = x;
        this.y = y;
        this.xLives = xLives;
        this.yLives = yLives;
        this.speedX = 0;
        this.speedY = 0;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
        this.ctx.font = "20px Arial";
        this.ctx.fillText(this.lives,this.xLives,this.yLives);
    }
    updateComponent(myGameArea){
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
        this.ctx.font = "20px Arial";
        this.ctx.fillStyle = 'black';
        this.ctx.fillText(this.lives,this.xLives,this.yLives);
    }
    newPosition(){
        this.x += this.speedX;
        this.y += this.speedY;
        this.xLives += this.speedX;
        this.yLives += this.speedY;
    }
}