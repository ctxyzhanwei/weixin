<?php
	class LiveModel extends Model{
	protected $_validate = array(
			array('name','require','名称不能为空'),
			array('keyword','require','关键词不能为空'),
	);


/*
	protected $_auto = array (    
		array('is_open','0'),  
		array('add_time','time',1,'function'), 
	);
*/


}

?>