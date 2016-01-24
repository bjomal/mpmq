<?php
namespace Queues;

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
}
