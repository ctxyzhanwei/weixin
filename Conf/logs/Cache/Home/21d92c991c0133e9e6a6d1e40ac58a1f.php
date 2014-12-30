<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>资费说明－<?php echo ($f_siteTitle); ?></title>
<meta name="keywords" content="微信帮手 微信公众账号 微信公众平台 微信公众账号开发 微信二次开发 微信接口开发 微信托管服务 微信营销 微信公众平台接口开发"/>
<meta name="description" content="微信公众平台接口开发、托管、营销活动、二次开发"/>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/index.css"/>
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/ie6.css"/>
<![endif]-->
<script type="text/javascript">window.onerror=function(){return true;}</script>
<script type="text/javascript" src="<?php echo RES;?>/js/lang.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/config.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/page.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/js/jquery.lazyload.js"></script>
<script type="text/javascript">
GoMobile('');
var searchid = 5;
</script>
</head>
<body>
<div class="topbg">
<div class="top">
<div class="toplink">
<div class="welcome">欢迎使用<?php echo ($f_siteTitle); ?> - <?php echo ($f_siteName); ?></div>
    <div class="memberinfo"  id="destoon_member">	
		<?php if($_SESSION[uid]==false): ?>你好&nbsp;&nbsp;<span class="f_red">游客</span>&nbsp;&nbsp;
			<a href="<?php echo U('Index/login');?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href="<?php echo U('Index/login');?>">注册</a>
			<?php else: ?>
			你好,<a href="/#" hidefocus="true"  ><span style="color:red"><?php echo (session('uname')); ?></span></a>（uid:<?php echo (session('uid')); ?>）
			<a href="/#" onClick="Javascript:window.open('<?php echo U('System/Admin/logout');?>','_blank')" >退出</a><?php endif; ?>	
	</div>
</div>
    <div class="logo">
        <div style="float:left"></div>
            <h1><a href="/" title="<?php echo ($f_siteName); ?>"><img src="<?php echo ($f_logo); ?>" /></a></h1>
            <div class="navr">
        <ul id="topMenu">           
			 <li <?php if((ACTION_NAME == 'index') and (GROUP_NAME == 'Home')): ?>class="menuon"<?php endif; ?> ><a href="/" >首页</a></li>
                <li <?php if((ACTION_NAME) == "fc"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/fc');?>">功能介绍</a></li>
                <!--<li <?php if((ACTION_NAME) == "about"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/about');?>">关于我们</a></li>-->
                <li <?php if((ACTION_NAME) == "price"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/price');?>">资费说明</a></li>
                <li <?php if((ACTION_NAME) == "common"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/common');?>">微信导航</a></li>
                <li <?php if((GROUP_NAME) == "User"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('User/Index/index');?>">管理中心</a></li>
                <li <?php if((ACTION_NAME) == "help"): ?>class="menuon"<?php endif; ?>><a href="/agent.php">代理管理</a></li>
                <li <?php if((ACTION_NAME) == "help"): ?>class="menuon"<?php endif; ?>><a href="<?php echo U('Home/Index/help');?>">帮助中心</a></li>
            
            </ul>
        </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style-price.css"/>
<div class="banner jbanner"></div>
<div class="main">
   <div class="pos">&nbsp;&nbsp;&nbsp;当前位置: <a href="<?php echo ($f_siteUrl); ?>"><?php echo ($f_siteName); ?></a> &raquo; <a href="<?php echo ($f_siteUrl); ?>">资费说明</a></div>
<div class="abody" style="margin-top:0;padding-top:0">
             <div class="qtcontent">
        <div class="document faq" style="margin-top:0;padding-top:0">
            <div class="normalTitle"><h2>资费</h2></div>
            <div class="normalContent">
                <div class="section lastSection">
                	<table width="100%" border="0" cellpadding="0" cellspacing="0" class=" ListProduct8">
<thead>
                			<tr>
                				<th class="lefttitle"><strong>微信号流量套餐</strong></th>
                				<?php if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><th width="100" <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g["name"]); ?></th><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
</thead>
<tbody>
                			<tr>
                				<td height="60" valign="middle" class="lefttitle">VIP价格
                					<a  class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>VIP只是流量套餐！</p>
</span></a></td>
<?php if(is_array($prices)): $i = 0; $__LIST__ = $prices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><span class="price"><?php echo ($g); ?><p>元 / 月</p></span></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
               				<tr>
                				<td height="33" valign="middle" class="lefttitle">允许创建公众号数量
                					<a  class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>最多允许创建公众号的数量</p>
</span></a></td>
<?php if(is_array($wechatNums)): $i = 0; $__LIST__ = $wechatNums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                			<tr>
                				<td height="33" valign="middle" class="lefttitle">自定义图文条数
                					<a  class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>每个月可以创建的图文回复数量</p>
</span></a></td>
<?php if(is_array($diynums)): $i = 0; $__LIST__ = $diynums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                			<tr>
                				<td height="33" valign="middle" class="lefttitle">请求数
                					<a  class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>每个月可以进行多少次回复请求</p>
</span></a></td>
<?php if(is_array($connectnums)): $i = 0; $__LIST__ = $connectnums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                		
               				</tr>
                            <tr >
                				<td height="33" class="lefttitle">每月活动创建费次数<span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>什么是活动创建数量？</strong></p>
<p>每月允许创建的大转盘等互动活动数量</p>
</span></span></td>
                			<?php if(is_array($activitynums)): $i = 0; $__LIST__ = $activitynums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
               				<tr >
                				<td height="33" class="lefttitle">每月会员卡开卡数量<span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>什么是每月会员卡开卡数量？</strong></p>
<p>每个月允许创建多少张会员卡提供给会员领取</p>
</span></span></td>
                			<?php if(is_array($create_card_nums)): $i = 0; $__LIST__ = $create_card_nums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php echo ($g); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                             <tr >
                				<td height="33" class="lefttitle">自定义版权信息<span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>自定义版权信息？</strong></p>
<p>如果不能自定义，将在微网站底部显示页面有:此页面是由【<a href="<?php echo ($f_siteUrl); ?>"><?php echo ($f_siteName); ?>接口平台</a>】系统生成 版权信息</p>
</span></span></td>
<?php if(is_array($copyrights)): $i = 0; $__LIST__ = $copyrights;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><?php if(g): ?>可以<?php else: ?>不能<?php endif; ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                			<tr >
                				<td height="50" class="lefttitle"> <span class="red">购买VIP套餐</span><span class="tooltips"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><strong>简单购买流程提醒</strong></p>
<p></p>
</span></span></td>
<?php if(is_array($create_card_nums)): $i = 0; $__LIST__ = $create_card_nums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>><a class="btnGreens"  href="<?php echo U('User/Alipay/index',array('gid'=>0));?>"><em>立即充值</em></a></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
                			<tr>
                				<td height="36" class="lefttitle"><strong>功能列表及套餐对比</strong></td>
                				<?php if(is_array($create_card_nums)): $i = 0; $__LIST__ = $create_card_nums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><td <?php if($i == $count): ?>class="norightborder"<?php endif; ?>></td><?php endforeach; endif; else: echo "" ;endif; ?>
               				</tr>
               				<?php if(is_array($funs)): $i = 0; $__LIST__ = $funs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$f): $mod = ($i % 2 );++$i;?><tr>
                				<td class="lefttitle" height="33" valign="middle"><a href="###"><?php echo ($f["name"]); ?></a> <a class="tooltips" href="###"><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" />
<span>
<p><?php echo ($f["info"]); ?></p>
</span></a></td>
<?php  if ($f['access']){ $i=1; foreach ($f['access'] as $v){ ?>
                				<td class="<?php if ($v){echo 'checked';}else{echo 'error';} if ($i==$count){echo ' norightborder';}?>">&nbsp;</td>
 <?php  $i++; } } ?>
                				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
               			</table>
                </div>
            <div class="section lastSection">
<p>有疑问的请QQ<?php echo ($f_qq); ?>提问。</p>
               		</div>
            </div>
        </div>
    </div>
    </div>
    </div>
<script type="text/javascript">try{Dd('webpage_6').className='left_menu_on';}catch(e){}</script>
</div>
<div class="IndexFoot" style="height:120px;">
<div class="foot">
<div class="foot_page">
<a href="/"><?php echo ($f_siteName); ?>,微信公众平台营销</a><br/>
帮助您快速搭建属于自己的营销平台,构建自己的客户群体。<br/>
大转盘、刮刮卡，会员卡,优惠卷,订餐,订房等营销模块,客户易用,易懂,易营销。
</div>
<div id="copyright">
	<?php echo ($f_siteName); ?>(c) 版权所有<br/>
	<a href="http://www.miibeian.gov.cn" target="_blank" style="color:white"><?php echo C('ipc');?></a><br/>
	QQ咨询：<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($f_qq); ?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo ($f_qq); ?>:51" alt="联系我吧" title="联系我吧"/></a>

</div>
    </div>
</div>
<div style="display:none">
<?php echo base64_decode(C('countsz'));?>
</div>
</body>
</html>