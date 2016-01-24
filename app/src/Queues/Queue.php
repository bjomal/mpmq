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
    public function listAvailableMessages($request, $response, $args) {
        return "Available messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }   
    public function listInFlightMessages($request, $response, $args) {
        return "InFlight messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }   

    public function listFinishedMessages($request, $response, $args) {
        return "Finished messages list<br>\ncomes here...<br>\n" . var_dump($args, true);
    }   

}
