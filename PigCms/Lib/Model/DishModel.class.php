<?php
class DishModel extends Model{
	protected $_validate = array(
		array('name','require','菜名不能为空',1),
	);
    protected $_auto = array ( 
        array('creattime','time',1,'function'), // 对create_time字段在更新的时候写入当前时间戳
    );
}
?>