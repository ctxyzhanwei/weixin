<?php
class CarModel extends Model {

	//自动验证
	protected $_validate = array(

			array('name','require','品牌名不能为空',1),
			array('logo','require','LOGO不能为空',1),
			array('info','require','品牌简介不能为空',1)
	 );


}