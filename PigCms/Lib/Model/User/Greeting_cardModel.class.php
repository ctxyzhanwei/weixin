<?php
class Greeting_cardModel extends Model{
	protected $_validate = array(
			array('keyword','require','关键词不能为空',1),
			array('picurl','require','封面图片必须填写',1),
			array('mp3','require','背景音乐必须填写',1),
			array('type','require','开场动画必须选择',1),
			array('info','require','贺卡内容必须填写',1),
			array('id','checkid','非法操作',2,'callback',2),
	 );
	protected $_auto = array (		
		array('token','getToken',Model:: MODEL_BOTH,'callback'),
		array('create_time','time',1,'function'), // 对create_time字段在更新的时候写入当前时间戳);
	);
	function checkid(){
		$dataid=$this->field('id')->where(array('id'=>$_POST['id'],'token'=>session('token')))->find();
		if($dataid==false){
			return false;
		}else{
			return true;
		}
	}
	function getToken(){	
		return $_SESSION['token'];
	}
}

?>
