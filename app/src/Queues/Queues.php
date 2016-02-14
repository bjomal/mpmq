<?php
namespace Malmanger\Mpmq\Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

use Malmanger\Mpmq\Db\DbQueue as DbQueue;
use Malmanger\Mpmq\Db\DbTools as DbTools;

class Queues {
//    private $log = \Malmanger\Mpmq\Util\Log::getInstance(\Malmanger\Mpmq\Util\Log::DEBUG);

    protected $log = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
    }
    
    public function listQueues(Request $request, Response $response, array $args)
    {
        global $database;
        $dbTools = DbTools::getInstance($database);

        $resp = new \Malmanger\Mpmq\Util\ResponseHandler($dbTools->getQueues());
        return $resp->getResponse($response);
     }  

    public function getInformation(Request $request, Response $response, array $args)
    {
        $id = intval($args["id"]);
        $queue = new DbQueue($id);
        return json_encode($queue->toArray()); //"getInformation <br>\n" . var_dump($args, true);
     }  
    // Needs to be a POST request
    public function newQueue(Request $request, Response $response, array $args)
    {
        global $database;
        $err = new \Malmanger\Mpmq\Util\ErrorHandler();

        $data = $request->getParsedBody();
        $this->log->debug("newQueue data=".print_r($data, true));

        // Check for mandatory parameters and set defaults
        $id = null;
        $name = null;
        $timeout = null;
        $description = '';

        $key = "id";
        if (!array_key_exists($key, $data)) {  
            $err->addMissing($key);
        } else {
            $id = $data["id"];
        }
        $key = "name";
        if (!array_key_exists($key, $data)) {  
            $err->addMissing($key);
        } else {
            $name = $data["name"];
        }
        $key = "description";
        if (array_key_exists($key, $data)) {  
           $description = $data[$key];
        }
        $key = "timeout";
        if (array_key_exists($key, $data)) {  
            $timeout = $data[$key];
        }

        $queue = new DbQueue($id, $name, $description, $timeout);
        if ($queue->queueExists()) {
            $err->addExists($id);
        }

        if ($err->getLevel() > 0) {
            return $err->getErrorResponse($response);
        } 

        if (!$queue->save()) {
            $err->addDbUpdate("newQueue");
        }

        if ($err->getLevel() > 0) {
            return $err->getErrorResponse($response);
        } else {
            $data = array();
            $data['id'] = $queue->getId();
            $data['name'] = $queue->getName();
            $data['description'] = $queue->getDescription();
            $data['timeout'] = $queue->getTimeout();

            $resp = new \Malmanger\Mpmq\Util\ResponseHandler($data);
            
            return $resp->getResponse($response);
        }

     }  
    public function updateQueue(Request $request, Response $response, array $args)
    {
        return "Update Queue <br>\n" . var_dump($args, true);
     }  
    public function deleteQueue(Request $request, Response $response, array $args)
    {
        global $database;
        $err = new \Malmanger\Mpmq\Util\ErrorHandler();

        $data = $request->getParsedBody();
        $this->log->debug("deleteQueue args=".print_r($args, true));

        $queue = new DbQueue($args['id']);
        if (!$queue->queueExists()) {
            $err->addNotFound($args['id']);
        }

        if (!$queue->delete()) {
            $err->addDbUpdate("deleteQueue");
        }

        if ($err->getLevel() > 0) {
            return $err->getErrorResponse($response);
        } else {
            $resp = new \Malmanger\Mpmq\Util\ResponseHandler();
            
            return $resp->setStatus(204, 'Queue deleted')->getResponse($response);
        }
     }  
}
