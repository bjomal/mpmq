<?php
namespace Malmanger\Mpmq\Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class Queue {
    // private $id;

    // public function __construct($id) {
    //     $this->id = $id;
    // }

    public function listMessages(Request $request, Response $response, array $args) {
    	return "All messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
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
    public function newMessage(Request $request, Response $response, array $args) {
        return "New Message <br>\ncomes here...<br>\n" . var_dump($args, true);
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
