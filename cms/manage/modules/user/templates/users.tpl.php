<?php include($this->showManageTpl('header','manage'));?>
    <div class="columntitle">用户管理</div>
   <div class="oper"><a class="add" href="?m=user&c=user&a=set&isadmin=<?php echo $_GET['isadmin'];?>">添加</a><a class="delete" onclick="if(confirm('确定删除吗')){$('myform').submit()}" href="###">删除</a></div>
   <form id="myform" method="POST" action="?m=user&c=user&a=index&isadmin=<?php echo $_GET['isadmin'];?>">
        <table cellspacing="1" cellpadding="1" Align="center" border="0" border="0" style="width:100%;word-break: break-all">
	<tr>
		<td>
        <tr class="summary-title" id="property">
        <td align="center" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
          <td width="30" align="center">UID</td>
          <td style="width:160px;">&nbsp;用户名</td>
          <td style="width:160px;">&nbsp;姓名</td>
          <td align="Center" style="width:120px;">操作</td>
        </tr>
      </td>
	</tr>
<?php
if ($users){
	foreach ($users as $u){
?>
	<tr>
		<td>
        <tr class="tdbg" onMouseOver="this.className='tdbg-dark';" onMouseOut="this.className='tdbg';" rel="">
        <td align="center"><input type="checkbox" value="<?php echo $u['uid'];?>" name="id[]"></td>
          <td align="center"><?php echo  $u['uid']; ?></td>
          <td style="width:160px;">&nbsp;<?php echo  $u['username']; ?></td>
          <td style="width:160px;">&nbsp;<?php echo  $u['realname']; ?></td>
          
          <td align="Center" style="width:30px;"><a href="?m=user&c=user&a=set&isadmin=<?php echo $_GET['isadmin'];?>&id=<?php echo $u['uid']; ?>">修改</a></td>
        </tr>
      </td>
	</tr>
<?php
}
}
?>
</table>

</form>


<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>