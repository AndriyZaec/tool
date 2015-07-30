<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
define( "DB_DSN", "mysql:host=localhost;dbname=watchdog" );
define( "DB_LOGIN", "watchdog" );
define( "DB_PASS", "6COuuyPEqV" );

include_once('simple_html_dom.php');

$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$db=new PDO( DB_DSN, DB_LOGIN, DB_PASS, $opt );

$address_get = $db->query('SELECT * FROM addressList');
$address = $address_get->fetchAll(PDO::FETCH_ASSOC);

foreach($address as $key)
{
    $address_name = $key['address'];
    $data = $key['text'];
    $id_class = $key['id_class'];
    $id_or_class_flag = $key['id_or_class_flag'];


    set_error_handler(
        create_function(
            '$severity, $message, $file, $line',
            'throw new ErrorException($message, $severity, $severity, $file, $line);'
        )
    );
    try
    {
        $html = file_get_html($address_name);
    } catch (Exception $e) {
        echo "can't find address";
        exit;
    }
    restore_error_handler();

    if($id_or_class_flag === '1')
    {
        if (count($html->find("#$id_class"))) {
            foreach ($html->find("#$id_class") as $div) {
                $get = $div->plaintext;
            }
        }
    }
    elseif($id_or_class_flag === '2')
    {
        if (count($html->find(".$id_class")))
        {
            foreach ($html->find(".$id_class") as $div)
            {
                $get = $div->plaintext;
            }
        }
    }
    else
    {
        $get = $html;
    }

    preg_match_all("#(.*?)($data)(.*?)#", $get, $match);

    if (!empty($match[2][0]) or $get == $data)
    {
        $flag = 1;
        $response = 'ok';
    }
    else
    {
        $flag = 0;
        $response = 'error';
    }
    try
    {
        //для проверки есть ли такой лог уже с флагом 0 or 1 и если есть то не записываем
        $execute = $db->prepare("SELECT log_name FROM logs WHERE log_name=:log_name AND flag=:flag");
        $execute->execute(array(':log_name' =>  $data, 'flag' => $flag));
        $row = $execute->fetch(PDO::FETCH_ASSOC);

        if ($row == false)
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
            
            $execute = $db->prepare("INSERT INTO logs (host_name, log_name, flag, time_log) VALUES (:host_name, :log_name, :flag, :time_log)");
            $execute->execute(array(':host_name' => $address_name, ':log_name' => $data, 'flag' => $flag, ':time_log' => $time));
        }

        if($flag == 0)
        {
            $to= "@example.com" . ", " ;
            $to .= "@example.com";
            $subject = "search Error";
            $message = "Can't find text: $data, from address: $address_name ";
            $message = wordwrap($message, 70);

            mail($to, $subject, $message);
        }
    }
    catch(Exception $e)
    {
        echo "Can't find or create in db".$e;
    }
    echo $response;
}
?>
