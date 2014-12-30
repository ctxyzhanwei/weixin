<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<script src="<?php echo STATICS;?>/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo STATICS;?>/function.js" type="text/javascript"></script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body class="warp">
<div id="artlist">
	<div class="mod kjnav">
		<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U($action.'/'.$vo['name'],array('pid'=>$_GET['pid'],'level'=>3,'title'=>urlencode ($vo['title'])));?>"><?php echo ($vo['title']); ?></a>
		<?php if(($action == 'Article') or ($action == 'Img') or ($action == 'Text') or ($action == 'Voiceresponse')): break; endif; endforeach; endif; else: echo "" ;endif; ?>
	</div>   	
</div>
<div class="cr"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="alist">
	  <tr>
		<td width="70">ID</td>
		<td width="150">关键词</td>
		<td width="100">微信公众号</td>
		<td width="150">内容</td>
		<td width="150">发布时间</td>
		<td width="150">更新时间</td>
		<td width="150">发布用户</td>
		<td width="100">管理操作</td>
	  </tr>
	    <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td align='center'><?php echo ($vo["id"]); ?></td>
				<td align='center'><?php echo ($vo["keyword"]); ?></td>
				<td align='center'><?php if($vo['wxname'] == null): ?>微信已删除<?php else: echo ($vo["wxname"]); endif; ?></td>
				<td align='center'><?php echo (iconv_substr($vo["text"],0,20,'utf-8')); ?></td>
				<td align='center'><?php echo date('Y-m-d', $vo['createtime']) ?></td>
				<td align='center'><?php echo date('Y-m-d', $vo['updatetime']) ?></td>
				<td align='center'><?php echo $vo['uname'] ?></td>
				</td>
				<td align='center'>
					<a target="ddd" href="<?php echo U('Wap/Index/content',array('token'=>$vo['token'],'id'=>$vo['id']));?>">查看</a> |
					<a href="<?php echo U('Text/del/',array('id'=>$vo['id'],'pid'=>$_GET['pid']));?>">删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
     <tr bgcolor="#FFFFFF"> 
      <td colspan="8"><div class="listpage"><?php echo ($page); ?></div></td>
    </tr>
   
</table>
<!--
<div class="bottom">
<span><b>选择：</b><a href="#">全选</a> <a href="#">反选</a> <a href="#">不选</a></span>
<span><b>属性设定：</b><select><option value="">正常</option></select></span>
<span><b>取消属性：</b><select><option value="">推荐</option></select></span>
<span><b>批量转移：</b> 由 <select><option value="">栏目名称</option></select> 移动到 <select><option value="">栏目名称2</option></select></span>
</div>-->

</body>
</html>