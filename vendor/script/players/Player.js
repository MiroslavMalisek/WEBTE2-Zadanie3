class Player{
    constructor(position, color) {
        if (this.constructor === Player){
            throw new Error("Abstract class");
        }
        this.gap = null;
        this.compSize = null;
        //if player left vertical
        //this.playerComponent = new PlayerComponent(myGameArea, this.compSize, 80, 50, 50+4*(this.compSize+this.gap))
        this.playerComponent = null;
        this.position = position;
        this.color = color;
        this.first = false;
        this.name = null;
        this.lives = "3";
    }
    
    createPlayer(myGameArea){
        this.compSize = myGameArea.componentSize;
        this.gap = myGameArea.gap;
        let width;
        let height;
        let x;
        let y;
        let xLives;
        let yLives;
        switch (this.position){
            case "left":
                width = 10;
                height = 80;
                x = 50 + this.compSize - 10;
                y = 50+4*(this.compSize+this.gap);
                xLives = x - 20;
                yLives = y + 45;
                break;
            case "upper":
                width = 80;
                height = 10;
                x = 50+4*(this.compSize+this.gap);
                y = 50 + this.compSize - 10;
                xLives = x + 35;
                yLives = y - 10;
                break;
            case "right":
                width = 10;
                height = 80;
                x = 50+9*(this.compSize+this.gap);
                y = 50+4*(this.compSize+this.gap);
                xLives = x + 20;
                yLives = y + 45;
                break;
            case "bottom":
                width = 80;
                height = 10;
                x = 50+4*(this.compSize+this.gap);
                y = 50+9*(this.compSize+this.gap);
                xLives = x + 35;
                yLives = y + 35;
                break;
        }
        this.playerComponent = new PlayerComponent(myGameArea, this.lives, this.color, width, height, x, y, xLives, yLives);
    }
    
    setName(name){
        this.name = name;
    }
    getName(){
        return this.name;
    }

    setLives(lives){
        this.lives = lives;
    }
    getLives(){
        return this.lives;
    }
    
    isFirst(){
        return this.first;
    }
    setFirst(first){
        this.first = first;
    }
    
    getPosition(){
        return this.position;
    }

    update(myGameArea, lives){
        this.playerComponent.updateComponent(myGameArea, lives);
        this.lives = lives;
    }
    
    getX(){
        return this.playerComponent.x;
    }
    getY(){
        return this.playerComponent.y;
    }
    getLivesX(){
        return this.playerComponent.xLives;
    }
    getLivesY(){
        return this.playerComponent.yLives;
    }
}