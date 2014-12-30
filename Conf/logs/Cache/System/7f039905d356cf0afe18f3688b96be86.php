<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加功能</title>
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<script src="<?php echo STATICS;?>/jquery-1.4.2.min.js" type="text/javascript"></script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body class="warp">
<div id="artlist" class="addn">
		<?php if(($info["id"]) > "0"): ?><form action="<?php echo U('Function/edit');?>" method="post" name="form" id="myform">
			<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
		<?php else: ?>
			<form action="<?php echo U('Function/add');?>" method="post" name="form" id="myform"><?php endif; ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="addn">

				 <tr>
					<th colspan="4"><?php echo ($title); ?></th>
				</tr>
				<tr>
					<td height="48" align="right"><strong>状态：</strong></td>
					<td colspan="3" class="lt">
						<input type="radio" class="radio" class="ipt" value="1" name="status" id="status1" <?php if(($info["status"] == 1) OR ($info['status'] == '') ): ?>checked=""<?php endif; ?> >
							启用
							<input type="radio" class="radio" class="ipt"  value="0" name="status" id="status2" <?php if(($info["status"]) == "0"): ?>checked=""<?php endif; ?> >
							关闭
					</td>
				</tr>
					<tr style="display:none">
					<td height="48" align="right"><strong>模块分类：</strong></td>
					<td colspan="3" class="lt">
						<select name="isserve" style="width:136px;height:30px;">
								<option style="height:30px;" value="1" <?php if(($info["isserve"]) == "1"): ?>selected="selected"<?php endif; ?> >查询模块</option>
								<option style="height:30px;" value="2" <?php if(($info["isserve"]) == "2"): ?>selected="selected"<?php endif; ?> >功能查询</option>
						</select>
					</td>
				</tr>				
				<tr style="display:none">
					<td height="48" align="right"><strong>等级要求：</strong></td>
					<td colspan="3" class="lt">
						<select name="gid" style="width:136px;height:30px;">
							<option value="1">请选择</option>
							<?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option style="height:30px;" value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $info["gid"]): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</td>
				</tr>				
				<tr>
					<td height="48" align="right"><strong>模块名称：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="name" class="ipt" size="45" value="<?php echo ($info["name"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>模块方法名称：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="funname" class="ipt" size="45" value="<?php echo ($info["funname"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>功能说明：</strong></td>
					<td colspan="3" class="lt">
						<textarea type="text" name="info" value="" class="ipt" style="width:450px;height:60px;margin:5px 0 5px 0;"><?php echo ($info["info"]); ?></textarea>
					</td>
				</tr>
	<tr>
		<td colspan="4" style="padding:10px 0 10px 160px;">
			<?php if(($info["id"]) > "0"): ?><button class="button" type="submit" name="" value="" >修 改</button>
				<?php else: ?>
				<button class="button" type="submit" name="" value="">添 加</button><?php endif; ?>
			&nbsp;
			<button class="button" onclick="javascript:history.back(-1);" value="" >返 回</button></td>
	</tr>
</table>
</form>
<br />
<br />
<br />

</div>
</body>
</html>