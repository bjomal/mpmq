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

    public function getQueue($id) {
        // id, name, description, timeout
        $ret = array();
        $sql = "SELECT * FROM queues WHERE id = '${id}' ORDER BY id ASC";
        $results = $this->db->query($sql);
        while ($row = $results->fetchArray()) {
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
}