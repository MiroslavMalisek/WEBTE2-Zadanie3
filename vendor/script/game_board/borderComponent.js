class BorderComponent{
    constructor(myGameArea, x, y) {
        this.width = myGameArea.componentSize;
        this.height = myGameArea.componentSize;
        this.x = x;
        this.y = y;
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = "grey";
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
    }

    updateComponent(myGameArea){
        this.ctx = myGameArea.context;
        this.ctx.fillStyle = "grey";
        this.ctx.fillRect(this.x, this.y, this.width, this.height);
    }
}