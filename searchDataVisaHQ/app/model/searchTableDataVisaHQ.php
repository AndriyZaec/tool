<?php

define("DB_HOST", "localhost");
define( "DB_LOGIN", "root" );
define( "DB_PASS", "charok" );
mysql_connect(DB_HOST, DB_LOGIN, DB_PASS) or die(mysql_error());
mysql_query("SET NAMES utf8");
$sql='CREATE DATABASE IF NOT EXISTS searchVisaHQ';
mysql_query($sql) or die(mysql_error());
mysql_select_db('searchVisaHQ') or die(mysql_error());

$sql="CREATE TABLE IF NOT EXISTS logs (
   id int(11) NOT NULL AUTO_INCREMENT,
   host_name VARCHAR(255) NOT NULL,
   log_name VARCHAR(255) NOT NULL,
   flag int(11) NOT NULL,
   time_log int(11) NOT NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8";
mysql_query($sql) or die(mysql_error());
print '<p>Data table addressList created!</p>';

$sql="CREATE TABLE IF NOT EXISTS addressList(
   id int(11) NOT NULL AUTO_INCREMENT,
   address VARCHAR(255) NOT NULL,
   text VARCHAR(255) NOT NULL,
   id_class VARCHAR(255) NULL,
   id_or_class_flag int(11) DEFAULT 0,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8";

mysql_query($sql) or die(mysql_error());
print '<p>Data table logs created!</p>';

mysql_close();

?>