<?php
/**
 * Default route handle
 *
 */
namespace Malmanger\Mpmq\Util;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

use Malmanger\Mpmq\Util\ResponseHandler as ResponseHandler;
use Malmanger\Mpmq\Util\ErrorHandler as ErrorHandler;

/**
 * Default route handle
 *
 */
class Misc {

    protected $log = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
    }

    /**
     * Handle access to root web folder
     *
     * @param RPsr\Http\Message\RequestInterface $request The HTTP request object
     * @param Psr\Http\Message\ResponseInterface $response The HTTP response object
     * 
     * @return Psr\Http\Message\ResponseInterface new Response instance
     */
    public function root(Request $request, Response $response, array $args) {
        global $misc;
        $resp = new \Malmanger\Mpmq\Util\ResponseHandler();
        if (array_key_exists('root', $misc)) {
            $resp->setStatus(\Malmanger\Mpmq\Util\ResponseHandler::CODE_FOUND)->setUrl($misc['root']);
        } else {
            $resp->setStatus(\Malmanger\Mpmq\Util\ResponseHandler::CODE_FOUND)->setUrl('/doc/');

        }
        return $resp->getResponse($response);
    }
}
