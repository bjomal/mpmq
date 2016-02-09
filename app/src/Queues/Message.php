<?php
namespace Malmanger\Mpmq\Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class Message {
    // Get and reserve message from queue
    // Get information about message, but don't reserve it
    // Touch message to extend time-out
    public function touch(Request $request, Response $response, array $args) {
        return "Message touch<br>\ncomes here...<br>\n" . var_dump($args, true);    
    }
    // Release message back to queue, but don't reserve it
    public function release(Request $request, Response $response, array $args) {
        return "Message release<br>\ncomes here...<br>\n" . var_dump($args, true);  
    }
}
