<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.05.15
 * Time: 13:38
 */
class Controller
{
    /*
     * главная страница
     */
    public function index()
    {
        $domains=file_get_contents('files/domains.txt');
        $pages=file_get_contents('files/pages.txt');
        $domains=trim($domains);
        $pages=trim($pages);
        for ($i=0;$i<=2;$i++){
            $robot=file_get_contents('files/robotsFiles/test_robots'.$i.'.txt');
            $robot=trim($robot);
            $robots[$i]=$robot;
            $checkDomain=file_get_contents('files/robotsFiles/test_domains'.$i.'.txt');
            $checkDomain=trim($checkDomain);
            $checkDomains[$i]=$checkDomain;
        }
//        $db=new PDO( DB_DSN, DB_LOGIN, DB_PASS, Model::$checkPDO );
//        //$log_get = $db->query('SELECT * FROM logs ORDER BY id DESC');
//        $log_get = $db->query('SELECT * FROM logs WHERE flag=0 ORDER BY id DESC');
//        $logs = $log_get->fetchAll(PDO::FETCH_ASSOC);
//        $date = new \DateTime();
//        $address_get = $db->query('SELECT * FROM addressList');
//        $address = $address_get->fetchAll(PDO::FETCH_ASSOC);
        require('../app/view/searchFormDataVisaHQ.php');
        //require('../searchDataVisaHQ/app/view/searchFormDataVisaHQ.php');
    }

    /*
     * проверка данных по адресу
     */
    public function searchDataVisaHQ()
    {
        $db=new PDO( DB_DSN, DB_LOGIN, DB_PASS );

        if($_REQUEST['address']!='default')
        {
                $execute = $db->prepare("SELECT * FROM addressList WHERE id=:id");
                $execute->execute(array(':id' => $_REQUEST['address']));
                $row = $execute->fetch(PDO::FETCH_ASSOC);
                $address = $row['address'];
                $data = ($row['text']);
        }
        else
        {
            if ($_REQUEST['data'])
            {
                $data = ($_REQUEST['data']);
                //$data = iconv("UTF-8", "windows-1251", quotemeta($_REQUEST['data']));
            } else {
                $return = 'Введите данные для поиска';
                echo $return;
                exit;
            }
            if (empty($_REQUEST['host']) or !isset($_REQUEST['host']))
            {
                $return = 'Введите место поиска';
                echo $return;
                exit;
            }
            $address = $_REQUEST['host'];
        }

        if($file = file_get_contents($address)) {
            //preg_match_all("#\<div class=\"footer\_lefttop\"\>\s+(.*?)($data)(.*?)\s+\<\/div\>#", $file, $match);
            preg_match_all("#(.*?)($data)(.*?)#", $file, $match);

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
            //$date->setTimestamp($time);
            // var_dump($date->format('Y-m-d H:i:s'));


            /* set_error_handler(
                 create_function(
                     '$severity, $message, $file, $line',
                     'throw new ErrorException($message, $severity, $severity, $file, $line);'
                 )
             );
             try
             {
                 $file = file_get_contents($address);
                 //preg_match_all("#\<div class=\"footer\_lefttop\"\>\s+(.*?)($data)(.*?)\s+\<\/div\>#", $file, $match);
                 preg_match_all("#(.*?)($data)(.*?)#", $file, $match);
             } catch (Exception $e) {
                 echo "can't find address";
                 exit;
             }
             restore_error_handler();*/

        // preg_match_all("#(.*?)class=\"$id_class\"(.*?)($data)(.*?)#", $file, $match);

            if (!empty($match[2][0])) {
                $flag = 1;
                $response = 'ok';
            } else {
                $flag = 0;
                $response = 'error';
            }
            try {
                //для проверки есть ли такой лог уже с флагом 0 or 1 и если есть то не записываем
                $execute = $db->prepare("SELECT log_name FROM logs WHERE log_name=:log_name AND flag=:flag");
                $execute->execute(array(':log_name' => $data, 'flag' => $flag));
                $row = $execute->fetch(PDO::FETCH_ASSOC);

                if ($row == false) {
                    $execute = $db->prepare("INSERT INTO logs (host_name, log_name, flag, time_log) VALUES (:host_name, :log_name, :flag, :time_log)");
                    $execute->execute(array(':host_name' => $address, ':log_name' => $data, 'flag' => $flag, ':time_log' => $time));
                }
            } catch (Exception $e) {
                echo "Can't find or create in db" . $e;
            }
            /*
    $write_log = $_REQUEST['data'] . '|' . $time . '|||';
    $fp = fopen("logs.txt", "a+");
    fwrite($fp, $write_log);
    fclose($fp);
    */
            echo $response;
        }
        else
        {
            echo "can't find address";
        }
    }


    /*
     * переходим на страницу адресов
     */
    public function addressCheck()
    {
        $db=new PDO( DB_DSN, DB_LOGIN, DB_PASS);

        $address_get = $db->query('SELECT * FROM addressList');
        $address = $address_get->fetchAll(PDO::FETCH_ASSOC);

        require('../view/CheckAddressTable.php');
    }

    /*
     * Записываем новую запись введенную менеджером в таблицу адресов
     */
    public function record()
    {
        if (isset($_REQUEST['address']) && $_REQUEST['text'])
        {

            $db = new PDO(DB_DSN, DB_LOGIN, DB_PASS);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_REQUEST['id_or_class_flag'] != 'default' && ($_REQUEST['id_class'] == "" or !isset($_REQUEST['id_class'])))
            {
                echo 'Please enter id/class data';
                exit;
            }
            elseif ($_REQUEST['id_or_class_flag'] == 'default' && ($_REQUEST['id_class'] != ""))
            {
                echo 'Please enter id or class';
                exit;
            }
            elseif ($_REQUEST['id_or_class_flag'] == 'default' && ($_REQUEST['id_class'] == "" or !isset($_REQUEST['id_class'])))
            {
                $id_or_class_flag = '';
                $id_class = 0;
            }
            else
            {
                $id_or_class_flag = $_REQUEST['id_or_class_flag'];
                $id_class = $_REQUEST['id_class'];
            }

            try {
                $execute = $db->prepare("INSERT INTO addressList (address, text, id_or_class_flag, id_class) VALUES (:address, :text, :id_or_class_flag, :id_class)");
                $execute->execute(array(':address' => $_REQUEST['address'], ':text' => $_REQUEST['text'],
                    ':id_or_class_flag' => $id_or_class_flag, ':id_class' => $id_class));
                echo 'ok';
                //require('../app/view/searchFormDataVisaHQ.php');
                header("Location: searchDataVisaHQ");
            } catch (Exception $e) {
                echo "Error $e";
            }
        }
        else
        {
            echo 'Please enter address and search data';
        }
    }

    /*
     * изменяем введенную запись в таблице адресов
     */
    public function changeAddress()
    {
        if (isset($_REQUEST['address']) && $_REQUEST['text'])
        {
            if ($_REQUEST['id_or_class_flag'] == 'default' && ($_REQUEST['id_class'] != ""))
            {
                echo 'Please enter id or class';
                exit;
            }
            $db = new PDO(DB_DSN, DB_LOGIN, DB_PASS);
            $execute = $db->prepare("UPDATE addressList SET address =:address, text =:text, id_or_class_flag =:id_or_class_flag, id_class =:id_class WHERE id =:id");
            $execute->execute(array(':address' => $_REQUEST['address'], ':text' => $_REQUEST['text'],
                ':id_or_class_flag' => $_REQUEST['id_or_class_flag'], ':id_class' => $_REQUEST['id_class'], ':id' => $_REQUEST['id']));
            echo 'ok';
            header("Location: addressCheck");
        }
        else
        {
            echo 'Please enter address and data';
        }
    }

    /*
     * удаляем ненужные адреса
     */
    public function deleteDataAddress()
    {
        $db=new PDO( DB_DSN, DB_LOGIN, DB_PASS);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $delete = $db->prepare('DELETE FROM addressList WHERE id=:id');
            $delete->execute(array(':id' => $_REQUEST['id']));
            //echo 'ok';
            //require('../app/view/searchFormDataVisaHQ.php');
            header("Location: addressCheck");
        }
        catch(Exception $e)
        {
            echo 'error';
        }
    }
    /*
     * удаляем из логов старые записи
     */
    public function deleteData()
    {
        $db=new PDO( DB_DSN, DB_LOGIN, DB_PASS);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $delete = $db->prepare('DELETE FROM logs WHERE id=:id');
            $delete->execute(array(':id' => $_REQUEST['id']));
            //echo 'ok';
            //require('../app/view/searchFormDataVisaHQ.php');
            header("Location: searchDataVisaHQ");
        }
        catch(Exception $e)
        {
            echo 'error';
        }
    }
    public function searchDuplicateTags(){
    }

}