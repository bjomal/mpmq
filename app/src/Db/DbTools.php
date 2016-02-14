<?php
namespace Malmanger\Mpmq\Db;

class DbTools {
    private static $instance = null;    // Implement as singleton
    private static $db = null;

    // Make Private to nonly have one instance
    private function __construct() {       
    }

    public static function getInstance ($db) {
        if (self::$instance === null) {
            self::$instance = new self;
            // Only use setLogLevel when creatinf first instance
            self::$instance->setDb($db);
        }
        return self::$instance;
    }

    public static function setDb ($db) {
        self::$db = $db;
    }

    public static function getQueues() {
        return self::$db->listQueues();
    }
}
