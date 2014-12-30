<?php
class Red_packet_prizeModel extends Model{
	protected $_validate = array(
		array('name','require','红包名称不能为空',1),
		array('worth','require','红包价值不能为空',1),
		array('num','require','红包数量不能为空',1),
		array('odds','require','中奖几率不能为空',1),
	);
}

?>
