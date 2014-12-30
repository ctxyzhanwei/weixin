<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>添加用户级</title>
	<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
	<script src="<?php echo STATICS;?>/jquery-1.4.2.min.js" type="text/javascript"></script>
	<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body class="warp">
<div id="artlist" class="addn">
<?php if(($info["id"]) > "0"): ?><form action="<?php echo U('User_group/edit');?>" method="post" name="form" id="myform">
			<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
		<?php else: ?>
			<form action="<?php echo U('User_group/add');?>" method="post" name="form" id="myform"><?php endif; ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="addn">
				
				 <tr>
					<th colspan="4"><?php echo ($title); ?></th>
				</tr>
				<tr>
					<td height="48" align="right"><strong>用户状态：</strong></td>
					<td colspan="3" class="lt">
						<input type="radio" class="radio" class="ipt" value="1" name="status" id="status1" <?php if(($info["status"] == 1) OR ($info['status'] == '') ): ?>checked=""<?php endif; ?> >
							启用
							<input type="radio" class="radio" class="ipt"  value="0" name="status" id="status2" <?php if(($info["status"]) == "0"): ?>checked=""<?php endif; ?> >
							关闭
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>是否显示版权:</strong></td>
					<td colspan="3" class="lt">
						<input type="radio" class="radio" class="ipt" value="1" name="iscopyright" id="status1" <?php if(($info["iscopyright"] == 1) OR ($info['iscopyright'] == '') ): ?>checked=""<?php endif; ?> >
							启用
							<input type="radio" class="radio" class="ipt"  value="0" name="iscopyright" id="status2" <?php if(($info["iscopyright"]) == "0"): ?>checked=""<?php endif; ?> >
							关闭
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>用户组名称：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="name" class="ipt" size="45" value="<?php echo ($info["name"]); ?>" <?php if(($info["username"]) == "admin"): ?>readonly="readonly"<?php endif; ?>>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>公众号数量：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="wechat_card_num" value="<?php echo ($info["wechat_card_num"]); ?>" class="ipt" size="45"/>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>自定义图文条数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="diynum" value="<?php echo ($info["diynum"]); ?>" class="ipt" size="45"/>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>功能请求次数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="connectnum" value="<?php echo ($info["connectnum"]); ?>" class="ipt" size="45"/>
						</select>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>活动创建次数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="activitynum"  value="<?php echo ($info["activitynum"]); ?>" class="ipt" size="45"/>
						</select>
					</td>
				</tr>
				
				<tr>
					<td height="48" align="right"><strong>包月价格：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="price" class="ipt" size="45" value="<?php echo ($info["price"]); ?>"/>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>创建会员卡数量：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="create_card_num" class="ipt" size="45" value="<?php echo ($info["create_card_num"]); ?>"/>
					</td>
				</tr>
				<tr>
					<td height="100" align="right"><strong>分配功能模块</strong></td>
					<td colspan="3" class="lt">
								<input type="checkbox" id="checkall" onclick="selectAll(this);" /><label for="checkall"> 全选 / 全不选 </label>
								<table style="padding:5px;" cellpadding="10" cellspacing="8">
									<?php if(is_array($func)): $i = 0; $__LIST__ = $func;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$f): $mod = ($i % 2 );++$i; if($i%10 == 1): ?><tr><?php endif; ?>
										<td>
											<input type="checkbox" style="cursor:pointer" name="func[]" value="<?php echo ($f["funname"]); ?>" id="func_<?php echo ($f["id"]); ?>" <?php if(in_array(($f['funname']), is_array($info['func'])?$info['func']:explode(',',$info['func']))): ?>checked<?php endif; ?> /> 
											<label for="func_<?php echo ($f["id"]); ?>" style="cursor:pointer"><?php echo ($f["name"]); ?></label> 
										</td>
										<?php if($i%10 == 0 || $i == count($func)): ?></tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
								</table>
																
								</div>
						
					</td>
				</tr>
				
<script>

	function selectAll(checkbox) { 
		$('input[type=checkbox]').attr('checked', $(checkbox).attr('checked')); 
	} 

</script>				
				
				
	<tr>
		<td colspan="4" style="padding:10px 0 10px 230px;">
			<?php if(($info["id"]) > "0"): ?><button class="button" type="submit"  value="" >修 改</button>
				<?php else: ?>
				<button class="button" type="submit" value="">添 加</button><?php endif; ?>
			&nbsp;
			<button class="button" type="button" onclick="javascript:history.back(-1);" value="" >返 回</button></td>
	</tr>
</table>
</form>
<br />
<br />
<br />

</div>
</body>
</html>