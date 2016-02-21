<?php
namespace Malmanger\Mpmq\Db;

abstract class Db {
    const SCHEMA_VERSION = 1;
    protected $log = null;
    protected $db = null;           // The database connection
    protected $connected = false;   // If connected
    protected $data = null;         // Connection parameters
    protected $type = 'generic';    // Database type description
    protected $schema = 'mpmq';     //
    protected $tablePrefix = '';    // 

    abstract public function connect();
    abstract public function listQueues();
    /**
     * get messages from database
     * 
     * @param string $id ID of queue to get
     * 
     * @return array messageData for queue
     */
    abstract public function listMessages($id);
    abstract public function getQueue($id);
    abstract public function queueExists($id);
    abstract public function updateQueue($id, $name, $description, $timeout);
    abstract public function deleteQueue($id);
    abstract public function messageExists($id, $messageId);
    abstract public function createMessage($id, $name, $description, $timeout);
    abstract public function updateMessage($id, $messageId, $name, $description, $timeout, $inFlight);
    abstract public function deleteMessage($id, $messageId);
    /**
     * Gets a config value from the database
     *
     * @param string $key the config value to read
     *
     * @return string|null|false The key's value or null if none provided. False on error.
     */
    abstract public function getConfig($key);
    /**
     * Sets a config value to the database
     *
     * @param string $key the config key to set
     * @param string $value the config value to set
     *
     * @return true|false False on error, else true
     */
    abstract public function setConfig($key, $value);

    public function __construct($data) {
        $this->log = \Malmanger\Mpmq\Util\Log::getInstance();
        $this->type = 'generic';
        $this->connected = false;
        $this->data = $data;
        if (array_key_exists('prefix', $this->data)) {
            $this->tablePrefix = $this->data['prefix'];
        }

        if (!$this->connect()) {
            throw new \Exception('Unable to connect to database');
        }
    }

    public function getType() {
        return $this->type;
    }

}