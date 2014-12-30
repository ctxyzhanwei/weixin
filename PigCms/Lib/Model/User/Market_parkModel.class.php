<?php 
class Market_parkModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('park_name','require','停车区域名不能为空',3),
			array('park_num','require','车位数量不能为空',3),
	 );

}