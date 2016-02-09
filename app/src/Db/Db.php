<?php
namespace Malmanger\Mpmq\Db;

class Db {
    protected $log = null;
    protected $db = null;           // The database connection
    protected $connected = false;   // If connected
    protected $data = null;         // Connection parameters
    protected $type = 'generic';    // Database type description
    protected $schema = 'mpmq';     //
    protected $prefix = '';          //

    public function __construct($data) {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->type = 'generic';
        $this->connected = false;
        $this->data = $data;
        $this->connect();
    }

    public function connect() {
        // TODO: This is a stub
    }
    
    public function getType() {
        return $this->type;
    }

    public function listQueues() {
        // TODO: override - query queues and return array of 
    }

    public function getQueue($id) {
        // TODO: override -         
    }

}