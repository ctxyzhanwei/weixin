<?php
class Wall_prizeModel extends Model {

	//自动验证
	protected $_validate = array(
			array('name','require','奖项不能为空',1),
			array('pname','require','奖品名称不能为空',1),
			array('num','require','奖品数量不能为空',1),
			array('pic','require','奖品图片必须上传',1),
	 );

}