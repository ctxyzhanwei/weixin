<?php
/**
 *  background access
 *
 */
 //root dir
define('ABS_PATH', dirname(__FILE__).'/');
include './config/config.inc.php';
include './config/safe3.php';
include './'.MANAGE_DIR.'/base.php';
if (!isset($_GET['m'])){
	define('ROUTE_MODEL', 'site');
	define('ROUTE_CONTROL', 'home');
	define('ROUTE_ACTION', 'home');
}
bpBase::creatApp();
?>