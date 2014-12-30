<?php
class WeddingModel extends Model{
	protected $_validate = array(
			array('title','require','标题不能为空',1),
			//array('info','require','详细内容必须填写',1),
			array('coverurl','require','封面图片必须填写',1),
			array('openpic','require','开场动画图片必须填写',1),
			array('man','require','新郎姓名必须填写',1),
			array('woman','require','新娘姓名必须填写',1),
			//array('fid','require','必须选择相册名',1),
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
