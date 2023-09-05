<?php

namespace Core;

use App\AppConfiguration;

class Logger
{
    private $logFileError;

    private $logFileDebug;

    private $date;

    private $time;

    private static $_instance;

    public function __construct()
    {
        $appConfig = AppConfiguration::getAppConfig();

        $tz = $appConfig['time_zone'];
        $dt = new \DateTime("now", new \DateTimeZone($tz));

        $timestamp = time();
        $dt->setTimestamp($timestamp);
        $dateNow = $dt->format('Y-m-d');

        $this->date = $dateNow;
        $this->time = $dt->format('H:i:s');
        $logConfg = $appConfig['logs'];
        $this->logFileError = $logConfg['log_dir'] . '/' . $dateNow . '.' . $logConfg['log_file']['error'];
        $this->logFileDebug = $logConfg['log_dir'] . '/' . $dateNow . '.' . $logConfg['log_file']['debug'];
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function log($msg, $type = 'debug') {
        $msgs = explode("\n", $msg);
        if ($type == 'error') {
            foreach ($msgs as $key => $msg) {
                $label = 'Trace =>';
                if ($key == 0) {
                    $label = 'Message =>';
                }
                if ($key == 1) {
                    $this->logRoute($type);
                }
                $msg = $this->date . ' ' . $this->time . ': ' . $label . ' ' . $msg . "\n";
                if ($key + 1 >= count($msgs)) {
                    // $msg .= "\n";
                }
                file_put_contents($this->{'logFile' . ucfirst($type)}, $msg, FILE_APPEND);
            }
        }

        if ($type == 'debug') {
            foreach ($msgs as $key => $msg) {
                if ($key == 0) {
                    $this->logRoute($type);
                }
                if ($key + 1 >= count($msgs)) {
                    // $msg .= "\n";
                }
                file_put_contents($this->{'logFile' . ucfirst($type)}, $msg, FILE_APPEND);
            }
        }

    }

    private function logRoute($type) {
        $msg = $this->date . ' ' . $this->time . ': Route => "'. $_SERVER['REQUEST_URI']. "\"\n";
        $logFile = $this->{'logFile' . ucfirst($type)};
        file_put_contents($logFile, $msg, FILE_APPEND);
    }
}