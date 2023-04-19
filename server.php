<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/vendor/php/Player.php';

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
    // Timer every 30ms (30fps)
    Timer::add(1/50, function()use($ws_worker)
    {

        foreach($ws_worker->connections as $connection){
            foreach ($GLOBALS['players'] as $player){
                if ($player->getConnection() !== $connection){
                    $obj = new stdClass();
                    $obj->x = $player->getX();
                    $obj->y = $player->getY();
                    $connection->send(json_encode($obj));
                }
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

            array_push($GLOBALS['players'], new Player($connection));
            //array_push($GLOBALS['players'], new Player($connection->id));

            foreach ($GLOBALS['players'] as $player){
                echo $player->getConnection()->id."\n";
            }
            echo "\n";
            //echo "conn id: " . $connection->id . "\n";

        };


    };

    $ws_worker->onMessage = function($connection, $data)
    {
        $data = json_decode($data);
        for($i = 0; $i < count($GLOBALS['players']); $i++){
            if (($GLOBALS['players'][$i]->getConnection()) == $connection){
                $GLOBALS['players'][$i]->setX($data->x);
                $GLOBALS['players'][$i]->setY($data->y);
                break;
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
            if (($GLOBALS['players'][$i]->getConnection()->id) == $connection->id){
                array_splice($GLOBALS['players'], $i, 1);
                break;
            }
        }
    };
};
// Run worker
Worker::runAll();
?>