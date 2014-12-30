<?php
class School_catModel extends Model {

	//自动验证
	protected $_validate = array(
			array('name','require','版块名称不能为空',3),
			array('icon','require','版块图标不能为空',3),
			array('url','require','版块链接不能为空',3),
	);


}