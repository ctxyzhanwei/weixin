<?php
class InvitesModel extends Model{
	protected $_validate = array(
			array('title','require','邀请标题不能为空',1),
			array('keyword','require','邀请关键词不能为空',1),
			array('brief','require','邀请简介不能为空',1),
			array('content','require','邀请内容不能为空',1),
			array('statdate','require','邀请时间必须填写',1),
			array('address','require','邀请地点必须填写',1),
			array('lng','require','请选择经纬度',1),

	 );
	protected $_auto = array (		
		array('token','getToken',Model:: MODEL_BOTH,'callback'),
	);
	function getToken(){	
		return $_SESSION['token'];
	}
}

?>
