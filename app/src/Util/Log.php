<?php
namespace Malmanger\Mpmq\Util;

class Log {
    const ERROR = 2;
    const WARNING = 4;
    const INFO = 8;
    const DEBUG = 16;

    private static $logLevel = self::WARNING;
    private static $instance = null;    // Implement as singleton-ish

    // Make Private to nonly have one instance
    private function __construct() {       
    }
    private static function output($message, $prefix = '', $level = self::INFO) {
        if (strlen($prefix) > 0 ) {
            error_log($prefix."($level): ".$message);
        } else {
            error_log($message);
        }
    }

    public static function getInstance ($logLevel = self::WARNING)
    {
        if (self::$instance === null) {
            self::$instance = new self;
            // Only use setLogLevel when creatinf first instance
            self::$instance->setLogLevel($logLevel);
        }
        return self::$instance;
    }

    public function setLogLevel($logLevel = self::WARNING) {
        self::$logLevel = $logLevel;
        return $this;
    }

    public function log($message, $level = self::INFO) {
        switch ($level) {
            case self::ERROR:
                if (self::$logLevel >= self::ERROR) {
                    self::output($message, 'MPMQ_ERROR', $level);
                }
                break;
            case self::WARNING:
                if (self::$logLevel >= self::WARNING) {
                    self::output($message, 'MPMQ_WARNING', $level);
                }
                break;
            case self::INFO:
                if (self::$logLevel >= self::INFO) {
                    self::output($message, 'MPMQ_INFO', $level);
                }
                break;
            case self::DEBUG:
            default:
                if (self::$logLevel >= self::DEBUG) {
                    self::output($message, 'MPMQ_DEBUG', $level);
                }
                break;
        }
        return $this;
    }
    public function error($message) {
        $this->log($message, self::ERROR);
        return $this;
    }
    public function warning($message) {
        $this->log($message, self::WARNING);
        return $this;
    }
    public function info($message) {
        $this->log($message, self::INFO);
        return $this;
    }
    public function debug($message) {
        $this->log($message, self::DEBUG);
        return $this;
    }
}