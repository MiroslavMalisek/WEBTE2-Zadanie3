class Player{
    constructor(myGameArea, position) {
        if (this.constructor === Player){
            throw new Error("Abstract class");
        }
        this.gap = 0.5;
        this.compSize = myGameArea.componentSize;
        //if player left vertical
        //this.playerComponent = new PlayerComponent(myGameArea, this.compSize, 80, 50, 50+4*(this.compSize+this.gap))
        this.playerComponent = null;
        this.position = position;
    }
    
    getPosition(){
        return this.position;
    }

    update(myGameArea){
        this.playerComponent.updateComponent(myGameArea);
    }
}