class PlayerComponent{
    constructor(myGameArea, color, width, height, x, y) {
        this.color = color;
        this.width = width;
        this.height = height;
        this.x = x;
        this.y = y;
        this.speedX = 0;
        this.speedY = 0;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
    }
    updateComponent(myGameArea){
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
    }
    newPosition(){
        this.x += this.speedX;
        this.y += this.speedY;
    }
}