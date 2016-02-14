<?php
namespace Malmanger\Mpmq\Db;

abstract class Db {
    protected $log = null;
    protected $db = null;           // The database connection
    protected $connected = false;   // If connected
    protected $data = null;         // Connection parameters
    protected $type = 'generic';    // Database type description
    protected $schema = 'mpmq';     //
    protected $prefix = '';          //

    abstract public function connect();
    abstract public function listQueues();
    abstract public function getQueue($id);
    abstract public function queueExists($id);
    abstract public function updateQueue($id, $name, $description, $timeout);
    abstract public function deleteQueue($id);

    public function __construct($data) {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->type = 'generic';
        $this->connected = false;
        $this->data = $data;
        $this->connect();
    }

    public function getType() {
        return $this->type;
    }

}