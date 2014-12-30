<?php include($this->showManageTpl('header','manage'));?>
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="js/calendar.js"></script>
<script>
window.addEvent('domready',function(){
	var url=window.location.toString();
	var urls=url.split('?');
	var parms=urls[1].parseQueryString();
	if(parms.channel){
		$('channel').value=parms.channel.toInt();
	}
	if(parms.uid){
		$('uid').value=parms.uid.toInt();
	}
	
	$('deleteContent').addEvent('click',function(){
		if(confirm('确定删除吗')){
		$('form').setProperty('action','?m=article&c=m_article&a=action_delete');
		$('form').submit();
		}
	})

});
var toSearchUrl=function(){
	window.location.href='?m=article&c=m_article&a=search&siteid=<?php echo $this->siteid;?>&keyword='+$('keyword').value;
}
</script>
    <div class="columntitle">文章搜索</div>
    <div style="border:1px solid #E9EFF7;margin-top:10px;">
    <TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="padding-left:10px;">
          <div id="searchDom" style="padding:10px;"><div style="padding:0 0px;">
          <a href="javascript:void(0)" id="deleteContent" class="delete">删除</a>
<!--
              <input type="hidden" id="site" value="<?php echo $siteID;?>"></input>
              栏目:
              <select id="channel" name="channel">
              <option value="0">所有</option>
          <?php
          
          echo $display;
          ?>
          </select>
                       
              时间: <input type="text" class="colorblur" name="startdate" value="<?php echo $st;?>" id="startdate" style="width:100px;" rel="calendar"></input> - <input type="text" name="enddate" value="<?php echo $et;?>" class="colorblur" id="enddate" style="width:100px;" rel="calendar"></input><div id="calendarDiv"></div>
              
              管理员:
              <?php
              echo '<select id="uid" name="uid"><option value="0">所有</option>';
              if ($admins){
              	foreach ($admins as $admin){
              		echo '<option value="'.$admin['uid'].'">'.$admin['username'].'</option>';
              	}
              }
              echo '</select>';
              ?>
              -->
              关键词: <input name="keyword" id="keyword" value="<?php echo $_GET['keyword'];?>" style="width:14%;" type="text" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';"></input> <input type="button" class="button" style="display:inline" onclick="toSearchUrl()" id="searchBtn" value="搜索"></input>
              
              
              </div></div>
          </TD>
        </TR>
      </TBODY>
    </TABLE>
    <form method="POST" id="form" action="">
    <input type="hidden" name="channelid" value="<?php echo $_GET['id'];?>" />
    <input type="hidden" name="siteid" value="<?php echo $_GET['siteid'];?>" />
    <table cellspacing="1" cellpadding="1" Align="center" border="0" border="0" style="width:100%;word-break: break-all">
	<tr>
		<td>
        <tr class="summary-title" id="property">
          <td align="Center" style="width:30px;">ID</td>
          <td>&nbsp;标题</td>
          
          <td align="Center" style="width:30px;"></td>
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
           <td align="Center" style="width:30px;"><?php echo $c['id']; ?></td>
          <td valign="middle">&nbsp;

            <a href="?m=article&c=m_article&a=articleSet&channelid=<?php echo $c['channel_id']; ?>&site=<?php echo $c['site']; ?>&id=<?php echo $c['id']; ?>&start=<?php echo $_GET['start']; ?>"><?php echo $c['title']; ?></a> <?php if(strlen($c['thumb'])){ echo '<img src="image/picture.png" align="absmiddle"></img>';
            }
             ?>
          </td>
          
          <td align="Center" width="110"><nobr><?php echo date('Y-m-d H:i',$c['time']); ?></nobr></td>

          <td align="Center" style="width:30px;"><a href="?m=article&c=m_article&a=articleSet&channelid=<?php echo $c['channel_id']; ?>&site=<?php echo $c['site']; ?>&id=<?php echo $c['id']; ?>&start=<?php echo $_GET['start']; ?>">修改</a></td>
          <td align="Center" style="width:30px;display:none"><a href="<?php if (intval($_GET['site'])==1){echo $c['link'];}else{echo $thisSite->url.$c['link'];} ?>" target="_blank">预览</a></td>
          <td align="center"><input type="checkbox" value="<?php echo $c['id']?>" name="id[]"></td>
        </tr>
      </td>
	</tr>
<?php
	}
}
?>
</table>
</form>
</div>
<div class="pages">
<?php
echo $pages;
?>
</div>

<?php include($this->showManageTpl('footer','manage'));?>