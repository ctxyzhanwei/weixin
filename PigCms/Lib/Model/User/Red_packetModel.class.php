<?php
class Red_packetModel extends Model{
	protected $_validate = array(
			array('title','require','活动名称必须填写',1),
			array('keyword','require','关键词必须填写',1),
			array('packet_pic','require','主题图片不能为空',1),
			array('msg_pic','require','消息图片不能为空',1),
			array('desc','require','活动简介不能为空',1),
			array('start_time','require','开始时间不能为空',1),
			array('end_time','require','结束时间不能为空',1),
			array('info','require','活动说明不能为空',1),
			array('packet_count','require','红包奖励上限金额不能为空',1),
			array('entity_count','require','礼品奖励上限金额不能为空',1),
			array('get_number','require','最多领取红包数量不能为空',1),
			array('endtitle','require','结束标题不能为空',1),
			array('endinfo','require','结束说明不能为空',1),
	 );
}

?>
