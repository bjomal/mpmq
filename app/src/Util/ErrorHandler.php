<?php
namespace Malmanger\Mpmq\Util;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class ErrorHandler {
    const CODE_NULL = 0;
    const CODE_GENERIC = 1;
    const CODE_DATA_NOT_FOUND = 10;
    const CODE_DATA_MISSING = 20;
    const CODE_DATA_INVALID = 21;
    const CODE_DATA_EXIST = 25;
    const CODE_DB_UPDATE_FAILED = 39;
    const CODE_FATAL = 99;

    private static $ERROR_CODES = array(
        self::CODE_NULL => "success",
        self::CODE_GENERIC => "generic error",
        self::CODE_DATA_MISSING => "data missing",
        self::CODE_DATA_INVALID => "invalid data",
        self::CODE_DATA_EXIST => "date already exist",
        self::CODE_DATA_NOT_FOUND => "data not found",
        self::CODE_DB_UPDATE_FAILED => "database update failed",
        self::CODE_FATAL => "fatal error"
    );

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
        if ($level > $this->errorCode) {
            $this->errorCode = $level;
        }
        ++$this->errorCount;
        $this->errorMessages[] = $message;
    }

    public function addMissing($key) {
        $this->addError("Missing value for required input '" . $key . "'", self::CODE_DATA_MISSING);
    }
    public function addExists($key) {
        $this->addError("Item '" . $key . "' already exists", self::CODE_DATA_EXIST);
    }
    public function addNotFound($key) {
        $this->addError("Item '" . $key . "' not found", self::CODE_DATA_NOT_FOUND);
    }
    public function addDbUpdate($key) {
        $this->addError("Database update feiled on '" . $key . "'", self::CODE_DB_UPDATE_FAILED);
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

        switch ($this->errorCode) {
            case self::CODE_NULL:
                $response = $response->withStatus(200, 'Unknown');
                break;
            case self::CODE_GENERIC:
                $response = $response->withBody($stream)->withStatus(400, 'Invalid request');
                break;
            case self::CODE_DATA_MISSING:
            case self::CODE_DATA_INVALID:
            case self::CODE_DATA_EXIST:
                $response = $response->withBody($stream)->withStatus(400, 'Invalid request');
                break;
            case self::CODE_DATA_NOT_FOUND:
                $response = $response->withBody($stream)->withStatus(404, 'Not found');
                break;
            case self::CODE_DB_UPDATE_FAILED:
            case self::CODE_FATAL:
                $response = $response->withBody($stream)->withStatus(500, 'Server Error');
                break;
            default:
                $response = $response->withStatus(200, 'Unknown');
                break;
        }
        return $response;
    }
}
