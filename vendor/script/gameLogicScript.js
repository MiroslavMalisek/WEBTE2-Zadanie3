//import {BorderComponent} from 'game_board/borderComponent.js'

var borderComponents = [];
function startGame() {
    myGameArea.start();
    //x start of border at canvas
    let x = 50;
    //y start of border at canvas
    let y = 50;
    //corners
    const borderComponent = new BorderComponent(myGameArea, x, y);
    borderComponents.push(borderComponent);
    borderComponents.push(new BorderComponent(myGameArea, x+30+0.5, y));
    borderComponents.push(new BorderComponent(myGameArea, x, y+30+0.5));

    borderComponents.push(new BorderComponent(myGameArea, x+8*30+0.5, y));
    borderComponents.push(new BorderComponent(myGameArea, x+9*30+0.5, y));
    borderComponents.push(new BorderComponent(myGameArea, x+9*30+0.5, y+30+0.5));



    /*for (let i = 1; i < 10; i++){
        const borderComponent = new BorderComponent(myGameArea, x+i*(30+0.5), y);
        borderComponents.push(borderComponent);
    }
    for (let i = 1; i < 10; i++){
        const borderComponent = new BorderComponent(myGameArea, x, y+i*(30+0.5));
        borderComponents.push(borderComponent);
    }
    for (let i = 1; i < 10; i++){
        const borderComponent = new BorderComponent(myGameArea, x+i*(30+0.5), y+9*(30+0.5));
        borderComponents.push(borderComponent);
    }*/
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 500;
        this.canvas.height = 450;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
    }
}

function component(width, height, color, x, y) {
    this.width = width;
    this.height = height;
    this.x = x;
    this.y = y;
    //ctx = myGameArea.context;
    //ctx.fillStyle = color;
    //ctx.fillRect(this.x, this.y, this.width, this.height);
}

