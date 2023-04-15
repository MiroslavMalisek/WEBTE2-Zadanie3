$(document).ready(function () {
    var ws = new WebSocket("wss://site152.webte.fei.stuba.sk:9000");
    ws.onopen = function () { log("Connection established"); };
    ws.onerror = function (error) { log("Unknown WebSocket Error " + JSON.stringify(error)); };
    ws.onmessage = function (e) {  var data = JSON.parse(e.data);
        console.log((data));
        log("< " + data.msg);
        document.getElementById("number").innerHTML = data.n_connections + "<br>";
    };
    ws.onclose = function () { log("Connection closed - Either the host or the client has lost connection"); }

    function log(m) {
        $("#log").append(m + "<br />");
    }

    function send() {
        $Msg = $("#msg");
        if ($Msg.val() == "") return alert("Textarea is empty");

        try {
            ws.send($Msg.val()); log('> Sent to server:' + $Msg.val());
        } catch (exception) {
            log(exception);
        }
        $Msg.val("");
    }

    $("#send").click(send);
    $("#msg").on("keydown", function (event) {
        if (event.keyCode == 13) send();
    });
    $("#quit").click(function () {
        log("Connection closed");
        ws.close(); ws = null;
    });
});