<?php
namespace Malmanger\Mpmq\Db;


class DbQueue {
    protected $log = null;
    protected $isSaved = false;
    protected $id = '';
    protected $name = '';
    protected $description = '';
    protected $timeout = 86400;

    public function __construct($id, $name, $description = '', $timeout = 86400) {
        $log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->id = $id;
        if (!$this->load()) {
            $this->name = $name;
            $this->description = $description;
            $this->timeout = $timeout;
            $this->changed();
        }
    }

    private function changed() {
        $this->isSaved = false;
        return $this;
    }

    public function getName() { return $this->name; }
    public function setName(string $name) { $this->changed()->name = $name; return $this;}
    public function getDescription() { return $this->description; }
    public function setDescription(string $description) { $this->changed()->description = $description; return $this;}
    public function getTimeout() { return $this->timeout; }
    public function setTimeout(int $timeout) { $this->changed()->timeout = $timeout; return $this;}

    public function load($id = '') {    // return true if successful, else false
        global $database;
        // TODO: get information from database
        $loadId = $this->id;
        if (strlen($id) > 0) {
            $loadId = $id;
        }
        $queueData = $database->getQueue($loadId);
        if (empty($queueData)) {
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
        // TODO: save to database

        $this->changed();
        return false;
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