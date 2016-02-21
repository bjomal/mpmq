<?php 
namespace Malmanger\Mpmq\Util;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamInterface as Stream;

class ResponseHandler {
    const CODE_OK = 200;
    const CODE_CREATED = 201;
    const CODE_ACCEPTED = 202;
    const CODE_NO_CONTENT = 204;
    const CODE_MOVED_PERMANENTLY = 301;
    const CODE_FOUND = 302;
    const CODE_SEE_OTHER = 303;

    private static $STATUS_CODES = array(
        self::CODE_OK => "OK",
        self::CODE_CREATED => "Created",
        self::CODE_ACCEPTED => "Accepted",
        self::CODE_NO_CONTENT => "No Content",
        self::CODE_MOVED_PERMANENTLY => "Moved Permanently",
        self::CODE_FOUND => "Found",
        self::CODE_SEE_OTHER => "See Other"
    );

    protected $log = null;
    protected $data = null;
    protected $statusCode = 200;
    protected $message = 'OK';
    protected $redirectUrl = null;

    public function __construct($data = null) {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        if (!empty($data)) {
            $this->data = $data;
        }
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }
    public function setUrl($url) {
        $this->redirectUrl = $url;
        return $this;
    }

    public function setStatus($statusCode = self::CODE_OK, $message = null) {
        $this->statusCode = $statusCode;
        if (empty($message)) {
            $this->message = self::$STATUS_CODES[$statusCode];
        } else {
            $this->message = $message;
        }
        return $this;
    }

    public function getResponse($response) {
        if (!empty($this->data)) {
            $stream = $response->getBody();
            $stream->write(json_encode($this->data));
            $response = $response->withBody($stream);
        }
$this->log->debug("statusCode".$this->statusCode);
        switch ($this->statusCode) {
            case self::CODE_FOUND:
$this->log->debug("CODE_SEE_OTHER");
                if (strlen($this->redirectUrl)>0) {
$this->log->debug("redirectUrl:".$this->redirectUrl);
                    $response = $response->withHeader('Location', $this->redirectUrl);
                }
                break;
            default:
                break;
        }

$this->log->debug("setting status:".$this->statusCode);
        $response = $response->withStatus($this->statusCode, $this->message);
        return $response;
    }

}