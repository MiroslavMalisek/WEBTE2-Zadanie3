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


function startGame() {
    myGameArea.start();
    let board = new Board3Players(myGameArea);
    //console.log(myGameArea);
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 500;
        this.canvas.height = 500;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.canvas.addEventListener('click', function (){
            console.log(myGameArea.context);
        })
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


