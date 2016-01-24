<?php

namespace Queues;

class Message {
	// Get and reserve message from queue
	public function getMessage($request, $response, $args) {
		return "Message <br>\ncomes here...<br>\n" . var_dump($args, true);
	}
	// Delete message from queue - To be done up on completion
	public function deleteMessage($request, $response, $args) {
		return "Message Complete(delete)<br>\ncomes here...<br>\n" . var_dump($args, true);
	}
	// Add message from queue - To be done up on completion
	public function addMessage($request, $response, $args) {
		return "Adding message to queue<br>\ncomes here...<br>\n" . var_dump($args, true);
	}
	// Get information about message, but don't reserve it
	public function information($request, $response, $args) {
		return "Message information<br>\ncomes here...<br>\n" . var_dump($args, true);	
	}
	// Touch message to extend time-out
	public function touch($request, $response, $args) {
		return "Message touch<br>\ncomes here...<br>\n" . var_dump($args, true);	
	}
	// Release message back to queue, but don't reserve it
	public function release($request, $response, $args) {
		return "Message release<br>\ncomes here...<br>\n" . var_dump($args, true);	
	}
}