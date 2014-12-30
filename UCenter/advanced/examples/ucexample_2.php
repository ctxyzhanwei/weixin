<?php
/**
 * UCenter 应用程序开发 Example
 *
 * UCenter 简易应用程序，应用程序有自己的用户表
 * 使用到的接口函数：
 * uc_authcode()	可选，借用用户中心的函数加解密 Cookie
 * uc_pm_checknew()	可选，用于全局判断是否有新短消息，返回 $newpm 变量
 */

//include './config.inc.php';
//数据库主机IP,数据库用户名,数据库密码,数据库名称,数据库字符集默认gbk,数据库表前缀ucenter.uc_
//通信密钥（要与UCenter保持一致）,UCenter的URL地址,UCenter的字符集默认gbk

$get_conf_url = "http://" .$_SERVER['SERVER_NAME']. "/index.php?g=home&a=get_conf_uc_db_web"; 
list($conf_db_str, $conf_web_str) = explode('UC_UC', file_get_contents($get_conf_url));//xxx UC_UC xxx

list($uc_db_host, $uc_db_user, $uc_db_pass, $uc_db_name, $uc_db_char, $uc_db_prix) = explode(',', $conf_db_str);
/*$uc_db_host = 'localhost';//localhost
$uc_db_user = 'root';//root
$uc_db_pass = '';
$uc_db_name = 'ucenter';
$uc_db_char = 'gbk';//gbk
$uc_db_prix = 'ucenter.uc_';//ucenter.uc_*/

list($uc_web_key, $uc_web_url, $uc_web_gbk) = explode(',', $conf_web_str);
/*$uc_web_key = '123456789';//123456789
$uc_web_url = 'http://localhost/ucenter/upload';//http://localhost/ucenter/upload
$uc_web_gbk = 'gbk';//gbk*/

define('UC_CONNECT', 'mysql');				// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
							// mysql 是直接连接的数据库, 为了效率, 建议采用 mysql

//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
define('UC_DBHOST', $uc_db_host);			// UCenter 数据库主机
define('UC_DBUSER', $uc_db_user);				// UCenter 数据库用户名
define('UC_DBPW', $uc_db_pass);					// UCenter 数据库密码
define('UC_DBNAME', $uc_db_name);				// UCenter 数据库名称
define('UC_DBCHARSET', $uc_db_char);				// UCenter 数据库字符集
define('UC_DBTABLEPRE', $uc_db_prix);			// UCenter 数据库表前缀

//通信相关
define('UC_KEY', $uc_web_key);				// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', $uc_web_url);	// UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', $uc_web_gbk);				// UCenter 的字符集
define('UC_IP', '');					// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', 1);					// 当前应用的 ID

//ucexample_2.php 用到的应用程序数据库连接参数
$dbhost = 'localhost';			// 数据库服务器
$dbuser = 'root';			// 数据库用户名
$dbpw = '';				// 数据库密码
$dbname = 'ucenter';			// 数据库名
$pconnect = 0;				// 数据库持久连接 0=关闭, 1=打开
$tablepre = $uc_db_prix;//'example_';   		// 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = 'gbk';			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定

//同步登录 Cookie 设置
$cookiedomain = ''; 			// cookie 作用域
$cookiepath = '/';			// cookie 作用路径


/**
 * 连接数据库

 用户表样例
 CREATE TABLE `example_members` (
   `uid` int(11) NOT NULL COMMENT 'UID',
   `username` char(15) default NULL COMMENT '用户名',
   `admin` tinyint(1) default NULL COMMENT '是否为管理员',
   PRIMARY KEY  (`uid`)
 ) TYPE=MyISAM;

 */

include './include/db_mysql.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

include './uc_client/client.php';

/**
 * 获取当前用户的 UID 和 用户名
 * Cookie 解密直接用 uc_authcode 函数，用户使用自己的函数
 */
if(!empty($_COOKIE['Example_auth'])) {
	list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
} else {
	$Example_uid = $Example_username = '';
}

/**
 * 获取最新短消息
 */
$newpm = uc_pm_checknew($Example_uid);

//处理微信平台的调用
if ($_GET['from_weixin_url']) {
	$username = $_GET['username'];
	$password = $_GET['password'];
	$email    = $_GET['email'];
	
	$uid = uc_user_register($username, $password, $email);
	if($uid <= 0) {
			if($uid == -1) {
				echo '用户名不合法';
			} elseif($uid == -2) {
				echo '包含要允许注册的词语';
			} elseif($uid == -3) {
				echo '用户名已经存在';
			} elseif($uid == -4) {
				echo 'Email 格式有误';
			} elseif($uid == -5) {
				echo 'Email 不允许注册';
			} elseif($uid == -6) {
				echo '该 Email 已经被注册';
			} else {
				echo '未定义';
			}
			exit;
	}
	$db->query("INSERT INTO {$tablepre}members (uid,username,admin) VALUES ('$uid','$username','0')");
	//注册成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
	setcookie('Example_auth', uc_authcode($uid."\t".$username, 'ENCODE'));	
	echo 1;exit;
}


/**
 * 各个功能的 Example 代码
 */
switch(@$_GET['example']) {
	case 'login':
		//UCenter 用户登录的 Example 代码
		include 'code/login_db.php';
	break;
	case 'logout':
		//UCenter 用户退出的 Example 代码
		include 'code/logout.php';
	break;
	case 'register':
		//UCenter 用户注册的 Example 代码
		include 'code/register_db.php';
	break;
	case 'pmlist':
		//UCenter 未读短消息列表的 Example 代码
		include 'code/pmlist.php';
	break;
	case 'pmwin':
		//UCenter 短消息中心的 Example 代码
		include 'code/pmwin.php';
	break;
	case 'friend':
		//UCenter 好友的 Example 代码
		include 'code/friend.php';
	break;
	case 'avatar':
		//UCenter 设置头像的 Example 代码
		include 'code/avatar.php';
	break;
}

echo '<hr />';
if(!$Example_username) {
	//用户未登录
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=login">登录</a> ';
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=register">注册</a> ';
} else {
	//用户已登录
	echo '<script src="ucexample.js"></script><div id="append_parent"></div>';
	echo $Example_username.' <a href="'.$_SERVER['PHP_SELF'].'?example=logout">退出</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=pmlist">短消息列表</a> ';
	echo $newpm ? '<font color="red">New!('.$newpm.')</font> ' : NULL;
	echo '<a href="###" onclick="pmwin(\'open\')">进入短消息中心</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=friend">好友</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=avatar">头像</a> ';
}

?>