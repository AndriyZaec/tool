<?php

class Model
{
    public function __construct()
    {
        define( "DB_DSN", "mysql:host=localhost;dbname=watchdog;charset=utf8" );
        define( "DB_LOGIN", "watchdog" );
        define( "DB_PASS", "6COuuyPEqV" );
    }

    public static $checkPDO = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    public static function getNewDate($day, $month, $year)
    {
        $date = new \DateTime();
        $date->setTime(0, 0);
        $date->setDate($year, $month, $day);
        $date->setTimezone(new \DateTimeZone('Europe/Moscow'));
        $time=$date->getTimestamp();
        return $time;
    }
}
?>