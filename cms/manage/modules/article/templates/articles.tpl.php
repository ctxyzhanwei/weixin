<?php include($this->showManageTpl('header','manage'));?>
<script>
window.parent.top.document.getElementById("bottomframes").cols="0,7,*";
window.parent.top.document.getElementById("separator").contentWindow.document.getElementById('ImgArrow').src="image/separator2.gif"
</script>
<script>
window.addEvent('domready',function(){
	$('taxis').addEvent('click',function(){
		$('form').setProperty('action','?m=article&c=m_article&a=action_taxis');
		$('form').submit();
	})
	if($('transfer')){
	$('transfer').addEvent('click',function(){
		$('form').setProperty('action','?m=article&c=m_article&a=action_toTransferUrl');
		$('form').submit();
	})
	}
	$('deleteContent').addEvent('click',function(){
		if(confirm('确定删除吗')){
		$('form').setProperty('action','?m=article&c=m_article&a=action_delete');
		$('form').submit();
		}
	})
	var status = {'true': '搜索','false': '取消'};
	var slide=new Fx.Slide('searchDom');
	slide.slideOut();
	$('search').addEvent('click',function(e){
		$('searchDom').setStyle('display','block');
		e.stop();
		slide.toggle();
		$('status').set('html',status[slide.open]);
	});
	$('searchBtn').addEvent('click',function(e){
		window.location.href='searchContents.php?channelid=<?php echo $_GET['id'];?>&keyword='+$('keyword').value;
	});
});
</script>
    <div class="columntitle"><?php echo $crumb.'<a href="?m=article&c=m_article&a=articles&id='.$_GET['id'].'&site='.$_GET['site'].'">'.$thisChannel->name.'</a>'; ?></div>
    <div style="border:1px solid #E9EFF7;margin-top:10px;">
    <TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0" style="">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="padding-left:10px;">
          <?php
          //if ($this->access('channel_content','add',array('channelID'=>$paretID))||$thisChannel->site!=1){
          	 ?>
          	 <a href="?m=article&c=m_article&a=articleSet&channelid=<?php echo $paretID; ?>&site=<?php echo $_GET['site'];?>"><img style="MARGIN-RIGHT: 3px" src="image/add.png" align="absmiddle" />添加</a>
          	 <?php
         // }
          ?>
            
            <?php
            
         // if ($this->access('channel_content','delete',array('channelID'=>$paretID))||$thisChannel->site!=1){
          	 ?>
          	 &nbsp;<img style="MARGIN-RIGHT: 3px" src="image/cross.png" align="absmiddle" /><a href="javascript:void(0)" id="deleteContent">删除</a>
          	 <?php
         // }
          ?>
             
          <?php
          //if (access('channel_content','update',array('channelID'=>$paretID))){
          	 ?>
          	 &nbsp;<img style="MARGIN-RIGHT: 3px" src="image/taxsis.png" align="absmiddle" /><a href="javascript:void(0)" id="taxis">排序</a>

          	 <?php
          //}
          ?>
             &nbsp;<img style="MARGIN-RIGHT: 3px" src="image/arrow_redo.png" align="absmiddle" /><a href="<?php echo $_SERVER['HTTP_REFERER'];?>">返回</a>
             <span style="display:none">
             &nbsp;<img style="MARGIN-RIGHT: 3px" src="image/page_world.png" align="absmiddle" /><a href="javascript:void(0)" id="create">生成</a>
           
             &nbsp;<img style="MARGIN-RIGHT: 3px" src="image/zoom.png" align="absmiddle" /><a href="javascript:void(0)" id="search"><span id="status">搜索</span></a>
             
              <div id="searchDom" style="padding:10px;display:none"><div style="padding:0 10px;"><input name="keyword" id="keyword" style="width:19%;" type="text" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';"></input> <input type="button" id="searchBtn" value="搜索"></input></div></div>
          </TD>
        </TR>
      </TBODY>
    </TABLE>
    <form method="POST" id="form" action="">
    <input type="hidden" name="channelid" value="<?php echo $_GET['id'];?>" />
    <input type="hidden" name="siteid" value="<?php echo $_GET['site'];?>" />
    <table cellspacing="1" cellpadding="1" Align="center" border="0" border="0" style="width:100%;word-break: break-all">
	<tr>
		<td>
        <tr class="summary-title" id="property">
          <td align="Center" style="width:30px;">ID</td>
          <td>&nbsp;标题</td>
          
          <td align="Center" style="width:30px;"></td>
          <td align="Center" style="width:30px;"><?php if ($thisChannel->channeltype!=2){ echo 'view';}?></td>
           <td align="Center" style="width:60px;">顺序</td>
           <td align="Center" style="width:30px;">修改</td>
           <td align="Center" style="width:30px;display:none">预览</td>
          <td align="Center" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
        </tr>
      </td>
	</tr>
<?php
if ($contents){
	foreach ($contents as $c){
?>
	<tr>
		<td>
        <tr class="tdbg" onMouseOver="this.className='tdbg-dark';" onMouseOut="this.className='tdbg';" style="height:25px;" id="tr<?php echo $c['id']; ?>">
           <td align="Center" style="width:30px;"><nobr><?php echo $c['id']; ?></nobr></td>
          <td valign="middle">&nbsp;
          <?php
          /*
          if (!strpos($_SERVER['HTTP_HOST'],'')&&!strpos($_SERVER['HTTP_HOST'],'')&&$thisChannel->channeltype!=2){
          if ($c['cancomment']){
          	
          	$commentsCount=$comment_db->count(array('contentid'=>$c['id']));
          	
          	if (!$commentsCount){
          		$commentStr='<a href="contentComments.php?id='.$c['id'].'" style="color:#999">暂无评论</a>';
          	}else {
          		$commentStr='<a href="contentComments.php?id='.$c['id'].'" style="color:#999"><span style="color:#f30">'.$commentsCount.'</span>条评论</a>';
          	}
          }else {
          	$commentStr='已关闭评论';
          }
          }
          */
          ?>
            <a href="?m=article&c=m_article&a=articleSet&channelid=<?php echo $paretID; ?>&site=<?php echo $_GET['site'];?>&id=<?php echo $c['id']; ?>&start=<?php echo $_REQUEST['start']; ?>"><?php echo $c['title']; ?></a> <?php if(strlen($c['thumb'])){ echo '<img src="image/picture.png" align="absmiddle"></img>';
            }
            if ($c['time']>SYS_TIME){
            	echo '<img align="absmiddle" src="image/icons/time.png" title="定时发布" /> ';
            }
            echo '<span style="color:#999">'.$commentStr.'</span>';
             ?>
          </td>
          
          <td align="Center" width="110"><nobr><?php echo date('Y-m-d H:i',$c['time']); ?></nobr></td>
           <td align="Center" style="width:30px;"><nobr><?php if ($thisChannel->channeltype!=2){echo $c['viewcount'];}else{echo '&nbsp;<a href="/'.CMS_DIR.'/contentPhotos.php?id='.$c['id'].'">上传图片</a>&nbsp;';} ?></nobr></td>
          <td align="Center" style="width:30px;"><input type="text" style="width:50px;" class="colorblur" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" name="taxis[<?php echo $c['id']; ?>]" value="<?php echo $c['taxis']; ?>" /></td>
          <td align="Center" style="width:30px;"><a href="?m=article&c=m_article&a=articleSet&channelid=<?php echo $paretID; ?>&site=<?php echo $_GET['site'];?>&id=<?php echo $c['id']; ?>&start=<?php echo $_REQUEST['start']; ?>">修改</a></td>
          <td align="Center" style="width:30px;display:none">
          <?php
          if ($c['time']<SYS_TIME){
          ?>
          <a href="<?php if (intval($_GET['site'])==1){echo $c['link'];}else{echo $thisSite->url.$c['link'];} ?>" target="_blank">预览</a>
          <?php
          }
          ?>
          </td>
          <td align="center"><input type="checkbox" value="<?php echo $c['id']?>" name="id[]"></td>
        </tr>
      </td>
	</tr>
<?php
	}
}
?>
</table>
</div>
</form>
<div class="pages">
<?php
echo $this->article_db->pages;
?>
</div>
<?php include($this->showManageTpl('footer','manage'));?>