<?php 
class Problem_gameModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('title','require','请输入消息标题'),	
			array('keyword','require','请输入关键词'),
			array('logo_pic','require','图文消息封面不能为空'),
			array('banner','require','活动横幅不能为空'),
			array('explain ','require','活动说明不能为空'),
			array('question_num','require','答题数量必须填写'),
			array('score','require','答题奖励必须填写'),
			array('answer_time','require','答题时间必须填写'),
			array('end_day','require','活动时间必须填写'),
			array('sub_limit','require','活动间隔时间必须填写'),
	 );


}