<?php
class WallAction extends WapAction {
	public $wall_model;
	public $wecha_id;
	public $token;
	public function __construct() {
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->assign('token', $this->token);
		$this->wecha_id = $this->_get('wecha_id');
		if (!$this->wecha_id) {
			$this->wecha_id = 'null';
		}
		$this->assign('wecha_id', $this->wecha_id);
		$this->wall_model = M('Wall');
	}
	public function index() {
		if (!$this->wecha_id) {
			$this->error('您无权参与微信墙', '');
		}
		if (IS_POST) {
			$wallRow = array();
			$wallRow['wecha_id'] = $this->wecha_id;
			$wallRow['token'] = $this->token;
			$thisWall = M('Wall')->where(array('token' => $wallRow['token']))->find();
			if ($thisWall) {
				$wallRow['wallid'] = $thisWall['id'];
				$wallRow['portrait'] = $this->_post('portrait');
				$wallRow['nickname'] = $this->_post('nickname');
				$wallRow['mp'] = $this->_post('mp');
				$wallRow['time'] = time();
				$wallRowExist = M('Wall_member')->where(array('wallid' => intval($thisWall['id']), 'wecha_id' => $wallRow['wecha_id']))->find();
				if ($wallRowExist) {
					M('Wall_member')->where(array('wallid' => intval($thisWall['id']), 'wecha_id' => $wallRow['wecha_id']))->save($wallRow);
				}else {
					M('Wall_member')->add($wallRow);
				}
				echo 1;
			}
		}else {
			$thisWall = M('Wall')->where(array('token' => $this->token))->find();
			$wallRowExist = M('Wall_member')->where(array('wallid' => intval($thisWall['id']), 'wecha_id' => $this->wecha_id))->find();
			$this->assign('info', $wallRowExist);
			$this->display();
		}
	}
	public function person() {
		$wallRow = array();
		$wallRow['wecha_id'] = $this->wecha_id;
		$wallRow['token'] = $this->token;
		$wallRow['wallid'] = intval($_GET['id']);
		$wallRow['portrait'] = $this->fans['portrait'];
		$wallRow['nickname'] = $this->fans['truename'];
		$wallRow['mp'] = $this->fans['tel'];
		$wallRow['time'] = time();
		$wallRowExist = M('Wall_member')->where(array('wallid' => $wallRow['wallid'], 'wecha_id' => $wallRow['wecha_id']))->find();
		if ($wallRowExist) {
			M('Wall_member')->where(array('wallid' => intval($wallRow['wallid']), 'wecha_id' => $wallRow['wecha_id']))->save($wallRow);
		}else {
			M('Wall_member')->add($wallRow);
		}
		$this->success('设置成功，关掉该页面，进入微信对话框留言就行了', '');
	}
}

?>