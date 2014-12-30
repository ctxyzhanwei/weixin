<?php
define('BETA',1);
if(PHP_VERSION > '5.1'){
    @date_default_timezone_set('Asia/Shanghai');
}
/************************MySQL settings**************************/
$dbConfigFile=$_SERVER['DOCUMENT_ROOT'].'/PigData/conf/db.php';
if (file_exists($dbConfigFile)){
	$dbConfig=include $dbConfigFile;
}else {
	$dbConfig=include $_SERVER['DOCUMENT_ROOT'].'/Conf/db.php';
}
define('DB_HOSTNAME',$dbConfig['DB_HOST']);//
define('DB_PORT',$dbConfig['DB_PORT']);//
define('DB_USER',$dbConfig['DB_USER']);//
define('DB_PASSWORD',$dbConfig['DB_PWD']);
define('DB_NAME',$dbConfig['DB_NAME']);
define('DB_CHARSET','utf8');
define('CHARSET','utf-8');
define('TABLE_PREFIX',$dbConfig['DB_PREFIX']);
define('MANAGE_DIR','manage');
define('PIGCMS_URL','');
?>