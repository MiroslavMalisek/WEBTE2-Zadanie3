class ActivePlayer extends Player{
    constructor(position, color, first) {
        //console.log("active" + first);
        super(position, color, first);
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
