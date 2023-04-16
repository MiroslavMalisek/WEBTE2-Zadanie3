class BorderComponent{
    constructor(myGameArea, x, y) {
        this.width = 40;
        this.height = 40;
        this.x = x;
        this.y = y;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = "grey";
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
    }
}