class Board{
    constructor(myGameArea) {
         if (this.constructor === Board){
             throw new Error("Abstract class");
         }
        this.borderComponents = [];
         this.sizeComp = 40;
         this.gap = 0.5;
        //x start of border at canvas
        this.x = 50;
        //y start of border at canvas
        this.y = 50;
        //corners
        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+this.sizeComp+this.gap, this.y));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y+this.sizeComp+this.gap));

        this.borderComponents.push(new BorderComponent(myGameArea, this.x+8*this.sizeComp+this.gap, this.y));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*this.sizeComp+this.gap, this.y));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*this.sizeComp+this.gap, this.y+this.sizeComp+this.gap));

        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y+8*this.sizeComp+this.gap));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x, this.y+9*this.sizeComp+this.gap));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+this.sizeComp+this.gap, this.y+9*this.sizeComp+this.gap));

        this.borderComponents.push(new BorderComponent(myGameArea, this.x+8*this.sizeComp+this.gap, this.y+9*this.sizeComp+this.gap));
        this.borderComponents.push(new BorderComponent(myGameArea, this.x+9*this.sizeComp+this.gap, this.y+9*this.sizeComp+this.gap));
        this. borderComponents.push(new BorderComponent(myGameArea, this.x+9*this.sizeComp+this.gap, this.y+8*this.sizeComp+this.gap));
        
        //this.borderComponents.push(new BorderComponent(myGameArea, x+4*this.sizeComp+this.gap, y));
        //this.borderComponents.push(new BorderComponent(myGameArea, x+5*this.sizeComp+this.gap, y));
         
    }
}