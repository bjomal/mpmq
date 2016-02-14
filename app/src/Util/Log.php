<?php
namespace Malmanger\Mpmq\Util;

class Log {
    const ERROR = 2;
    const WARNING = 4;
    const INFO = 8;
    const DEBUG = 16;

    private static $logLevel = self::WARNING;
    private static $instance = null;    // Implement as singleton
    private static $lastTrace = null;

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

    private function log($message, $level = self::INFO) {
        $caller = '';
        if (self::$logLevel >= self::DEBUG ) {
            $callerFunction = self::$lastTrace['function'];
            $callerClass = self::$lastTrace['class'];
            $caller = "${callerClass}->${callerFunction} in file '':\n";
        }
        switch ($level) {
            case self::ERROR:
                if (self::$logLevel >= self::ERROR) {
                    self::output($caller.$message, 'MPMQ_ERROR', $level);
                }
                break;
            case self::WARNING:
                if (self::$logLevel >= self::WARNING) {
                    self::output($caller.$message, 'MPMQ_WARNING', $level);
                }
                break;
            case self::INFO:
                if (self::$logLevel >= self::INFO) {
                    self::output($caller.$message, 'MPMQ_INFO', $level);
                }
                break;
            case self::DEBUG:
            default:
                if (self::$logLevel >= self::DEBUG) {
                    self::output($caller.$message, 'MPMQ_DEBUG', $level);
                }
                break;
        }
        return $this;
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

    public function error($message) {
        if (self::$logLevel >= self::DEBUG ) {
            $trace = debug_backtrace();
            if (array_key_exists(1, $trace)) { self::$lastTrace = $trace[1]; } else { self::$lastTrace = $trace[0]; }
        }
        $this->log($message, self::ERROR);
        return $this;
    }
    public function warning($message) {
        if (self::$logLevel >= self::DEBUG ) {
            $trace = debug_backtrace();
            if (array_key_exists(1, $trace)) { self::$lastTrace = $trace[1]; } else { self::$lastTrace = $trace[0]; }
        }
        $this->log($message, self::WARNING);
        return $this;
    }
    public function info($message) {
        if (self::$logLevel >= self::DEBUG ) {
            $trace = debug_backtrace();
            if (array_key_exists(1, $trace)) { self::$lastTrace = $trace[1]; } else { self::$lastTrace = $trace[0]; }
        }
        $this->log($message, self::INFO);
        return $this;
    }
    public function debug($message) {
        if (self::$logLevel >= self::DEBUG ) {
            $trace = debug_backtrace();
            if (array_key_exists(1, $trace)) { self::$lastTrace = $trace[1]; } else { self::$lastTrace = $trace[0]; }
        }
        $this->log($message, self::DEBUG);
        return $this;
    }
}