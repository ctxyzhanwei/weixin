<?php 
class PunishModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('title','require','请输入消息标题'),	
			array('keyword','require','请输入关键词'),
			array('pic','require','消息回复图片不能为空'),
	 );


}