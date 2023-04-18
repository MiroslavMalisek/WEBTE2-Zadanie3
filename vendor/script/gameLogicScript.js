let ws;

$(document).ready(function () {
    ws = new WebSocket("wss://site152.webte.fei.stuba.sk:9000");

    ws.onopen = function () { log("Connection established"); };
    ws.onerror = function (error) { log("Unknown WebSocket Error " + JSON.stringify(error)); };
    ws.onmessage = function (e) {
        var data = JSON.parse(e.data);
        console.log((data));
        //log("< " + data.msg);
        /*document.getElementById("number").innerHTML = data.n_connections + "<br>";*/

    };
    ws.onclose = function () { log("Connection closed - Either the host or the client has lost connection"); }


});

let board;
let player;
let player_opponent;

function startGame() {
    myGameArea.start();
    board = new Board(myGameArea);
    //console.log(myGameArea);
    player = new ActivePlayer(myGameArea, "bottom");
    player_opponent = new Opponent(myGameArea, "left");
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 500;
        this.canvas.height = 500;
        this.componentSize = 40;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.interval = setInterval(updateGameArea, 20);
        window.addEventListener('keydown', function (e) {
            myGameArea.key = e.key;
        })
        window.addEventListener('keyup', function (e) {
            myGameArea.key = false;
        })
        this.canvas.addEventListener('click', function (){
            board.addBottomBorder(myGameArea);
        })
    },
    clear : function(){
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

function updateGameArea(){
    myGameArea.clear();
    player.playerComponent.speedX = 0;
    player.playerComponent.speedY = 0;
    if (myGameArea.key && ((player.getPosition() === "left") || (player.getPosition() === "right")) && myGameArea.key === "ArrowUp") {
        player.playerComponent.speedY = -2;
    }
    if (myGameArea.key && ((player.getPosition() === "left") || (player.getPosition() === "right")) && myGameArea.key === "ArrowDown") {
        player.playerComponent.speedY = 2;
    }
    if (myGameArea.key && ((player.getPosition() === "upper") || (player.getPosition() === "bottom")) && myGameArea.key === "ArrowLeft") {
        player.playerComponent.speedX = -2;
    }
    if (myGameArea.key && ((player.getPosition() === "upper") || (player.getPosition() === "bottom")) && myGameArea.key === "ArrowRight") {
        player.playerComponent.speedX = 2;
    }
    player.playerComponent.newPosition();
    player.update(myGameArea);
    player_opponent.update(myGameArea);
    board.updateBoard(myGameArea);

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


