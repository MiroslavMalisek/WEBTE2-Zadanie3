class Board1Player extends Board{
    constructor(myGameArea) {
        super(myGameArea);

        //bottom border
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+i*(this.sizeComp+this.gap), this.y+9*(this.sizeComp+this.gap)));
        }
        //upper border
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+i*(this.sizeComp+this.gap), this.y));
        }
        //right border
        for (let i = 2; i < 8; i++){
            this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*(this.sizeComp+this.gap), this.y+i*(this.sizeComp+this.gap)));
        }

    }
    
}