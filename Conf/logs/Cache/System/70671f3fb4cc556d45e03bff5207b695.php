<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<meta http-equiv="x-ua-compatible" content="ie=7" />
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js" type="text/javascript"></script>
</head>
<body style="background:none">
<div class="content">
<div class="box">
	<h3><?php echo C('site_name');?>更新消息</h3>
    <div class="con dcon">
    <div class="update">
    <p>服务器环境：[<?php echo PHP_OS; ?>]<?php echo $_SERVER[SERVER_SOFTWARE];?> MySql:<?php echo mysql_get_server_info(); ?> php:<?php echo PHP_VERSION; ?></p>

    <p>服务器IP：<?php echo $_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']; ?></p>

    <p>当前网站语言：<?php echo getenv("HTTP_ACCEPT_LANGUAGE"); ?></p>

    <p>被屏蔽的函数：<?php echo get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"无" ; ?></p>

    <p>官方网站：<a href="http://lsxy.taobao.com" class="blue"><?php echo C('site_name');?></a></p>

    <p>系统版本：<?php echo ($ver); ?> <!--<a href="./index.php?g=System&m=Update&a=index" class="blue">检查更新并在线升级</a>--></p>

    </div>

    <ul class="myinfo">

   <li>
     <p class="red">您的程序版本为：<?php echo C('site_name');?>微信微信营销系统 <?php echo ($ver); ?></p>
   </li>

   <li><p><?php echo ($domain_time); ?></p></li>

   <li style="display:none"><p class="red" style="display:none">您的程序版本为：微我微信营销系统v1.0</p><span style="display:none">[ 授权版本：商业版 (终身免费) ]</span></li>

  
	</ul>
    </div>
</div>
<!--/box-->
<div class="box">
	<h3><?php echo C('site_name');?>说明</h3>
    <div class="con dcon">
    <div class="kjnav" style="display:none">
    <a >使用帮助</a><a >技术售后</a><a >安装指导</a><a >联系我们</a>
    </div>
    <ul class="myinfo kjinfo">
   <li class="title">售后服务范围</li>
<li>程序第一次安装指导,或第一次协助安装</li>
<li>服务时间：早9:00 晚10:00 如有疑问请在这个时间段联系我们</li>
<li>您的擅自修改出现的任何问题,将不在售后内('可指导')</li>
	</ul>
    </div>
</div>

<!--/box-->
</div>

</body>
</html>