<?php
class NewsAction extends BackAction{
	public function index(){
		$where = '';
		if (!C('agent_version')) {
		}else {
			$where = array('agentid' => 0);
		}
		$db = D('News');
		$links = M('News')->where($where)->select();
		$count = $db->where($where)->count();
		$page = new Page($count, 25);
		$info = $db->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('info', $info);
		$this->assign('page', $page->show());
		$this->display();
	}
	public function edit(){
		$where['id'] = $this->_get('id', 'intval');
		$db = D('News');
		$info = $db->where($where)->find();
		$this->assign('info', $info);
		$this->display('add');
	}
	public function add(){
		$this->display();
	}
	public function insert(){
		$_POST['showtime'] = strtotime($_POST['showtime']);
		$this->all_insert('News');
	}

	public function upsave(){
		$_POST['showtime'] = strtotime($_POST['showtime']);
		$this->all_save('News');
	}

	public function del(){
		$id = $this->_get('id', 'intval');
		$db = D('News');
		if ($db->delete($id)) {
			$this->success('操作成功', U(MODULE_NAME . '/index'));
		}else {
			$this->error('操作失败', U(MODULE_NAME . '/index'));
		}
	}
}
?>
