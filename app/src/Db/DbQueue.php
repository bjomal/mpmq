<?php
namespace Malmanger\Mpmq\Db;


class DbQueue {
    const DEFAULT_TIMEOUT = 86400;
    protected $log = null;
    protected $isSaved = false;
    protected $id = '';
    protected $name = '';
    protected $description = '';
    protected $timeout = self::DEFAULT_TIMEOUT;
    protected $existed = false;

    public function __construct($id, $name, $description = '', $timeout = self::DEFAULT_TIMEOUT) {
        if (empty($timeout)) { $timeout = self::DEFAULT_TIMEOUT; }
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->id = $id;
        if ($this->load()) {
            $this->log->debug("id=$id alredy existed. Cannot create!!!");
            $this->existed = true;
        } else {
            $this->name = $name;
            $this->description = $description;
            $this->timeout = $timeout;
            $this->changed();
        }
    }

    // Mark class instance as changed
    private function changed() {
        $this->isSaved = false;
        return $this;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function setName(string $name) { $this->changed()->name = $name; return $this;}
    public function getDescription() { return $this->description; }
    public function setDescription(string $description) { $this->changed()->description = $description; return $this;}
    public function getTimeout() { return $this->timeout; }
    public function setTimeout(int $timeout) { $this->changed()->timeout = $timeout; return $this;}

    public function queueExists() {
        return $this->existed;
    }

    public function load($id = '') {    // return true if successful, else false
        global $database;

        $loadId = $this->id;
        if (strlen($id) > 0) {
            $loadId = $id;
        }
        $queueData = $database->getQueue($loadId);
$this->log->debug("loaded queue ".var_export($queueData, true));
        if (empty($queueData)) {
            $this->log->debug("No queue data, so returning with result=false");
            return false;
        }
        $this->id = $queueData["id"];
        $this->name = $queueData["name"];
        $this->description = $queueData["description"];
        $this->timeout = $queueData["timeout"];
        return true;
    }
    public function save() {    // return true if successful, else false
        global $database;
        if (!$database->updateQueue($this->id, $this->name, $this->description, $this->timeout)) {
            return false;
        }
        $this->existed = true;
        $this->isSaved = true; //NOTE! Must save to db first!!!
        return true;
    }

    public function delete() {    // return true if successful, else false
        global $database;
        if (!$database->deleteQueue($this->id)) {
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
        $queueData = array();
        $queueData["id"] = $this->id;
        $queueData["name"] = $this->name;
        $queueData["description"] = $this->description;
        $queueData["timeout"] = $this->timeout;
        return $queueData;
    }
}