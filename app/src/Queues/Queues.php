<?php
namespace Queues;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class Queues {

	public function __construct() {
		echo "Queues::__construct<br>\n";
		var_dump($this);
		echo "\n<br>END __construct<br>\n";
	}
	
    public function listQueues($request, $response, $args)
    {
    	return "listQueues <br>\n" . var_dump($response, true);
     }	

    public function getInformation($request, $response, $args)
    {
    	return "getInformation <br>\n" . var_dump($args, true);
     }	
    public function newQueue($request, $response, $args)
    {
        return "New Queue <br>\n" . var_dump($args, true);
     }  
    public function updateQueue($request, $response, $args)
    {
        return "Update Queue <br>\n" . var_dump($args, true);
     }  
    public function deleteQueue(Request $request, Response $response, $args)
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
