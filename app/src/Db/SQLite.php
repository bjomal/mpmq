<?php
namespace Malmanger\Mpmq\Db;

class SQLite extends Db {
    private function query($sql) {
        $results = false;
        $this->log->debug("Running SQL:".$sql);
        $results = $this->db->query($sql);
        if (!$results) {
            $this->log->error("Unable to run query");
            $this->log->info("SQL: " . $sql);
            $this->log->debug($this->db->lastErrorMsg());
            return false;
        }
        return $results;
    }

    public function connect() {
        $this->connected = false;
        $databaseFile = MPMQ_ROOT . $this->data["database"];

        if(!file_exists($databaseFile) && !is_writable(dirname($databaseFile))) { //make sure the containing directory is writable if the database does not exist
            $getcwd = getcwd();
            $file_exists = file_exists($databaseFile) ? "true" : "false";
            $is_writable = is_writable(dirname($databaseFile)) ? "true" : "false";
            $this->log->error("Unable to access database: " . $databaseFile . " - Please check that it's writable\n\t\tfile_exist=$file_exists && is_writable=$is_writable");

            return false;
        } 
        $this->connected = true;

        try {
            $this->db = new \SQLite3($databaseFile);
        } catch (Exception $e) {
            $this->log->error("Unable to create database object for: " . $databaseFile . "!");
            $this->log->debug($e->getMessage());
            return false;
        }

        if($this->db!=null)
        {
            $this->type = "SQLite3";
            $this->connected = true;
            // TODO: Check for error and return status
            if (!$this->dbInitialize()) {
                $this->log->debug("Unable to initialize db");
                return false;
            }
        }

        return true;
    }

    public function dbInitialize() {
        $ret = true;
        // TODO: Check for error and return status
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->tablePrefix."config
            (
                key         CHAR(50)    NOT NULL    UNIQUE,
                value       TEXT        NOT NULL
            );";
        if (!$this->query($sql)) {
            $ret = false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS ".$this->tablePrefix."queues
            (
                id          CHAR(50)    NOT NULL    UNIQUE,
                name        TEXT        NOT NULL,
                description TEXT        NULL,
                timeout     INTEGER     NOT NULL
            );";
        if (!$this->query($sql)) {
            $ret = false;
        }

        $sql = "CREATE TABLE IF NOT EXISTS ".$this->tablePrefix."messages
            (
                messageId   INTEGER     PRIMARY KEY AUTOINCREMENT,
                queueId     CHAR(50)    NOT NULL,
                title       TEXT        NULL,
                payload     BLOB        NULL,
                timeout     INTEGER     NOT NULL,
                inFlight    INTEGER     NULL,
                timestamp   INTEGER     DEFAULT     CURRENT_TIMESTAMP
            );";
        if (!$this->query($sql)) {
            $ret = false;
        }
        if ($ret) {
            $schemaVersion = $this->getConfig('SCHEMA_VERSION');
            if ($schemaVersion === null) {  // If not set...
                $this->setConfig('SCHEMA_VERSION', self::SCHEMA_VERSION);
            }
        }
        return $ret;
    }

    public function getConfig($key) {
        $ret = false;
        $sql = "SELECT value FROM ".$this->tablePrefix."config WHERE key = '".$key."'";
        $results = $this->db->querySingle($sql);
        if ($results === null) { // returns null if no key then set key
            $ret = null;  // return empty string if no value found
        } elseif (!$results) {
            // Query Failed
            $this->log->error("Unable to run query");
            $this->log->info("SQL: " . $sql);
            $this->log->debug($results->lastErrorMsg());
            $ret = false; // return false on error
        } else {
            $ret = $results;
        }
        return $ret;
    }
    public function setConfig($key, $value) {
        $ret = true;
        $sql = "INSERT OR REPLACE INTO ".$this->tablePrefix."config (key, value) VALUES ( '$key', '$value' );";
        if (!$this->query($sql)) {
            $ret = false;
        }
        return $ret;
    }

    public function listQueues() {
        $ret = null;
        $sql = "SELECT * FROM ".$this->tablePrefix."queues;";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            if ($ret === null) { $ret = array(); }
            $ret[] = $row;
        }
        return $ret;
    }

    /**
     * get messages from database
     * 
     * @param string $id ID of queue to get
     * 
     * @return array messageData for queue
     */
    public function listMessages($id) {
        $ret = null;
        $sql = "SELECT * FROM ".$this->tablePrefix."messages WHERE queueId = '".$id."' ORDER BY timestamp ASC;";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            if ($ret === null) { $ret = array(); }
            $ret[] = $row;
        }
        return $ret;
    }

    public function getQueue($id) {
        $ret = null;
        $sql = "SELECT * FROM ".$this->tablePrefix."queues WHERE id = '${id}' ORDER BY id ASC";
        $results = $this->db->query($sql);
        $this->log->debug("select id=$id returns: " . var_export($results, true));
        while ($row = $results->fetchArray()) {
        $this->log->debug("fetchArray returns: " . var_export($row, true));
            $ret = array();
            $key = "id";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "name";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "description";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "timeout";
            if (array_key_exists($key, $row)) { $ret[$key] = intval($row[$key]); }
        }
        return $ret;
    }

    public function getMessage($messageId) {
        $ret = null;
        $sql = "SELECT * FROM ".$this->tablePrefix."messages WHERE messageId = '${messageId}'";
        $results = $this->db->query($sql);
        $this->log->debug("select messageId=$messageId returns: " . var_export($results, true));
        while ($row = $results->fetchArray()) {
        $this->log->debug("fetchArray returns: " . var_export($row, true));
            $ret = array();
            $key = "messageId";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "title";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "payload";
            if (array_key_exists($key, $row)) { $ret[$key] = $row[$key]; }
            $key = "timeout";
            if (array_key_exists($key, $row)) { $ret[$key] = intval($row[$key]); }
            $key = "inFlight";
            if (array_key_exists($key, $row)) { $ret[$key] = intval($row[$key]); }
        }
        return $ret;
    }

    public function queueExists($id) {
        $ret = false;
        $sql = "SELECT id FROM ".$this->tablePrefix."queues WHERE id = '${id}'";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            $ret = true;
        }
        return $ret;
    }

    public function updateQueue($id, $name, $description, $timeout) {
        $sql = "INSERT OR REPLACE INTO ".$this->tablePrefix."queues (id, name, description, timeout) VALUES ( '$id', '$name', '$description', $timeout );";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }
    public function deleteQueue($id) {
        $sql = "DELETE FROM ".$this->tablePrefix."queues WHERE id = '${id}'";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }

    public function messageExists($id, $messageId) {
        $ret = false;
        $sql = "SELECT id FROM ".$this->tablePrefix."messages WHERE id = '${id}'";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * Create message 
     * 
     * @param string $id
     * @param string $title
     * @param string $payload
     * @param integer $timeout
     *
     * @return boolean true|false true on success
     */
    public function createMessage($id, $title, $payload, $timeout) {
        $sql = "INSERT INTO ".$this->tablePrefix."messages (queueId, title, payload, timeout, inFlight) VALUES ( '$id', '$title', '$payload', $timeout, 0 );";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
//            $newId = $this->db::lastInsertRowId();
            return $this->db->lastInsertRowId();
        } else { 
            return false;
        }
    }

    /**
     * Update message 
     * 
     * @param string $id
     * @param integer $messageId
     * @param string $title
     * @param string $payload
     * @param integer $timeout
     * @param integer $inFlight
     *
     * @return boolean true|false true on success
     */
    public function updateMessage($id, $messageId, $title, $payload, $timeout, $inFlight) {
        $sql = "UPDATE ".$this->tablePrefix."messages SET title = '$title', payload = '$payload', timeout = $timeout, inFlight = $inFlight WHERE queueId='$id' AND messageId=$messageId;";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }

    public function deleteMessage($id, $messageId) {
        $sql = "DELETE FROM ".$this->tablePrefix."messages WHERE queueId = '${id}' AND messageId = '${messageId}';";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }

}