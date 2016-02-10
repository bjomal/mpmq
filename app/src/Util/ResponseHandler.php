<?php 
namespace Malmanger\Mpmq\Util;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class ResponseHandler {
    protected $log = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
    }

}