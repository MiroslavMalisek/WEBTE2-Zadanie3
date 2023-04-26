let ws;
let board;
let player;
let spectator = false;
let players_opponent = [];
let ball;

$(document).ready(function () {
    ws = new WebSocket("wss://site152.webte.fei.stuba.sk:9000");

    ws.onopen = function () {
        console.log("connected");
        //startGame();
    };
    ws.onerror = function (error) {
        console.log("Unknown WebSocket Error " + JSON.stringify(error));
    };
    ws.onmessage = function (e) {
        var data = JSON.parse(e.data);
        //connection message
        if (data.hasOwnProperty("first")){
            player = new ActivePlayer(data.yourPosition, '#4bc87f', data.first);
            firstPlayer();
        }
        if (data.hasOwnProperty('gameOn') && data.gameOn === "started"){
            document.getElementById('firstPlayerButtons').style.display = "none";
            document.getElementById('otherPlayerButtons').style.display = "none";
            data.opponents.forEach(opponent => {
                players_opponent.push(new Opponent(opponent.position, '#dc2f2f', opponent.name, opponent.lives));
            });
            startGame(data.bordersToAdd);
        }
        if (data.hasOwnProperty('spectator')){
            spectator = true;
            data.players.forEach(p => {
                players_opponent.push(new Opponent(p.position, '#dc2f2f', p.name, p.lives));
            });
            startGame(data.bordersToAdd);
        }
        if(data.hasOwnProperty('deletePosition')){
            if (!spectator) {
                if (player.getPosition() === data.deletePosition) {
                    eliminate();
                }
            }
            players_opponent = players_opponent.filter(player => player.getPosition() !== data.deletePosition);
            board.addBorder(myGameArea, data.deletePosition);
        }
        if (data.hasOwnProperty('numberPlayersLobby')){
            actualizeNumberPlayers(data.numberPlayersLobby);
        }
        if (data.hasOwnProperty('moveToFirst')){
            player.setFirst(true);
            changeToFirst();
        }
        if (data.hasOwnProperty('gameOn') && data.gameOn === "true"){
            updateGameArea(data);
        }
        if (data.hasOwnProperty('winner')){
            if(!spectator && (player.getPosition() === data.position)){
                winnerMe();
            }else {
                winnerPlayer(data.winner);
            }
        }
    };
    ws.onclose = function () {
        console.log("Connection closed - Either the host or the client has lost connection");
    }
});


function startGame(bordersToAdd) {
    myGameArea.start();
    board = new Board(myGameArea);
    bordersToAdd.forEach(border => {
        board.addBorder(myGameArea, border);
    });
    if (!spectator){
        player.createPlayer(myGameArea);
        sendPlayerPosition(player.getX(), player.getY(), player.getLivesX(), player.getLivesY());
    }
    players_opponent.forEach(p => {
        p.createPlayer(myGameArea);
    });

    ball = new Ball(myGameArea);
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 500;
        this.canvas.height = 500;
        this.componentSize = 40;
        this.gap = 0.5;
        this.startX = 50;
        this.startY = 50;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        //this.interval = setInterval(updateGameArea, 20);
        window.addEventListener('keydown', function (e) {
            myGameArea.key = e.key;
        })
        window.addEventListener('keyup', function (e) {
            myGameArea.key = false;
        })
    },
    clear : function(){
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

function updateGameArea(data){
    myGameArea.clear();
    if (!spectator){
        player.playerComponent.speedX = 0;
        player.playerComponent.speedY = 0;
        if (myGameArea.key && (player.getPosition() === "left") && myGameArea.key === "ArrowUp") {
            if (player.touchesLeftUpperBorder(board.getLeftBorderObstacles()[0])){
                player.playerComponent.speedY = 0;
            }else {
                player.playerComponent.speedY = -2;
            }
        }
        if (myGameArea.key && (player.getPosition() === "left") && myGameArea.key === "ArrowDown") {
            if (player.touchesLeftBottomBorder(board.getLeftBorderObstacles()[1])){
                player.playerComponent.speedY = 0;
            }else {
                player.playerComponent.speedY = 2;
            }
        }
        if (myGameArea.key && (player.getPosition() === "right") && myGameArea.key === "ArrowUp") {
            if (player.touchesRightUpperBorder(board.getRightBorderObstacles()[0])){
                player.playerComponent.speedY = 0;
            }else {
                player.playerComponent.speedY = -2;
            }
        }
        if (myGameArea.key && (player.getPosition() === "right") && myGameArea.key === "ArrowDown") {
            if (player.touchesRightBottomBorder(board.getRightBorderObstacles()[1])){
                player.playerComponent.speedY = 0;
            }else {
                player.playerComponent.speedY = 2;
            }
        }

        if (myGameArea.key && (player.getPosition() === "upper") && myGameArea.key === "ArrowLeft") {
            if (player.touchesUpperLeftBorder(board.getUpperBorderObstacles()[0])){
                player.playerComponent.speedX = 0;
            }else {
                player.playerComponent.speedX = -2;
            }
        }

        if (myGameArea.key && (player.getPosition() === "upper") && myGameArea.key === "ArrowRight") {
            if (player.touchesUpperRightBorder(board.getUpperBorderObstacles()[1])){
                player.playerComponent.speedX = 0;
            }else {
                player.playerComponent.speedX = 2;
            }
        }

        if (myGameArea.key && (player.getPosition() === "bottom") && myGameArea.key === "ArrowLeft") {
            if (player.touchesBottomLeftBorder(board.getBottomBorderObstacles()[0])){
                player.playerComponent.speedX = 0;
            }else {
                player.playerComponent.speedX = -2;
            }
        }

        if (myGameArea.key && (player.getPosition() === "bottom") && myGameArea.key === "ArrowRight") {
            if (player.touchesBottomRightBorder(board.getBottomBorderObstacles()[1])){
                player.playerComponent.speedX = 0;
            }else {
                player.playerComponent.speedX = 2;
            }
        }

        player.playerComponent.newPosition();
        sendPlayerPosition(player.getX(), player.getY(), player.getLivesX(), player.getLivesY());
        player.update(myGameArea, data.lives);
    }
    players_opponent.forEach(p => {
        let newOpp = data.opponent.find(opp => opp.position === p.getPosition());
        p.playerComponent.x = newOpp.x;
        p.playerComponent.y = newOpp.y;
        p.playerComponent.xLives = newOpp.xLives;
        p.playerComponent.yLives = newOpp.yLives;
        p.update(myGameArea, newOpp.lives);
    })

    board.updateBoard(myGameArea, data.bounces);
    ball.updateBall(myGameArea, data.ball.x, data.ball.y);

}

function sendPlayerPosition(x, y, xLives, yLives){
    try {
        ws.send(JSON.stringify({x: x, y: y, xLives: xLives, yLives: yLives}));
    }catch (e){
        console.log(e);
    }

}

function submitName(){
    var name = document.getElementById('inputName').value;
    player.setName(name);
    try {
        ws.send(JSON.stringify({name: name}));
    }catch (e) {
        console.log(e);
    }
    document.getElementById('form').style.display = "none";
    if (player.isFirst()){
        document.getElementById('firstPlayerButtons').style.display = "flex";
    }else {
        document.getElementById('otherPlayerButtons').style.display = "flex";
    }
}

function startGameButton(){
    try {
        ws.send(JSON.stringify({startButton: true}));
    }catch (e) {
        console.log(e);
    }
}

function leaveGame(){
    ws.close();
    document.getElementById('firstPlayerButtons').style.display = "none";
    document.getElementById('otherPlayerButtons').style.display = "none";
    document.getElementById('playerLeft').style.display = "flex";

}

function changeToFirst(){
    document.getElementById('otherPlayerButtons').style.display = "none";
    document.getElementById('firstPlayerButtons').style.display = "flex";
}

function firstPlayer(){
    document.getElementById('form').style.display = "flex";
}

function actualizeNumberPlayers(numberPlayersLobby){
    const collection = document.getElementsByClassName("numberPlayers");
    for (let i = 0; i < collection.length; i++) {
        collection[i].innerHTML = "Connected " + numberPlayersLobby + "/4 players";
    }
}

function eliminate(){
    let canvas = document.getElementsByTagName('canvas');
    canvas[0].style.display = "none";
    document.getElementById('playerLost').style.display = "flex";
    ws.close();
}

function winnerMe(){
    let canvas = document.getElementsByTagName('canvas');
    canvas[0].style.display = "none";
    document.getElementById('infoWinner').innerHTML = "You won!!!!"
    document.getElementById('winner').style.display = "flex";
}

function winnerPlayer(name){
    let canvas = document.getElementsByTagName('canvas');
    canvas[0].style.display = "none";
    document.getElementById('infoWinner').innerHTML = "Player " + name + " won!";
    document.getElementById('winner').style.display = "flex";
    ws.close();
}

