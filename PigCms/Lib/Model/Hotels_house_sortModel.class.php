<?php
class Hotels_house_sortModel extends Model{
	protected $_validate =array(
		array('name', 'require', '房间名（编号）不能为空', 1),
	);
	
	protected $_auto = array (
		array('token', 'gettoken', self::MODEL_INSERT, 'callback'),
	);
	
	public function gettoken(){
		return session('token');
	}
}