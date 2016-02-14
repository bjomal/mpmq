<?php
namespace Malmanger\Mpmq\Db;

class SQLite extends Db {
    
    public function connect() {
        $databaseFile = MPMQ_ROOT . $this->data["database"];
        
        if(!file_exists($databaseFile) && !is_writable(dirname($databaseFile))) { //make sure the containing directory is writable if the database does not exist
            $this->connected = false;
            $getcwd = getcwd();
            $file_exists = file_exists($databaseFile) ? "true" : "false";
            $is_writable = is_writable(dirname($databaseFile)) ? "true" : "false";
            error_log("Unable to connect to database: " . $databaseFile . " - Please check that it's writable\n\t\tfile_exist=$file_exists && is_writable=$is_writable");
            exit();
        } 
        $this->connected = true;

        $this->db = new \SQLite3($databaseFile);

        if($this->db!=null)
        {

            $this->type = "SQLite3";
            $this->connected = true;
            $this->dbInitialize();
        }
    }

    public function dbInitialize() {
        $sql = "CREATE TABLE IF NOT EXISTS queues
            (
                id          CHAR(50)    NOT NULL    UNIQUE,
                name        TEXT        NOT NULL,
                description TEXT        NULL,
                timeout     INTEGER     NOT NULL
            );";
        $results = $this->db->query($sql);
    }

    public function listQueues() {
        $ret = null;
        $sql = "SELECT * FROM queues;";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            if ($ret === null) { $ret = array(); }
            $ret[] = $row;
        }
        
        return $ret;
    }

    public function getQueue($id) {
        // id, name, description, timeout
        $ret = null;
        $sql = "SELECT * FROM queues WHERE id = '${id}' ORDER BY id ASC";
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

    public function queueExists($id) {
        $ret = false;
        $sql = "SELECT id FROM queues WHERE id = '${id}'";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
            $ret = true;
        }
        return $ret;
    }

    public function updateQueue($id, $name, $description, $timeout) {
        $sql = "INSERT OR REPLACE INTO queues (id, name, description, timeout) VALUES ( '$id', '$name', '$description', $timeout );";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }
    public function deleteQueue($id) {
        $sql = "DELETE FROM queues WHERE id = '${id}'";
        $this->log->debug("running sql query: " . $sql);
        if ($this->db->query($sql)) {
            return true;
        } else { 
            return false;
        }
    }
}