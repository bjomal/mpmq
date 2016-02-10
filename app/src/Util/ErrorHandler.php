<?php
namespace Malmanger\Mpmq\Util;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class ErrorHandler {
    const CODE_NULL = 0;
    const CODE_GENERIC = 1;
    const CODE_DATA_MISSING = 10;
    const CODE_DATA_INVALID = 11;
    const CODE_FATAL = 99;

    protected $log = null;
    protected $errorCount = 0;
    protected $errorCode = self::CODE_NULL;
    protected $errorMessages = null;

    public function __construct() {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->errorMessages = array();
    }

    public function getLevel() { return $this->errorCode; }
    public function getConstant($constant) { return constant('self::'.$constant); }
    public function addError($message, $level = self::CODE_GENERIC) {
$this->log->debug("addError level=" . print_r($level));
        if ($level > $this->errorCode) {
            $this->errorCode = $level;
        }
        ++$this->errorCount;
        $this->errorMessages[] = $message;
    }

    public function addMissing($key) {
        $this->addError("Missing value for required input '" . $key . "'", self::CODE_DATA_MISSING);
    }

    public function getError() {
        $ret = array();
        $ret["code"] = $this->errorCode;
        $ret["messageCount"] = $this->errorCount;
        $ret["messages"] = $this->errorMessages;
        return $ret;
    }

    public function getErrorResponse(Response $response) {
        $stream = $response->getBody();
        if ($this->errorCode > 0) {
            $stream->write(json_encode($this->getError()));
        }
        if ($this->errorCode >= 1 && $this->errorCode < 10) {

            $response = $response->withBody($stream)->withStatus(400, 'Invalid request');
        }
        if ($this->errorCode >= 10 && $this->errorCode < 20) {
            $response = $response->withBody($stream)->withStatus(400, 'Invalid request');
        }
        if ($this->errorCode >= 20 ) {
            $response = $response->withBody($stream)->withStatus(500, 'Server Error');
        }
        return $response;
    }
}