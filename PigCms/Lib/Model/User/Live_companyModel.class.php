<?php
	class Live_companyModel extends Model{
	protected $_validate = array(
		array('name','require','请选择商家'),
		array('bg_pic','require','背景图片不能为空'),
		array('top_pic','require','头部图片不能为空'),
	);


/*
	protected $_auto = array (    
		array('is_open','0'),  
		array('add_time','time',1,'function'), 
	);
*/


}

?>