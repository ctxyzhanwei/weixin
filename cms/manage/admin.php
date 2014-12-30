<?php
/**
 *  background access
 *
 */
 //root dir
define('ABS_PATH', dirname(__FILE__).'/../');
define('IN_BACKGROUND',1);
include ABS_PATH.'./config/config.inc.php';
//
if ($_SERVER['SCRIPT_NAME']!='/'.MANAGE_DIR.'/admin.php'){
	$p=str_replace('/'.MANAGE_DIR.'/admin.php','',$_SERVER['SCRIPT_NAME']);
	$p=str_replace('/','',$p);
	$dir='/'.$p;
}else {
	$dir='';
}
define('CMS_DIR',$p);//程序存放的文件夹名称
define('CMS_DIR_PATH',$dir);
//
include ABS_PATH.'./config/safe3.php';
include ABS_PATH.'./'.MANAGE_DIR.'/base.php';

bpBase::creatApp();
?>