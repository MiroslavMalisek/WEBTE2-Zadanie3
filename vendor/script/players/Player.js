class Player{
    constructor(position, color, first) {
        if (this.constructor === Player){
            throw new Error("Abstract class");
        }
        this.gap = 0.5;
        this.compSize = null;
        //if player left vertical
        //this.playerComponent = new PlayerComponent(myGameArea, this.compSize, 80, 50, 50+4*(this.compSize+this.gap))
        this.playerComponent = null;
        this.position = position;
        this.color = color;
        this.first = first;
        this.name = null;
    }
    
    createPlayer(myGameArea){
        this.compSize = myGameArea.componentSize;
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
        this.playerComponent = new PlayerComponent(myGameArea, this.color, width, height, x, y);
    }
    
    setName(name){
        this.name = name;
    }
    getName(){
        return this.name;
    }
    
    isFirst(){
        return this.first;
    }
    
    getPosition(){
        return this.position;
    }

    update(myGameArea){
        this.playerComponent.updateComponent(myGameArea);
    }
    
    getX(){
        return this.playerComponent.x;
    }
    getY(){
        return this.playerComponent.y;
    }
}