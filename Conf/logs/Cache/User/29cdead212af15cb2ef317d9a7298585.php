<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信公众平台源码,微信机器人源码,微信自动回复源码 PigCms多用户微信营销系统</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style_2_common.css?BPm" />
<script src="<?php echo RES;?>/js/common.js" type="text/javascript"></script>
<link href="<?php echo RES;?>/css/style.css" rel="stylesheet" type="text/css" />
 <script src="<?php echo STATICS;?>/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $apikey;?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/cymain.css" />
 <script src="/tpl/static/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/tpl/static/artDialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/tpl/User/default/common/js/select/js/jquery.js"></script>
<script type="text/javascript" src="/tpl/static/audioplayer/inc/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="/tpl/static/audioplayer/inc/jquery.mb.miniPlayer.js"></script>
    <link rel="stylesheet" type="text/css" href="/tpl/static/audioplayer/css/miniplayer.css" title="style" media="screen"/>
    <script type="text/javascript">
        $(function () {

            $(".audio").mb_miniPlayer({
                width: 200,
                inLine: false,
                onEnd: playNext
            });

            function playNext(player) {
                var players = $(".audio");
                document.playerIDX = player.idx + 1 <= players.length - 1 ? player.idx + 1 : 0;
                players.eq(document.playerIDX).mb_miniPlayer_play();
            }
			

			
			
        });
		
    </script>

</head>
<body style="background:#fff">
<script>
function changeFolder(v){
	window.location.href="?g=User&m=Attachment&a=index&type=<?php echo ($type); ?>&folder="+v;
}
function changeColor(v){
	if(v != 'all'){
		window.location.href="?g=User&m=Attachment&a=index&type=<?php echo ($type); ?>&folder=<?php echo (($_GET['folder'])?($_GET['folder']):'canyin'); ?>&color="+v;
	}else{
		window.location.href="?g=User&m=Attachment&a=index&type=<?php echo ($type); ?>&folder=<?php echo (($_GET['folder'])?($_GET['folder']):'canyin'); ?>";
	}
}
</script>
<!--tab start-->
<div class="tab">
<ul>

<li class="<?php if($type == 'icon'): ?>current<?php endif; ?> tabli" id="tab0"><a href="<?php echo U('Attachment/index',array('type'=>'icon'));?>">图标</a></li>
<li class="<?php if($type == 'focus'): ?>current<?php endif; ?> tabli" id="tab0"><a href="<?php echo U('Attachment/index',array('type'=>'focus'));?>">幻灯片</a></li>
<li class="<?php if($type == 'background'): ?>current<?php endif; ?> tabli" id="tab0"><a href="<?php echo U('Attachment/index',array('type'=>'background'));?>">背景图</a></li>
<li class="<?php if($type == 'music'): ?>current<?php endif; ?> tabli" id="tab0"><a href="<?php echo U('Attachment/index',array('type'=>'music'));?>">音乐</a></li>
<li class="<?php if($type == 'my'): ?>current<?php endif; ?> tabli" id="tab0"><a href="<?php echo U('Attachment/my',array('type'=>'my'));?>">我的素材</a></li>

</ul>
</div>
<!--tab end-->
<div style="margin:10px 20px;">

<div>
<?php
if ($type!='my'){ ?>
<div style="margin-bottom:10px;">
	请选择类别：
	<select onchange="changeFolder(this.value)">
		<?php if(is_array($folders)): $i = 0; $__LIST__ = $folders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$f): $mod = ($i % 2 );++$i;?><option value="<?php echo ($f["folder"]); ?>" <?php if($f["folder"] == $folder): ?>selected<?php endif; ?>><?php echo ($f["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	</select>
	<?php if((in_array($_GET['folder'],$folderArr) || $_GET['folder'] == '') && ($_GET['type'] == 'icon')): ?>&nbsp; &nbsp; &nbsp; 
	<select onchange="changeColor(this.value)" style="width:76px">
		<option value="all">全部颜色</option>
		<option value="red" <?php if($_GET['color'] == 'red'): ?>selected<?php endif; ?>>红</option>
		<option value="orange" <?php if($_GET['color'] == 'orange'): ?>selected<?php endif; ?>>橙</option>
		<option value="yellow" <?php if($_GET['color'] == 'yellow'): ?>selected<?php endif; ?>>黄</option>
		<option value="green" <?php if($_GET['color'] == 'green'): ?>selected<?php endif; ?>>绿</option>
		<option value="blue" <?php if($_GET['color'] == 'blue'): ?>selected<?php endif; ?>>蓝</option>
		<option value="gray" <?php if($_GET['color'] == 'gray'): ?>selected<?php endif; ?>>灰</option>
		<option value="purple" <?php if($_GET['color'] == 'purple'): ?>selected<?php endif; ?>>紫</option>
		<option value="brown" <?php if($_GET['color'] == 'brown'): ?>selected<?php endif; ?>>棕</option>
		<option value="white" <?php if($_GET['color'] == 'white'): ?>selected<?php endif; ?>>白</option>
	</select><?php endif; ?>
</div>
<?php if($type != 'music'): ?><ul>
<?php if(is_array($files)): $i = 0; $__LIST__ = $files;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li style="float:left;width:<?php echo ($height); ?>px;margin:1px;background:#ddd"><a href="###" onclick="returnHomepage('<?php echo ($siteUrl); ?>/tpl/static/attachment/<?php echo ($type); ?>/<?php echo ($folder); ?>/<?php echo ($item); ?>')"><img class="attimg" style="width:<?php echo ($height); ?>px;height:<?php echo ($height); ?>px;" src="/tpl/static/attachment/<?php echo ($type); ?>/<?php echo ($folder); ?>/<?php echo ($item); ?>" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
<div class="clr" style="height:10px;"></div>
</ul>
<div class="pages"><?php echo ($show); ?></div>
<?php else: ?>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>播放</th>
<th>选择 <span class="tooltips" ><img src="<?php echo RES;?>/images/price_help.png" align="absmiddle" /><span>
<p>点击歌名即可</p>
</span></span></th>
</tr>
</thead>
<?php if(is_array($files)): $i = 0; $__LIST__ = $files;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr><td><a style="width:220px;float:left" class="audio {skin:'blue'}" href="<?php echo ($siteUrl); ?>/tpl/static/attachment/<?php echo ($type); ?>/<?php echo ($folder); ?>/<?php echo ($item["file"]); ?>"><?php echo ($item["name"]); ?></a></td><td>&nbsp;&nbsp;<a href="###" onclick="returnHomepage('<?php echo ($siteUrl); ?>/tpl/static/attachment/<?php echo ($type); ?>/<?php echo ($folder); ?>/<?php echo ($item["file"]); ?>')"><?php echo ($item["name"]); ?></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table><?php endif; ?>
<?php
}else{ ?>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>文件</th>
<th>时间</th>
<th>选择</th>
</tr>
</thead>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr><td>
<?php
$classStr="audio {skin:'blue'}"; if (strpos($item['url'],'.mp3')){ echo '<a style="width:220px;float:left" class="'.$classStr.'" href="'.$item['url'].'">'.$item['name'].'</a>'; }else { echo '<img src="'.$item['url'].'" width="120" />'; } ?>
</td><td><?php echo (date('Y-m-d',$item["time"])); ?></td><td>&nbsp;&nbsp;<a href="###" onclick="returnHomepage('<?php echo ($item["url"]); ?>')">选中</a>&nbsp;<a href="<?php echo U('Attachment/delete',array('id'=>$item['id']));?>">删除</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages"><?php echo ($page); ?></div>
</div>
<?php
} ?>

</div>
</div>
<div style="clear:both;height:30px;"></div>

<script>
var domid=art.dialog.data('domid');
// 返回数据到主页面
function returnHomepage(url){
	var origin = artDialog.open.origin;
	var dom = origin.document.getElementById(domid);
	var domsrcid=domid+'_src';

	if(origin.document.getElementById(domsrcid)){
	origin.document.getElementById(domsrcid).src=url;
	}
	
	dom.value=url;
	setTimeout("art.dialog.close()", 100 )
}
</script>

</body>
</html>