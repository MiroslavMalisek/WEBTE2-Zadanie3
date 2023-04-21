<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/vendor/php/Player.php';
include __DIR__ . '/vendor/php/Spectator.php';

$context = [
    'ssl' => [
        'local_cert'  => '/home/xmalisek/webte_fei_stuba_sk.pem',
        'local_pk'    => '/home/xmalisek/webte.fei.stuba.sk.key',
        'verify_peer' => false,
    ]
];

// Create A Worker and Listens 9000 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:9000", $context);
$ws_worker->transport = 'ssl';
$ws_worker->count = 1;

// Add a Timer to Every worker process when the worker process start
$ws_worker->onWorkerStart = function($ws_worker)
{
    $GLOBALS['players'] = [];
    $GLOBALS['spectators'] = [];
    $GLOBALS['positions'] = ["left", "right", "upper", "bottom"];
    //lobby, start, playing
    $GLOBALS['gameOn'] = false;
    // Timer every 30ms (30fps)
    Timer::add(1/50, function()use($ws_worker)
    {

        if ($GLOBALS['gameOn'] === true){
            /*foreach($ws_worker->connections as $connection){
                $obj = new stdClass();
                $obj->gameOn = "true";
                $opponents = [];
                foreach ($GLOBALS['players'] as $player) {
                    if ($player->getConnection() !== $connection) {
                        $opp = new stdClass();
                        $opp->position = $player->getPosition();
                        $opp->x = $player->getX();
                        $opp->y = $player->getY();
                        array_push($opponents, $opp);
                    }
                }
                $obj->opponent = $opponents;
                $connection->send(json_encode($obj));
            }*/
            foreach ($GLOBALS['players'] as $player){
                $obj = new stdClass();
                $obj->gameOn = "true";
                $opponents = [];
                foreach ($GLOBALS['players'] as $p) {
                    if ($p !== $player){
                        $opp = new stdClass();
                        $opp->position = $p->getPosition();
                        $opp->x = $p->getX();
                        $opp->y = $p->getY();
                        $opp->xLives = $p->getLivesX();
                        $opp->yLives = $p->getLivesY();
                        array_push($opponents, $opp);
                    }
                }
                $obj->opponent = $opponents;
                $player->getConnection()->send(json_encode($obj));
            }

            foreach ($GLOBALS['spectators'] as $spectator){
                $obj = new stdClass();
                $obj->gameOn = "true";
                $opponents = [];
                foreach ($GLOBALS['players'] as $p) {
                    $opp = new stdClass();
                    $opp->position = $p->getPosition();
                    $opp->x = $p->getX();
                    $opp->y = $p->getY();
                    $opp->xLives = $p->getLivesX();
                    $opp->yLives = $p->getLivesY();
                    array_push($opponents, $opp);
                }
                $obj->opponent = $opponents;
                $spectator->getConnection()->send(json_encode($obj));
            }

        }

        /*foreach ($GLOBALS['players'] as $player){

            echo "x: " .$player->getX()."\n";
            echo "y: " .$player->getY()."\n";
        }*/



        /*echo "x: " . $GLOBALS['players'][0]->getX() . "\n";
        echo "y: " . $GLOBALS['players'][0]->getY() . "\n";*/
        //echo "\n";
        //$n_conn = count($ws_worker->connections);
        // Iterate over connections and send the time
        /*foreach($ws_worker->connections as $connection)
        {
            $connection->send(generateNumberConnectionsJsonMessage($n_conn));
        }*/
    });


    // Emitted when new connection come
    $ws_worker->onConnect = function($connection)
    {

        // Emitted when websocket handshake done
        $connection->onWebSocketConnect = function($connection)
        {
            //echo "New connection\n";

            //echo count($connection->worker->connections);
            /*foreach($connection->worker->connections as $connection)
            {
                $connection->send(generateNumberConnectionsJsonMessage(count($connection->worker->connections)));
            }*/

            if(count($GLOBALS['players']) === 0) {
                //this is the first player that connected to game
                $player = createPlayer($connection, true);
                $obj = new stdClass();
                $obj->gameOn = $GLOBALS['gameOn'];
                $obj->first = $player->isFirst();
                $obj->yourPosition = $player->getPosition();
                $connection->send(json_encode($obj));
                sendNoOfPlayersToAllPlayers();

            } elseif((count($GLOBALS['players']) >= 4) || $GLOBALS['gameOn'] === true){
                $spectator = new Spectator($connection);
                array_push($GLOBALS['spectators'], $spectator);
                $obj = new stdClass();
                $obj->spectator = true;
                $ps = [];
                foreach ($GLOBALS['players'] as $player){
                    $opp = new stdClass();
                    $opp->position = $player->getPosition();
                    $opp->name = $player->getName();
                    $opp->lives = $player->getLives();
                    array_push($ps, $opp);
                }
                $obj->players = $ps;
                $obj->bordersToAdd = $GLOBALS['positions'];
                $connection->send(json_encode($obj));
            }else{
                $player = createPlayer($connection, false);
                $obj = new stdClass();
                $obj->gameOn = $GLOBALS['gameOn'];
                $obj->first = $player->isFirst();
                $obj->yourPosition = $player->getPosition();
                $connection->send(json_encode($obj));
                sendNoOfPlayersToAllPlayers();
            }

            /*if (count($GLOBALS['players']) > 4){
                $obj = new stdClass();
                $obj->running = "true";
                $connection->send(json_encode($obj));
            }*/

        };


    };

    $ws_worker->onMessage = function($connection, $data)
    {
        $data = json_decode($data);
        //saving name
        if (property_exists($data, 'name')){
            for($i = 0; $i < count($GLOBALS['players']); $i++){
                if (($GLOBALS['players'][$i]->getConnection()) == $connection){
                    $GLOBALS['players'][$i]->setName($data->name);
                    break;
                }
            }
            if (count($GLOBALS['players']) === 4){
                startGame($connection);
            }
        }elseif (property_exists($data, 'startButton')) {
            startGame($connection);
        }else{
            for($i = 0; $i < count($GLOBALS['players']); $i++){
                if (($GLOBALS['players'][$i]->getConnection()) == $connection){
                    $GLOBALS['players'][$i]->setX($data->x);
                    $GLOBALS['players'][$i]->setY($data->y);
                    $GLOBALS['players'][$i]->setLivesX($data->xLives);
                    $GLOBALS['players'][$i]->setLivesY($data->yLives);
                    break;
                }
            }
        }



        //$GLOBALS['userdata']=$data;

        //sendMgsToAll($connection, $data);
        // Send hello $data
        //$connection->send(generateNumberConnectionsJsonMessage(count($connection->worker->connections)));
    };

    // Emitted when connection closed
    $ws_worker->onClose = function($connection)
    {
        for($i = 0; $i < count($GLOBALS['players']); $i++){
            if (($GLOBALS['players'][$i]->getConnection()) === $connection) {
                if (($i === 0) && (count($GLOBALS['players']) > 1) &&($GLOBALS['gameOn'] === false)) {
                    $obj = new stdClass();
                    $obj->moveToFirst = true;
                    $GLOBALS['players'][1]->setFirst(true);
                    $GLOBALS['players'][1]->getConnection()->send(json_encode($obj));
                }
                $obj = new stdClass();
                $obj->deletePosition = $GLOBALS['players'][$i]->getPosition();
                array_push($GLOBALS['positions'], $GLOBALS['players'][$i]->getPosition());
                sendToAllConnections($connection, $obj);
                array_splice($GLOBALS['players'], $i, 1);
                if ($GLOBALS['gameOn'] === false) {
                    sendNoOfPlayersToAllPlayers();
                }
                //$connection->send(json_encode($obj));
                break;
            }
        }

        if (count($GLOBALS['players']) === 0){
            $GLOBALS['gameOn'] = false;
            $GLOBALS['positions'] = ["left", "right", "upper", "bottom"];
            $GLOBALS['players'] = [];
            foreach ($GLOBALS['spectators'] as $spectator){
                $spectator->getConnection()->close();
            }
            $GLOBALS['spectators'] = [];
        }
    };
};
// Run worker
Worker::runAll();

function sendToAllConnections($connection, $obj){
    foreach ($connection->worker->connections as $connection) {
        $connection->send(json_encode($obj));
    }
}

function sendToAllPlayers($obj){
    foreach ($GLOBALS['players'] as $player) {
        $player->getConnection()->send(json_encode($obj));
    }
}

function sendNoOfPlayersToAllPlayers(){
    $obj = new stdClass();
    $obj->numberPlayersLobby = count($GLOBALS['players']);
    sendToAllPlayers($obj);
}

function createPlayer($connection, $first){
    $pos = rand(0, count($GLOBALS['positions'])-1);
    $player = new Player($connection, $GLOBALS['positions'][$pos], $first);
    array_push($GLOBALS['players'], $player);
    array_splice($GLOBALS['positions'], $pos, 1);
    return $player;
}

function startGame($connection){
    foreach($connection->worker->connections as $connection){
        $obj = new stdClass();
        $obj->gameOn = "started";
        $opponents = [];
        foreach ($GLOBALS['players'] as $player){
            if ($player->getConnection() !== $connection){
                $opp = new stdClass();
                $opp->position = $player->getPosition();
                $opp->name = $player->getName();
                $opp->lives = $player->getLives();
                array_push($opponents, $opp);
            }
        }
        $obj->opponents = $opponents;
        $obj->bordersToAdd = $GLOBALS['positions'];
        $connection->send(json_encode($obj));
    }
    $GLOBALS['gameOn'] = true;
}
?>