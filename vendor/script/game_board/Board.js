class Board{
    constructor(myGameArea) {
        this.borderComponents = [];
        //index 0 bude component vlavo hore, 1 bude vlavo dole
        this.leftBorderObstacles = [];
        //index 0 bude component vpravo hore, 1 bude vpravo dole
        this.rightBorderObstacles = [];
        //index 0 bude hore vlavo, 1 bude hore vpravo
        this.upperBorderObstacles = [];
        //index 0 bude dole vlavo, 1 bude dole vpravo
        this.bottomBorderObstacles = [];
        this.sizeComp = myGameArea.componentSize;
        this.gap = 0.5;
        //x start of border at canvas
        this.x = 50;
        //y start of border at canvas
        this.y = 50;
        //corners
        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y));
        let borderComp = new BorderComponent(myGameArea, this.x+this.sizeComp+this.gap, this.y);
        this.borderComponents.push(borderComp);
        this.upperBorderObstacles.push(borderComp);
        borderComp = new BorderComponent(myGameArea, this.x, this.y+this.sizeComp+this.gap);
        this.borderComponents.push(borderComp);
        this.leftBorderObstacles.push(borderComp);

        borderComp = new BorderComponent(myGameArea, this.x+8*(this.sizeComp+this.gap), this.y);
        this.borderComponents.push(borderComp);
        this.upperBorderObstacles.push(borderComp);
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y));
        borderComp = new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y+this.sizeComp+this.gap);
        this.borderComponents.push(borderComp);
        this.rightBorderObstacles.push(borderComp);

        borderComp = new BorderComponent(myGameArea, this.x, this.y+8*(this.sizeComp+this.gap));
        this.borderComponents.push(borderComp);
        this.leftBorderObstacles.push(borderComp);
        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y+9*(this.sizeComp+this.gap)));
        borderComp = new BorderComponent(myGameArea, this.x+this.sizeComp+this.gap, this.y+9*(this.sizeComp+this.gap));
        this.borderComponents.push(borderComp);
        this.bottomBorderObstacles.push(borderComp);

        borderComp = new BorderComponent(myGameArea, this.x+8*(this.sizeComp+this.gap), this.y+9*(this.sizeComp+this.gap));
        this.borderComponents.push(borderComp);
        this.bottomBorderObstacles.push(borderComp);
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y+9*(this.sizeComp+this.gap)));
        borderComp = new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y+8*(this.sizeComp+this.gap));
        this.borderComponents.push(borderComp);
        this.rightBorderObstacles.push(borderComp);

        //this.borderComponents.push(new BorderComponent(myGameArea, x+4*this.sizeComp+this.gap, y));
        //this.borderComponents.push(new BorderComponent(myGameArea, x+5*this.sizeComp+this.gap, y));
         
    }
    
    addBottomBorder(myGameArea){
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+i*(this.sizeComp+this.gap), this.y+9*(this.sizeComp+this.gap)));
        }
    }
    
    addUpperBorder(myGameArea){
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+i*(this.sizeComp+this.gap), this.y));
        }
    }
    
    addRightBorder(myGameArea){
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y+i*(this.sizeComp+this.gap)));
        }
    }
    
    getLeftBorderObstacles(){
        return this.leftBorderObstacles;
    }
    getRightBorderObstacles(){
        return this.rightBorderObstacles;
    }
    getUpperBorderObstacles(){
        return this.upperBorderObstacles;
    }
    getBottomBorderObstacles(){
        return this.bottomBorderObstacles;
    }
    
    updateBoard(myGameArea){
        this.borderComponents.forEach((component)=>{
            component.updateComponent(myGameArea);
        });

    }
}