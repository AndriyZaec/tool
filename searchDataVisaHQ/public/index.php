<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.05.15
 * Time: 15:02
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
require_once(APP.'model/db_connect.php');
require_once(APP.'controller/mvc_controller.php');


$connect = new Model();

if(!isset($_SERVER['REQUEST_METHOD']) or $_SERVER['REQUEST_URI']=='/searchDataVisaHQ/')
{
    $controller = new Controller();
    $controller->index();
}

if(isset($_SERVER['REQUEST_METHOD']))
{
    $array = explode('/', $_SERVER['REQUEST_URI']);

    $controller = new Controller();

    if (!empty($array[4]) )
    {
        @list($method, $get) = explode('?', $array[4]);
        $check=$method;
    }
       $ar=$array[3];
       if($ar=='public' && !isset($method))
       {
           $check='index';
       }
       try {
           $controller->$check();
        }
       catch(Exception $e) {
           echo "check path $check";
       }
}