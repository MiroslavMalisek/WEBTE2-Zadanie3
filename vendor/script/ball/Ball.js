class Ball {
    constructor(myGameArea) {
        this.startX = myGameArea.startX+5*(myGameArea.componentSize+myGameArea.gap);
        this.startY = myGameArea.startY+5*(myGameArea.componentSize+myGameArea.gap);
        this.x = this.startX;
        this.y = this.startY;
        this.color = '#222831';
        this.radius = 16;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.beginPath();
        this.ctx.arc(this.startX, this.startY, this.radius, 0, 2 * Math.PI);
        this.ctx.fill();
    }

    updateBall(myGameArea, x, y){
        this.x = x;
        this.y = y;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = this.color;
        this.ctx.beginPath();
        this.ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
        this.ctx.fill();
    }
}