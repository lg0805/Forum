<?php 
// set true if production environment else flase for development
define('IS_ENV_PRODUCTION', false);

// configure error reporting options
//error_reporting(E_ALL | E_STRICT);
error_reporting(0);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', 'log/phperror.txt');

// set time zone to use data/time functions without warnings
date_default_timezone_set("PRC");

!isset($_SESSION)?session_start():"";
/*
addslashes(); 				对单引号、双引号、反斜杠进行转义
mysql_real_escape_string()	将数据转义后保存至数据库
stripslashes(); 去除转义
 */
// echo stripslashes(addslashes($_GET['name']));