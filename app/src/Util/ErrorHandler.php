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

    protected $errorCount = 0;
    protected $errorCode = self::CODE_NULL;
    protected $errorMessages = null;

    public function __construct() {
        $this->errorMessages = array();
    }

    public function getLevel() { return $this->errorCode; }
    public function addError($message, $level = self::CODE_GENERIC) {
        if ($level > $this->errorCode) {
            $this->errorCode = $level;
        }
        ++$this->errorCount;
        $this->errorMessages[] = $message;
    }

    public function getError() {
        $ret = array();
        $ret["code"] = $this->errorCode;
        $ret["messageCount"] = $this->errorCount;
        $ret["messages"] = $this->errorMessages;
        return $ret;
    }
}