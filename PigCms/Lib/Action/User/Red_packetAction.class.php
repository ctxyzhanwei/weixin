<?php
class Red_packetAction extends UserAction {
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Red_packet');
	}
	public function index() {
		$searchkey = $this->_post('searchkey', 'trim');
		$where = array('token' => $this->token);
		if (!empty($searchkey)) {
			$where['title|keyword'] = array('like', '%' . $searchkey . '%');
		}
		$count = M('Red_packet')->where($where)->count();
		$Page = new Page($count, 15);
		$list = M('Red_packet')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $key => $value) {
			$log = M('Red_packet_log')->Distinct(true)->field('wecha_id')->where(array('token' => $this->token, 'packet_id' => $value['id']))->select();
			$list[$key]['pcount'] = count($log);
		}
		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->display();
	}
	public function set() {
		$prize_db = M('Red_packet');
		$keyword_db = M('Keyword');
		$where = array('token' => $this->token, 'id' => $this->_get('id', 'intval'));
		$packet_info = $prize_db->where($where)->find();
		if (IS_POST) {
			if ($prize_db->create()) {
				$_POST['start_time'] = strtotime($_POST['start_time']);
				$_POST['end_time'] = strtotime($_POST['end_time']);
				if (empty($packet_info)) {
					$_POST['token'] = $this->token;
					$id = $prize_db->add($_POST);
					if ($id) {
						$keyword['pid'] = $id;
						$keyword['module'] = 'Packet';
						$keyword['token'] = $this->token;
						$keyword['keyword'] = $this->_post('keyword', 'trim');
						$keyword_db->add($keyword);
					}
					$this->success('添加成功', U('Red_packet/index', array('token' => $this->token)));
				}else {
					$swhere = array('token' => $this->token, 'id' => $this->_post('id', 'intval'));
					$offset = $prize_db->where($swhere)->save($_POST);
					if ($offset) {
						$keyword['pid'] = $this->_POST('id', 'intval');
						$keyword['module'] = 'Packet';
						$keyword['token'] = $this->token;
						$keyword['keyword'] = $this->_post('keyword', 'trim');
						$keyword_db->where(array('token' => $this->token, 'pid' => $this->_post('id', 'intval'), 'module' => 'Problem'))->save($keyword);
					}
					$this->success('修改成功', U('Red_packet/index', array('token' => $this->token)));
				}
			}else {
				$this->error($prize_db->getError());
			}
		}else {
			$this->assign('set', $packet_info);
			$this->display();
		}
	}
	public function del() {
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		if (M('Red_packet')->where($where)->delete()) {
			M('Red_packet_prize')->where(array('token' => $this->token, 'packet_id' => $id))->delete();
			M('Red_packet_log')->where(array('token' => $this->token, 'packet_id' => $id))->delete();
			M('Red_packet_reward')->where(array('token' => $this->token, 'packet_id' => $id))->delete();
			M('Keyword')->where(array('token' => $this->token, 'pid' => $id, 'module' => 'Packet'))->delete();
			$this->success('删除成功', U('Red_packet/index', array('token' => $this->token)));
		}
	}
	public function prize_list() {
		$packet_id = $this->_get('id', 'intval');
		$type = $this->_post('type', 'trim');
		$searchkey = $this->_post('searchkey', 'trim');
		$where = array('token' => $this->token, 'packet_id' => $packet_id);
		if (!empty($searchkey)) {
			$where['name'] = array('like', '%' . $searchkey . '%');
		}
		if (!empty($type)) {
			$where['type'] = $type;
		}
		$count = M('Red_packet_prize')->where($where)->count();
		$Page = new Page($count, 15);
		$list = M('Red_packet_prize')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->assign('packet_id', $packet_id);
		$this->display();
	}
	public function add_prize() {
		$prize_db = D('Red_packet_prize');
		$packet_id = $this->_get('packet_id', 'intval');
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'packet_id' => $packet_id, 'id' => $id);
		$prize_info = $prize_db->where($where)->find();
		if (IS_POST) {
			if ($prize_db->create()) {
				if (empty($prize_info)) {
					$_POST['token'] = $this->token;
					$_POST['packet_id'] = $packet_id;
					$id = $prize_db->add($_POST);
					$this->success('添加成功', U('Red_packet/prize_list', array('token' => $this->token, 'id' => $packet_id)));
				}else {
					$swhere = array('token' => $this->token, 'id' => $this->_post('id', 'intval'));
					$offset = $prize_db->where($swhere)->save($_POST);
					$this->success('修改成功', U('Red_packet/prize_list', array('token' => $this->token, 'id' => $packet_id)));
				}
			}else {
				$this->error($prize_db->getError());
			}
		}else {
			$this->assign('packet_id', $packet_id);
			$this->assign('info', $prize_info);
			$this->display();
		}
	}
	public function prize_del() {
		$packet_id = $this->_get('packet_id', 'intval');
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'packet_id' => $packet_id, 'id' => $id);
		if (M('Red_packet_prize')->where($where)->delete()) {
			$this->success('删除成功', U('Red_packet/prize_list', array('token' => $this->token, 'id' => $packet_id)));
		}
	}
	public function prize_log() {
		$packet_id = $this->_get('id', 'intval');
		$prize_id = $this->_get('prize_id', 'intval');
		$log_id = $this->_get('log_id', 'trim');
		$where = array('token' => $this->token, 'packet_id' => $packet_id);
		$searchkey = $this->_post('searchkey', 'trim');
		$is_reward = $this->_post('is_reward', 'intval');
		if (!empty($searchkey)) {
			$searchkey = M('Userinfo')->where(array('truename|wechaname' => $searchkey))->getField('wecha_id');
			$where['wecha_id'] = $searchkey;
		}
		if (!empty($is_reward)) {
			$where['is_reward'] = $is_reward;
		}
		if (!empty($prize_id)) {
			$where['prize_id'] = $prize_id;
		}
		if (!empty($log_id)) {
			$where['id'] = array('in', $log_id);
		}
		$count = M('Red_packet_log')->where($where)->count();
		$Page = new Page($count, 20);
		$list = M('Red_packet_log')->where($where)->order('add_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $key => $value) {
			$list[$key]['wxname'] = M('Userinfo')->where(array('wecha_id' => $value['wecha_id']))->getField('wechaname');
		}
		$this->assign('list', $list);
		$this->assign('packet_id', $packet_id);
		$this->assign('page', $Page->show());
		$this->display();
	}
	public function log_del() {
		$packet_id = $this->_get('packet_id', 'intval');
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'packet_id' => $packet_id, 'id' => $id);
		if (M('Red_packet_log')->where($where)->delete()) {
			$this->success('删除成功', U('Red_packet/prize_log', array('token' => $this->token, 'id' => $packet_id)));
		}
	}
	public function show_forms() {
		$id = $this->_get('id', 'intval');
		$packet_id = $this->_get('packet_id', 'intval');
		$where = array('token' => $this->token, 'id' => $id, 'packet_id' => $packet_id);
		$info = M('Red_packet_exchange')->where($where)->find();
		$info['wxname'] = M('Userinfo')->where(array('wecha_id' => $info['wecha_id']))->getField('wechaname');
		$this->assign('info', $info);
		$this->display();
	}
	public function is_ok() {
		$id = $this->_get('id', 'intval');
		$packet_id = $this->_get('packet_id', 'intval');
		$where = array('token' => $this->token, 'id' => $id, 'packet_id' => $packet_id);
		$result = array();
		M('Red_packet_exchange')->where($where)->save(array('status' => '1'));
		$result['err'] = 0;
		$result['info'] = '操作成功！';
		echo json_encode($result);
	}
	public function exchange() {
		$packet_id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'packet_id' => $packet_id);
		$type = $this->_post('type', 'intval');
		$status = $this->_post('status');
		$searchkey = $this->_post('searchkey', 'trim');
		if (!empty($type)) {
			$where['type'] = $type;
		}
		if ($status != '') {
			$where['status'] = intval($status);
		}
		if (!empty($searchkey)) {
			$searchkey = M('Userinfo')->where(array('truename|wechaname' => $searchkey))->getField('wecha_id');
			$where['wecha_id'] = $searchkey;
		}
		$count = M('red_packet_exchange')->where($where)->count();
		$Page = new Page($count, 20);
		$list = M('red_packet_exchange')->where($where)->order('status asc,time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $key => $value) {
			$list[$key]['wxname'] = M('Userinfo')->where(array('wecha_id' => $value['wecha_id']))->getField('wechaname');
		}
		$this->assign('list', $list);
		$this->assign('packet_id', $packet_id);
		$this->assign('page', $Page->show());
		$this->display();
	}
	public function change_del() {
		$packet_id = $this->_get('packet_id', 'intval');
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'packet_id' => $packet_id, 'id' => $id);
		if (M('red_packet_exchange')->where($where)->delete()) {
			$this->success('删除成功', U('Red_packet/exchange', array('token' => $this->token, 'id' => $packet_id)));
		}
	}
}

?>