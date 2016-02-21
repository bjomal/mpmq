<?php
namespace Malmanger\Mpmq\Db;


class DbMessage {
    const DEFAULT_TIMEOUT = 86400;
    protected $log = null;
    protected $isSaved = false;
    protected $queueId = null;
    protected $messageId = '';
    protected $title = '';
    protected $payload = '';
    protected $timeout = self::DEFAULT_TIMEOUT;
    protected $inFlight = false;
    protected $existed = false;

    public function __construct($queueId, $messageId = null, $title = '', $payload = '', $timeout = self::DEFAULT_TIMEOUT) {
        if (empty($timeout)) { $timeout = self::DEFAULT_TIMEOUT; }
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->queueId = $queueId;
        $this->messageId = $messageId;
        if ($this->messageId !== null && $this->load()) {
            $this->log->debug("id=$id alredy existed. Cannot create!!!");
            $this->existed = true;
        } else {
            $this->title = $title;
            $this->payload = $payload;
            $this->timeout = $timeout;
            $this->inFlight = false;
            $this->changed();
        }
    }

    // Mark class instance as changed
    private function changed() {
        $this->isSaved = false;
        return $this;
    }

    public function getMessageId() { return $this->messageId; }
    public function getQueueId() { return $this->queueId; }
    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->changed()->title = $title; return $this;}
    public function getPayload() { return $this->payload; }
    public function setPayload($payload) { $this->changed()->payload = $payload; return $this;}
    public function getTimeout() { return $this->timeout; }
    public function setTimeout($timeout) { $this->changed()->timeout = $timeout; return $this;}
    public function getInFlight() { return $this->inFlight; }
    public function setInFlight($inFlight) { $this->changed()->inFlight = $inFlight; return $this;}

    public function messageExists() {
        return $this->existed;
    }

    public function load($messageId = '') {    // return true if successful, else false
        global $database;

        $loadId = $this->messageId;
        if (strlen($messageId) > 0) {
            $loadId = $messageId;
        }
        $messageData = $database->getMessage($loadId);
$this->log->debug("loaded message ".var_export($messageData, true));
        if (empty($messageData)) {
            $this->log->debug("No queue data, so returning with result=false");
            return false;
        }
        $this->messageId = $messageData["messageId"];
        $this->title = $messageData["title"];
        $this->payload = $messageData["payload"];
        $this->timeout = $messageData["timeout"];
        $this->inFlight = $messageData["inFlight"];
        return true;
    }
    public function save() {    // return true if successful, else false
        global $database;
        $newId = false;
        if ($this->queueI > 0) {
            if (!$database->updateMessage($this->queueId, $this->messageId, $this->title, $this->payload, $this->timeout, $this->inFlight)) {
                return false;
            } else {
                $newId = $this->messageId;
            }
        } else {
            $newId = $database->createMessage($this->queueId, $this->title, $this->payload, $this->timeout);
            if (!$newId) {
                return false;
            }
        }
        $this->existed = true;
        $this->isSaved = true; //NOTE! Must save to db first!!!
        return true;
    }

    public function delete() {    // return true if successful, else false
        global $database;
        if (!$database->deleteQueue($this->messageId)) {
            return false;
        }
        $this->existed = false;
        $this->isSaved = false; //NOTE! Must save to db first!!!
        return true;
    }

    public function listMessages() {
        // TODO: implement listMessages
    }

    public function toArray() {
        $messageData = array();
        $messageData["messageId"] = $this->messageId;
        $messageData["title"] = $this->title;
        $messageData["payload"] = $this->payload;
        $messageData["timeout"] = $this->timeout;
        return $messageData;
    }
}