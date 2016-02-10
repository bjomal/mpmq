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

        // Check for mandatory parameters and set defaults
        $timeout = null;
        $description = '';

        $key = "id";
        if (!array_key_exists($key, $data)) {  
            $err->addMissing($key);
        }
        $key = "name";
        if (!array_key_exists($key, $data)) {  
            $err->addMissing($key);
        }
        $key = "description";
        if (array_key_exists($key, $data)) {  
           $description = $data[$key];
        }
        $key = "timeout";
        if (array_key_exists($key, $data)) {  
            $timeout = $data[$key];
            // TODO: Set default if required...
        }
//        $err->addMissing("xxx");
//        $err->addError("Something really bad went wrong here", $err->getConstant('CODE_FATAL'));
//        $err->addMissing("yyy");

        if ($err->getLevel() > 0) {
            $this->log->debug("newQueue err=" . print_r(json_encode($err->getError())));
            return $err->getErrorResponse($response);
        } else {

            $queue = new DbQueue($data["id"], $data["name"], $description, $timeout);


            // TODO: Fix it...  $id = 

            $resp = new \Malmanger\Mpmq\Util\ResponseHandler();

            return "XXNew Queue <br>\n" . var_export($queue, true);
        }

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
