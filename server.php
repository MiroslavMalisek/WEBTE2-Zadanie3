<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/vendor/php/Player.php';
include __DIR__ . '/vendor/php/Spectator.php';
include __DIR__ . '/vendor/php/Ball.php';
include __DIR__ . '/vendor/php/borders/BottomBorder.php';
include __DIR__ . '/vendor/php/borders/LeftBorder.php';
include __DIR__ . '/vendor/php/borders/RightBorder.php';
include __DIR__ . '/vendor/php/borders/UpperBorder.php';

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
    $GLOBALS['ball'] = null;
    $GLOBALS['spectators'] = [];
    $GLOBALS['positions'] = [new LeftBorder(), new UpperBorder(), new RightBorder(), new BottomBorder()];
    //$GLOBALS['positions'] = ["left", "right", "upper", "bottom"];
    $GLOBALS['gameOn'] = false;
    $GLOBALS['bounces'] = 0;
    // Timer every 50ms (50fps)
    Timer::add(1/50, function()use($ws_worker)
    {

        if ($GLOBALS['gameOn'] === true){
            checkCollisions();
            checkGoal();
            foreach ($GLOBALS['players'] as $player){
                $obj = new stdClass();
                $obj->gameOn = "true";
                $opponents = [];
                foreach ($GLOBALS['players'] as $p) {
                    if ($p !== $player){
                        $opp = new stdClass();
                        $opp->position = $p->getPosition()->getPosition();
                        $opp->x = $p->getX();
                        $opp->y = $p->getY();
                        $opp->lives = $p->getLives();
                        $opp->xLives = $p->getLivesX();
                        $opp->yLives = $p->getLivesY();
                        array_push($opponents, $opp);
                    }else{
                        $obj->lives = $p->getLives();
                    }
                }
                $obj->opponent = $opponents;
                $ball = new stdClass();
                $ball->x = $GLOBALS['ball']->getX();
                $ball->y = $GLOBALS['ball']->getY();
                $obj->ball = $ball;
                $obj->bounces = $GLOBALS['bounces'];
                $player->getConnection()->send(json_encode($obj));
            }
            foreach ($GLOBALS['spectators'] as $spectator){
                $obj = new stdClass();
                $obj->gameOn = "true";
                $opponents = [];
                foreach ($GLOBALS['players'] as $p) {
                    $opp = new stdClass();
                    $opp->position = $p->getPosition()->getPosition();
                    $opp->x = $p->getX();
                    $opp->y = $p->getY();
                    $opp->lives = $p->getLives();
                    $opp->xLives = $p->getLivesX();
                    $opp->yLives = $p->getLivesY();
                    array_push($opponents, $opp);
                }
                $obj->opponent = $opponents;
                $ball = new stdClass();
                $ball->x = $GLOBALS['ball']->getX();
                $ball->y = $GLOBALS['ball']->getY();
                $obj->ball = $ball;
                $obj->bounces = $GLOBALS['bounces'];
                $spectator->getConnection()->send(json_encode($obj));
            }

        }
    });

    //zrychlovanie lopticky
    Timer::add(2, function()use($ws_worker){
        if ($GLOBALS['gameOn'] === true){
            $GLOBALS['ball']->setSpeedX(($GLOBALS['ball']->getSpeedX() < 0) ? $GLOBALS['ball']->getSpeedX() - 0.5 : $GLOBALS['ball']->getSpeedX() + 0.1);
            $GLOBALS['ball']->setSpeedY(($GLOBALS['ball']->getSpeedY() < 0) ? $GLOBALS['ball']->getSpeedY() - 0.5 : $GLOBALS['ball']->getSpeedY() + 0.1);
            $GLOBALS['ball']->newPosition();
        }
    });


    // Emitted when new connection come
    $ws_worker->onConnect = function($connection)
    {

        // Emitted when websocket handshake done
        $connection->onWebSocketConnect = function($connection)
        {
            if(count($GLOBALS['players']) === 0) {
                //this is the first player that connected to game
                $player = createPlayer($connection, true);
                $obj = new stdClass();
                $obj->gameOn = $GLOBALS['gameOn'];
                $obj->first = $player->isFirst();
                $obj->yourPosition = $player->getPosition()->getPosition();
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
                    $opp->position = $player->getPosition()->getPosition();
                    $opp->name = $player->getName();
                    $opp->lives = $player->getLives();
                    array_push($ps, $opp);
                }
                $obj->players = $ps;
                $positions = [];
                foreach ($GLOBALS['positions'] as $p){
                    array_push($positions, $p->getPosition());
                }
                $obj->bordersToAdd = $positions;
                $connection->send(json_encode($obj));
            }else{
                $player = createPlayer($connection, false);
                $obj = new stdClass();
                $obj->gameOn = $GLOBALS['gameOn'];
                $obj->first = $player->isFirst();
                $obj->yourPosition = $player->getPosition()->getPosition();
                $connection->send(json_encode($obj));
                sendNoOfPlayersToAllPlayers();
            }
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
                $obj->deletePosition = $GLOBALS['players'][$i]->getPosition()->getPosition();
                array_push($GLOBALS['positions'], $GLOBALS['players'][$i]->getPosition());
                sendToAllConnections($connection, $obj);
                array_splice($GLOBALS['players'], $i, 1);
                if ($GLOBALS['gameOn'] === false) {
                    sendNoOfPlayersToAllPlayers();
                }
                break;
            }
        }
        if (count($GLOBALS['players']) === 0){
            restoreGame();
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
                $opp->position = $player->getPosition()->getPosition();
                $opp->name = $player->getName();
                $opp->lives = $player->getLives();
                array_push($opponents, $opp);
            }
        }
        $obj->opponents = $opponents;
        $positions = [];
        foreach ($GLOBALS['positions'] as $p){
            array_push($positions, $p->getPosition());
        }
        $obj->bordersToAdd = $positions;
        $connection->send(json_encode($obj));
    }
    $GLOBALS['gameOn'] = true;
    $GLOBALS['ball'] = new Ball();
}

function checkCollisions(){
    $GLOBALS['ball']->setSpeedX($GLOBALS['ball']->getSpeedX());
    $GLOBALS['ball']->setSpeedY($GLOBALS['ball']->getSpeedY());
    foreach ($GLOBALS['positions'] as $border){
        if ($border->ballCrashedBorder($GLOBALS['ball'])){
            $speeds = $border->ballSpeed($GLOBALS['ball']);
            $GLOBALS['ball']->setSpeedX($speeds[0]);
            $GLOBALS['ball']->setSpeedY($speeds[1]);
            $GLOBALS['bounces'] = $GLOBALS['bounces'] + 1;
            //break;
        }
    }
    foreach ($GLOBALS['players'] as $player){
        if ($player->getPosition()->ballCrashedCorner($GLOBALS['ball'])){
            $speeds = $player->getPosition()->ballSpeedCorner($GLOBALS['ball']);
            $GLOBALS['ball']->setSpeedX($speeds[0]);
            $GLOBALS['ball']->setSpeedY($speeds[1]);
            $GLOBALS['bounces'] = $GLOBALS['bounces'] + 1;
        }elseif ($player->crashedBall($GLOBALS['ball'])){
            $speeds = $player->ballSpeedPlayer($GLOBALS['ball']);
            $GLOBALS['ball']->setSpeedX($speeds[0]);
            $GLOBALS['ball']->setSpeedY($speeds[1]);
            $GLOBALS['bounces'] = $GLOBALS['bounces'] + 1;
        }
    }
    $GLOBALS['ball']->newPosition();
}

function checkGoal(){
    for ($i = 0; $i < count($GLOBALS['players']); $i++){
        $player = $GLOBALS['players'][$i];
        if ($player->getPosition()->isGoal($GLOBALS['ball'])){
            $player->setLives($player->getLives() - 1);
            if ($player->getLives() === 0){
                eliminatePlayer($player, $i);
            }else{
                $GLOBALS['ball']->setToCenter();
            }
            break;
        }
    }

}

function eliminatePlayer($player, $index){
    if (count($GLOBALS['players']) === 1){
        $obj = new stdClass();
        $obj->winner = $player->getName();
        $obj->position = $player->getPosition()->getPosition();
        sendToAllConnections($player->getConnection(), $obj);
        //$GLOBALS['spectators'][0]->getConnection()->send(json_encode($obj));
        restoreGame();
    }else{
        $obj = new stdClass();
        $obj->deletePosition = $player->getPosition()->getPosition();
        array_push($GLOBALS['positions'], $player->getPosition());
        sendToAllConnections($player->getConnection(), $obj);
        array_splice($GLOBALS['players'], $index, 1);
        $GLOBALS['ball']->setToCenter();
    }
}

function restoreGame(){
    $GLOBALS['gameOn'] = false;
    $GLOBALS['positions'] = [new LeftBorder(), new UpperBorder(), new RightBorder(), new BottomBorder()];
    foreach ($GLOBALS['players'] as $p){
        $p->getConnection()->close();
    }
    $GLOBALS['players'] = [];
    foreach ($GLOBALS['spectators'] as $spectator){
        $spectator->getConnection()->close();
    }
    $GLOBALS['spectators'] = [];
    $GLOBALS['ball'] = null;
    $GLOBALS['bounces'] = 0;
}
?>