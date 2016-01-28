<?php
namespace Queues;

class Queue {
    // private $id;

    // public function __construct($id) {
    //     $this->id = $id;
    // }

    public function listMessages($request, $response, $args) {
    	return "All messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }	
    // public function listMessages() {
    //     return var_dump($this, true);
    // }   
    public function listInFlightMessages($request, $response, $args) {
        return "InFlight messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }   
    public function getMessage($request, $response, $args) {
        return "Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Get and release message from queue - To be done up on completion
    public function releaseMessage($request, $response, $args) {
        return "Release message back to queue<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    public function newMessage($request, $response, $args) {
        return "New Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    public function updateMessage($request, $response, $args) {
        return "Update Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    public function getMessageInformation($request, $response, $args) {
        return "Message <br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Delete message from queue - To be done up on completion
    public function deleteMessage($request, $response, $args) {
        return "Message Complete(delete)<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
    // Get and release message from queue - To be done up on completion
    public function touchMessage($request, $response, $args) {
        return "Renew reserve time in-flight<br>\ncomes here...<br>\n" . var_dump($args, true);
    }
//    // Add message from queue - To be done up on completion
//    public function addMessage($request, $response, $args) {
//        return "Adding message to queue<br>\ncomes here...<br>\n" . var_dump($args, true);
//    }
}
