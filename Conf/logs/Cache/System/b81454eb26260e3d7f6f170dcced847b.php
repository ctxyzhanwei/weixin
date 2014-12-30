<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo RES;?>/images/main.css" type="text/css" rel="stylesheet">
<script src="<?php echo STATICS;?>/jquery-1.4.2.min.js" type="text/javascript"></script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
</head>
<body class="warp">
<div id="artlist" class="addn">
<?php if(($info["id"]) > "0"): ?><script src="./tpl/User/default/common/js/date/WdatePicker.js"></script>
			<form action="<?php echo U('Users/edit');?>" method="post" name="form" id="myform">
			<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
		<?php else: ?>
			<form action="<?php echo U('Users/add');?>" method="post" name="form" id="myform"><?php endif; ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" id="addn">

				 <tr>
					<th colspan="4"><?php echo ($title); ?></th>
				</tr>
				<tr>
					<td height="48" align="right"><strong>客户名称：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="username" class="ipt" size="45" value="<?php echo ($info["username"]); ?>" <?php if(($info["username"]) == "admin"): ?>readonly="readonly"<?php endif; ?>>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>手机号：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="mp" class="ipt" size="45" value="<?php echo ($info["mp"]); ?>" />
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>邮箱：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="email" class="ipt" size="45" value="<?php echo ($info["email"]); ?>" />
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>密　　码：</strong></td>
					<td colspan="3" class="lt">
						<input type="password" name="password" class="ipt" size="45" value="">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>确认密码：</strong></td>
					<td colspan="3" class="lt">
						<input type="password" name="repassword" class="ipt" size="45"/>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>分配套餐：</strong></td>
					<td colspan="3" class="lt">
						<select name="gid" style="width:136px">
							<?php if(is_array($role)): $i = 0; $__LIST__ = $role;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $info["gid"]): ?>selected=""<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>所属行业：</strong></td>
					<td>
                            <select name="business">
                                <?php if(is_array($business)): $i = 0; $__LIST__ = $business;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$business): $mod = ($i % 2 );++$i;?><option value="<?php echo ($business["key"]); ?>" <?php if($business['key'] == $info['business']): ?>selected<?php endif; ?>><?php echo ($business["val"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>客户状态：</strong></td>
					<td colspan="3" class="lt">
						<input type="radio" class="radio" class="ipt" value="1" name="status" id="status1" <?php if(($info["status"] == 1) OR ($info['status'] == '') ): ?>checked=""<?php endif; ?> >
							启用
							<input type="radio" class="radio" class="ipt"  value="0" name="status" id="status2" <?php if(($info["status"]) == "0"): ?>checked=""<?php endif; ?> >
							关闭
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>公众号已创建数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="wechat_card_num" class="ipt" size="45" value="<?php echo ($info["wechat_card_num"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>会员卡数量：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="card_num" class="ipt" size="45" value="<?php echo ($info["card_num"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>活动创建数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="activitynum" class="ipt" size="45" value="<?php echo ($info["activitynum"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>资金余额：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="moneybalance" class="ipt" size="45" value="<?php echo ($info["moneybalance"]); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>已充值总数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="spend" class="ipt" size="45" value="<?php echo ($info["spend"]); ?>">
					</td>
				</tr>				
				<tr>				
					<td height="48" align="right"><strong>附件大小：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="attachmentsize" class="ipt" size="45" value="<?php echo $info['attachmentsize'];?>"> <?php echo $info['attachmentsize']/(1024*1024);?> M
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>短信数：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="smscount" class="ipt" size="45" value="<?php echo $info['smscount'];?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>到期时间：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="viptime" onClick="WdatePicker()" class="ipt" size="45" value="<?php echo (date("Y-m-d",$info["viptime"])); ?>">
					</td>
				</tr>
				<tr>
					<td height="48" align="right"><strong>备注说明：</strong></td>
					<td colspan="3" class="lt">
						<input type="text" name="remark" class="ipt" size="45" value="<?php echo ($info["remark"]); ?>"/>
					</td>
				</tr>
				
				<tr>
					<td height="48" align="right"><strong>邀请列表：</strong></td>
					<td colspan="3" class="lt">
					<a href="?g=System&m=Users&a=index&inviter=<?php echo ($info["id"]); ?>">点击查看邀请列表（共<?php echo ($inviteCount); ?>人）</a>
					</td>
				</tr>
	<tr>
		<td colspan="4">
			<?php if(($info["id"]) > "0"): ?><input class="bginput" type="submit" name="dosubmit" value="修 改" >
				<?php else: ?>
				<input class="bginput" type="submit" name="dosubmit" value="添 加"><?php endif; ?>
			&nbsp;
			<input class="bginput" type="button" onclick="javascript:history.back(-1);" value="返 回" ></td>
	</tr>
</table>
</form>
<br />
<br />
<br />

</div>
</body>
</html>