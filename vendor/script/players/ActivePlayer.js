class ActivePlayer extends Player{
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
        this.playerComponent = new PlayerComponent(myGameArea, '#4bc87f', width, height, x, y);
    }

    touchesLeftUpperBorder(leftUpperBorder){
        var myTop = this.playerComponent.y;
        var borderBottom = leftUpperBorder.y + leftUpperBorder.height;
        if (myTop < borderBottom){
            return true;
        }
        return false;
    }

    touchesLeftBottomBorder(leftBottomBorder){
        var myBottom = this.playerComponent.y + this.playerComponent.height;
        var borderTop = leftBottomBorder.y;
        if (myBottom > borderTop){
            return true;
        }
        return false;
    }

    touchesRightUpperBorder(rightUpperBorder){
        var myTop = this.playerComponent.y;
        var borderBottom = rightUpperBorder.y + rightUpperBorder.height;
        if (myTop < borderBottom){
            return true;
        }
        return false;
    }
    
    touchesRightBottomBorder(rightBottomBorder){
        var myBottom = this.playerComponent.y + this.playerComponent.height;
        var borderTop = rightBottomBorder.y;
        if (myBottom > borderTop){
            return true;
        }
        return false;
    }

    touchesUpperLeftBorder(upperLeftBorder){
        var myLeft = this.playerComponent.x;
        var borderLeft = upperLeftBorder.x + upperLeftBorder.width;
        if (myLeft < borderLeft){
            return true;
        }
        return false;
    }

    touchesUpperRightBorder(upperRightBorder){
        var myRight = this.playerComponent.x + this.playerComponent.width;
        var borderRight = upperRightBorder.x;
        if (myRight > borderRight){
            return true;
        }
        return false;
    }

    touchesBottomLeftBorder(bottomLeftBorder){
        var myLeft = this.playerComponent.x;
        var borderLeft = bottomLeftBorder.x + bottomLeftBorder.width;
        if (myLeft < borderLeft){
            return true;
        }
        return false;
    }

    touchesBottomRightBorder(bottomRightBorder){
        var myRight = this.playerComponent.x + this.playerComponent.width;
        var borderRight = bottomRightBorder.x;
        if (myRight > borderRight){
            return true;
        }
        return false;
    }
    
}
