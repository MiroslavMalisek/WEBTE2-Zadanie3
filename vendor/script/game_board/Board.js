class Board{
    constructor(myGameArea) {
        this.borderComponents = [];
        this.leftBorderObstacles = [];
        this.rightBorderObstacles = [];
        this.upperBorderObstacles = [];
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
    
    updateBoard(myGameArea){
        this.borderComponents.forEach((component)=>{
            component.updateComponent(myGameArea);
        });

    }
}