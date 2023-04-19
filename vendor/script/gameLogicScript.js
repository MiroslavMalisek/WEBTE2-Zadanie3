let ws;
let board;
let player;
let playerPos;
let players_opponent = [];

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
        if (data.hasOwnProperty('running')){
            console.log("Game is already running");
        }else if (data.hasOwnProperty('gameOn') && data.gameOn === "started"){
            //console.log(data);
            data.opponent.forEach(pos => {
                players_opponent.push(new Opponent(pos, '#dc2f2f'));
            });
            startGame();
        }else if(data.hasOwnProperty('deletePosition')){

        }else if (data.hasOwnProperty('gameOn') && data.gameOn === "true"){
            //console.log(data);
            updateGameArea(data.opponent);
        }else if (data.hasOwnProperty('yourPosition')){
            player = new ActivePlayer(data.yourPosition, '#4bc87f');
        }
        //log("< " + data.msg);
        /*document.getElementById("number").innerHTML = data.n_connections + "<br>";*/

    };
    ws.onclose = function () {
        console.log("Connection closed - Either the host or the client has lost connection");
    }


});


function startGame() {
    myGameArea.start();
    board = new Board(myGameArea);
    //console.log(myGameArea);
    player.createPlayer(myGameArea);
    sendPlayerPosition(player.getX(), player.getY())
    players_opponent.forEach(p => {
        p.createPlayer(myGameArea);
    });
    //console.log(players_opponent);
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 500;
        this.canvas.height = 500;
        this.componentSize = 40;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        //this.interval = setInterval(updateGameArea, 20);
        window.addEventListener('keydown', function (e) {
            myGameArea.key = e.key;
        })
        window.addEventListener('keyup', function (e) {
            myGameArea.key = false;
        })
        /*this.canvas.addEventListener('click', function (){
            board.addUpperBorder(myGameArea);
        })*/
    },
    clear : function(){
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

function updateGameArea(opponents){
    myGameArea.clear();
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
    sendPlayerPosition(player.getX(), player.getY())
    player.update(myGameArea);
    players_opponent.forEach(p => {
        let newOpp = opponents.find(opp => opp.position === p.getPosition());
        p.playerComponent.x = newOpp.x;
        p.playerComponent.y = newOpp.y;
        p.update(myGameArea);
    })
    //player_opponent.playerComponent.x = opponentX;
    //player_opponent.playerComponent.y = opponentY;
    //player_opponent.update(myGameArea);
    board.updateBoard(myGameArea);

}

function sendPlayerPosition(x, y){
    try {
        ws.send(JSON.stringify({x: x, y: y}));
    }catch (e){
        console.log(e);
    }

}

function log(m) {
    $("#log").append(m + "<br />");
}

function send() {
    /*$Msg = $("#msg").val();
    if ($Msg === "") return alert("Textarea is empty");*/

    try {
        ws.send(JSON.stringify(myGameArea.context.getImageData(0, 0, myGameArea.canvas.width, myGameArea.canvas.height).data));
        //log('> Sent to server:' + $Msg);
    } catch (exception) {
        console.log(exception);
    }
    //$Msg.val("");
}

$("#send").click(send);
$("#msg").on("keydown", function (event) {
    console.log(event.keyCode);
    if (event.keyCode == 13) send();
});
$("#quit").click(function () {
    log("Connection closed");
    ws.close(); ws = null;
});


