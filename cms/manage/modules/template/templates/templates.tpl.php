<?php include($this->showManageTpl('header','manage'));?>

<script>
window.addEvent('domready',function(){
	
});
</script>
    <div class="columntitle">模板管理（<?php echo $this->cats[$type]; ?>）</div>
    <div style="border:1px solid #E9EFF7;margin-top:10px;">
    <div class="oper" style="border:none">
        选择模板类型：<?php
          foreach ($this->cats as $k=>$cat){
          	if ($type==$k){
          		$style=' style="color:#902E0C"';
          	}else {
          		$style='';
          	}
          	echo '<a href="?m=template&c=m_template&a=templates&type='.$k.'&siteid='.$_GET['siteid'].'"'.$style.'>'.$cat.'</a>&nbsp;';
          }
          ?>
        </div>
    <div class="oper" style="display:none">
           <a href="?m=template&c=m_template&a=templateSet&type=<?php echo $type;?>&siteid=<?php echo $_GET['siteid'];?>" class="add">添加</a>
            <a href="###" onclick="$('form').submit()" class="delete">删除</a>
        </div>
  <form action="?m=template&c=m_template&a=templates" id="form" method="POST">
  <input type="hidden" name="type" value="<?php echo $type;?>" />
  <table cellspacing="1" cellpadding="1" Align="center" border="0" border="0" style="width:100%;word-break: break-all">
	<tr>
		<td>
        <tr class="summary-title" id="property">
        <td align="Center" width="20" height="30"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
          <td style="width:30px;text-align:center;">&nbsp;ID</td>
          <td>&nbsp;名称</td>
          <td align="Center">文件地址</td>
          <td align="Center" style="width:60px;">模板类型</td>
          <td align="Center" style="width:60px;">默认</td>
          <td align="Center" style="width:60px;">操作</td>
        </tr>
      </td>
	</tr>
<?php
if ($templates){
	foreach ($templates as $t){
?>
	<tr>
		<td>
        <tr id="tr<?php echo $t->id;?>">
        <td align="center" height="30"><input type="checkbox" value="<?php echo $t->id;?>" name="id[]"></td>
        
          <td style="width:30px;text-align:center;"><?php echo $t->id;?></td>
          <td>&nbsp;<?php echo $t->name;?></td>
          <td align="Center"><?php echo $t->path;?></td>
          <td align="Center" valign="middle">
          <?php
          echo $this->cats[$t->type];
          ?>
          </td>
          <td align="center"><?php if (intval($t->isdefault)){echo ' <img src="image/default.gif" align="absmiddle"></img>';}?></td>
          <td align="Center" style="width:60px;"><a href="?m=template&c=m_template&a=templateSet&type=<?php echo $type;?>&siteid=<?php echo $_GET['siteid'];?>&id=<?php echo $t->id;?>" title="修改">修改</a></td>
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
<?php include($this->showManageTpl('footer','manage'));?>