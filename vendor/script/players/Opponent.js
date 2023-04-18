class Opponent extends Player{
    constructor(myGameArea, position) {
        super(myGameArea, position);
        let width;
        let height;
        let x;
        let y;
        switch (this.position){
            case "left":
                width = this.compSize;
                height = 80;
                x = 50;
                y = 50+4*(this.compSize+this.gap);
                break;
            case "upper":
                width = 80;
                height = this.compSize;
                x = 50+4*(this.compSize+this.gap);
                y = 50;
                break;
            case "right":
                width = this.compSize;
                height = 80;
                x = 50+9*(this.compSize+this.gap);
                y = 50+4*(this.compSize+this.gap);
                break;
            case "bottom":
                width = 80;
                height = this.compSize;
                x = 50+4*(this.compSize+this.gap);
                y = 50+9*(this.compSize+this.gap);
                break;
        }
        this.playerComponent = new PlayerComponent(myGameArea, '#dc2f2f', width, height, x, y);

    }
}