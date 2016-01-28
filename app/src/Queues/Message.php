<?php

namespace Queues;

class Message {
	// Get and reserve message from queue
	// Get information about message, but don't reserve it
	// Touch message to extend time-out
	public function touch($request, $response, $args) {
		return "Message touch<br>\ncomes here...<br>\n" . var_dump($args, true);	
	}
	// Release message back to queue, but don't reserve it
	public function release($request, $response, $args) {
		return "Message release<br>\ncomes here...<br>\n" . var_dump($args, true);	
	}
}