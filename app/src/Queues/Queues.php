<?php
namespace Malmanger\Mpmq\Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

use Malmanger\Mpmq\Db\DbQueue as DbQueue;

class Queues {
//    private $log = \Malmanger\Mpmq\Util\Log::getInstance(\Malmanger\Mpmq\Util\Log::DEBUG);

    protected $log = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
    }
    
    public function listQueues(Request $request, Response $response, array $args)
    {
        return "listQueues <br>\n" . var_dump($response, true);
     }  

    public function getInformation(Request $request, Response $response, array $args)
    {
        $id = intval($args["id"]);
        $queue = new DbQueue((int)$id);
        return json_encode($queue->toArray()); //"getInformation <br>\n" . var_dump($args, true);
     }  
    // Needs to be a POST request
    public function newQueue(Request $request, Response $response, array $args)
    {
        $err = new \Malmanger\Mpmq\Util\ErrorHandler();

        $data = $request->getParsedBody();
        $this->log->debug("newQueue data=".print_r($data, true));

        // TODO: check with array_key_exists...
        $key = "id";
        if (!array_key_exists($key, $data)) {  
            $err->addError("Missing value for required input '" . $key . "'", $err->CODE_DATA_MISSING);
        }
        $key = "name";
        if (!array_key_exists($key, $data)) {  
            $err->addError("Missing value for required input '" . $key . "'", $err->CODE_DATA_MISSING);
        }
        $key = "xxx";
        $err->addError("Missing value for required input '" . $key . "'", $err->CODE_DATA_MISSING);
        $key = "yyy";
        $err->addError("Missing value for required input '" . $key . "'", $err->CODE_DATA_MISSING);

        $this->log->debug("newQueue err=" . print_r(json_encode($err->getError())));
        $queue = new DbQueue($data["id"], $data["name"], $data["description"], $data["timeout"]);
        // TODO: Fix it...  $id = 
        return "New Queue <br>\n" . var_dump($queue, true);
     }  
    public function updateQueue(Request $request, Response $response, array $args)
    {
        return "Update Queue <br>\n" . var_dump($args, true);
     }  
    public function deleteQueue(Request $request, Response $response, array $args)
    {
//        $stream = $response->getBody();
        // $stream->write(json_encode([
        //     'status' => 204,
        //     'detail' => 'Queue deleted',
        // ]));

//$stream->write("Hello World");
//        $response = $response->withBody($stream);

        // An Response with status 204 should be empty
        $response = $response->withStatus(204, 'Queue deleted');

        return $response;
     }  
}
