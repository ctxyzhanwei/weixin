<?php 
class CarsalerModel extends Model {

	//自动验证
	protected $_validate = array(
			
			array('name','require','姓名不能为空'),
			array('picture','require','头像不能为空',3),
			array('mobile','require','电话不能为空'),
			//array('info','require','介绍不能为空'),
			
	 );


}