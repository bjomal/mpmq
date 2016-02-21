<?php
namespace Malmanger\Mpmq\Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

use Malmanger\Mpmq\Db\DbMessage as DbMessage;
use Malmanger\Mpmq\Db\DbTools as DbTools;
use Malmanger\Mpmq\Db\DbQueue as DbQueue;

class Queue {
    protected $log = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
    }

    /**
     * Get messages in queue
     *
     * @param Psr\Http\Message\RequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response 
     * @param array $args
     * 
     * @return Psr\Http\Message\ResponseInterface
     */
    public function listMessages(Request $request, Response $response, array $args) {
        global $database;
        $err = new \Malmanger\Mpmq\Util\ErrorHandler();
        $id = $args['id'];

        $dbTools = \Malmanger\Mpmq\Db\DbTools::getInstance($database);
        $messages = $dbTools->getMessages($id);
        if (empty($messages)) {
            $err->addNotFound("messages for queue=".$id);
            return $err->getErrorResponse($response);
        }
        $resp = new \Malmanger\Mpmq\Util\ResponseHandler($dbTools->getMessages($id));
        return $resp->getResponse($response);

    }	
    // public function listMessages() {
    //     return var_dump($this, true);
    // }   
    public function listInFlightMessages(Request $request, Response $response, array $args) {
        return "InFlight messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }   
    public function getMessage(Request $request, Response $response, array $args) {
        return "Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Get and release message from queue - To be done up on completion
    public function releaseMessage(Request $request, Response $response, array $args) {
        return "Release message back to queue<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    /**
     * Add new message to queue
     *
     * @param Psr\Http\Message\RequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response 
     * @param array $args
     * 
     * @return Psr\Http\Message\ResponseInterface
     */
    public function newMessage(Request $request, Response $response, array $args) {
        global $database;
        $err = new \Malmanger\Mpmq\Util\ErrorHandler();

        $data = $request->getParsedBody();
        $this->log->debug("newMessage data=".print_r($data, true));

        // Check for mandatory parameters and set defaults
        $id = $args['id'];
        $queue = new DbQueue($id);
        if (!$queue->queueExists()) {
            $err->addNotFound("queue with id=".$id);
        }
        $messageId = null;
        $title = null;
        $payload = null;
        $timeout = null;

//        $key = "id";
//        if (!array_key_exists($key, $data)) {  
//            $err->addMissing($key);
//        } else {
//            $id = $data["id"];
//        }
        $key = "title";
        if (!array_key_exists($key, $data)) {  
            $err->addMissing($key);
        } else {
            $title = $data[$key];
        }
        $key = "payload";
        if (array_key_exists($key, $data)) {  
           $payload = $data[$key];
        }
        $key = "timeout";
        if (array_key_exists($key, $data)) {  
            $timeout = $data[$key];
        } else {
            $timeout = $queue->getTimeout();
        }

        $message = new DbMessage($id, $messageId, $title, $payload, $timeout);
//        if ($queue->queueExists()) {
//            $err->addExists($id);
//        }

        if ($err->getLevel() > 0) {
            return $err->getErrorResponse($response);
        } 

        $messageId = $message->save();
        if (!$messageId) {
            $err->addDbUpdate("newMessage");
        }

        if ($err->getLevel() > 0) {
            return $err->getErrorResponse($response);
        } else {
            $data = array();
            $data['id'] = $message->getQueueId();
            $data['messageId'] = $message->getMessageId();
            $data['title'] = $message->getTitle();
            $data['payload'] = $message->getPayload();
            $data['timeout'] = $message->getTimeout();
            $data['inFlight'] = $message->getInFlight();

            $resp = new \Malmanger\Mpmq\Util\ResponseHandler($data);
            
            return $resp->getResponse($response);
        }
    }
    public function updateMessage(Request $request, Response $response, array $args) {
        return "Update Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    public function getMessageInformation(Request $request, Response $response, array $args) {
        return "Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Delete message from queue - To be done up on completion
    public function deleteMessage(Request $request, Response $response, array $args) {
        return "Message Complete(delete)<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Get and release message from queue - To be done up on completion
    public function touchMessage(Request $request, Response $response, array $args) {
        return "Renew reserve time in-flight<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
//    // Add message from queue - To be done up on completion
//    public function addMessage(Request $request, Response $response, array $args) {
//        return "Adding message to queue<br>\ncomes here...<br>\n" . var_dump($args, true);
//    }
}
