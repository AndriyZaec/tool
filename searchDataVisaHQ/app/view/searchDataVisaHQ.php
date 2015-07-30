<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
define( "DB_DSN", "mysql:host=localhost;dbname=searchVisaHQ" );
define( "DB_LOGIN", "root" );
define( "DB_PASS", "charok" );
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$db=new PDO( DB_DSN, DB_LOGIN, DB_PASS, $opt );


if($_REQUEST['data'])
{
    $data = quotemeta($_REQUEST['data']);
}
else
{
    $data = 'VisaHQ';
}
$file = file_get_contents('https://www.visahq.com/');

preg_match_all("#\<div class=\"footer\_lefttop\"\>\s+(.*?)($data)(.*?)\s+\<\/div\>#", $file, $match);
//var_dump($match);

if (!empty($match[2][0]))
{
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $hour = date('H');
    $minutes = date('i');

    $date = new \DateTime();
    $date->setTime($hour, $minutes);
    $date->setDate($year, $month, $day);
    $date->setTimezone(new \DateTimeZone('Europe/Kiev'));
    $time = $date->getTimestamp();
    $date->setTimestamp($time);
    var_dump($date->format('Y-m-d H:i:s'));
    var_dump($match[2][0]);


    $execute = $db->prepare("SELECT log_name FROM logs WHERE log_name=:log_name");
    $execute->execute(array(':log_name' => $match[2][0]));
    $row = $execute->fetch(PDO::FETCH_ASSOC);

    if($row == false)
    {
        $execute = $db->prepare("INSERT INTO logs (log_name, time_log) VALUES (:log_name, :time_log)");
        $execute->execute(array(':log_name' => $match[2][0], ':time_log' => $time));
    }
    /*
    $write_log = $_REQUEST['data'] . '|' . $time . '|||';
    $fp = fopen("logs.txt", "a+");
    fwrite($fp, $write_log);
    fclose($fp);
    */
}
?>
