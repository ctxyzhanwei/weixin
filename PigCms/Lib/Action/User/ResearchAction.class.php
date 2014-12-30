<?php
class ResearchAction extends LotteryBaseAction 
{

	public function _initialize()
	{
		parent::_initialize();
		if (empty($this->token)) {
			$this->error('不合法的操作', U('Index/index'));
		}
		$this->canUseFunction('Research');
	}
	
	public function index()
	{
		$data = M('Research')->where(array('token' => $this->token))->order('id desc')->select();
		$lids = array();
		foreach ($data as $row) {
			$lids[] = $row['lid'];
		}
		if ($lids) {
			M('Keyword')->where(array('pid' => array('in', $lids), 'token' => $this->token, 'module' => 'Lottery'))->delete();
		}
		$this->assign('list', $data);
		$this->display();
	}
	
	public function saveInfo()
	{
		$rid = isset($_REQUEST['rid']) ? intval($_REQUEST['rid']) : 0;
		if ($data = M('Research')->where(array('id' => $rid, 'token' => $this->token))->find()) {
			$data['starttime'] = date("Y-m-d H:i:s", $data['starttime']);
			$data['dateline'] = date("Y-m-d H:i:s", $data['endtime']);
			$rqdata = M('Research_question')->where(array('rid' => $rid))->order('id asc')->select();
			$useqids = $useaids = $list = array();
			foreach ($rqdata as $v) {
				$useqids[] = $v['id'];
				$answerobj = M('Research_answer')->where(array('qid' => $v['id']))->order('id asc')->select();
				$v['answer'] = $answerobj;
				$list[] = $v;
				foreach ($answerobj as $a) {
					$useaids[] = $a['id'];
				}
			}
		}
		if (IS_POST) {
			$research = array();
			$research['title'] = isset($_REQUEST['title']) ? htmlspecialchars($_REQUEST['title']) : '';
			$research['description'] = isset($_REQUEST['description']) ? htmlspecialchars($_REQUEST['description']) : '';
			$keyword = $research['keyword'] = isset($_REQUEST['keyword']) ? htmlspecialchars($_REQUEST['keyword']) : '';
			$research['logourl'] = isset($_REQUEST['logourl']) ? htmlspecialchars($_REQUEST['logourl']) : '';
			$research['token'] = $this->token;
			$research['starttime'] = isset($_REQUEST['starttime']) ? strtotime(htmlspecialchars($_REQUEST['starttime'])) : '';
			$research['endtime'] = isset($_REQUEST['deadline']) ? strtotime(htmlspecialchars($_REQUEST['deadline'])) : '';
			$question = isset($_REQUEST['question']) ? $_REQUEST['question'] : array();
			$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : array();
			$ismulti = isset($_REQUEST['ismulti']) ? $_REQUEST['ismulti'] : array();
			$dataBase = D('Research');
			if ($rid && ($res = $dataBase->where(array('id' => $rid))->find())) {
				$_POST['id'] = $rid;
				$qids = isset($_REQUEST['qid']) ? $_REQUEST['qid'] : array();
				$aids = isset($_REQUEST['oid']) ? $_REQUEST['oid'] : array();
				$updateqids = $updateaids = array();
				if ($dataBase->create() !== false) {
					$research['id'] = $rid;
					$dataBase->save($research);//edit 调研
					//edit 奖项
					D("Lottery")->where(array('id' => $res['lid']))->save(array('keyword' => $research['keyword'], 'statdate' => $research['starttime'], 'enddate' => $research['endtime'], 'info' => $research['description'], 'title' => $research['title']));
					foreach ($question as $k => $q) {
						if (!isset($option[$k]) || (isset($option[$k]) && empty($option[$k])) || empty($q) || !isset($qids[$k])) continue;
						$qdata = array('rid' => $rid, 'name' => htmlspecialchars($q), 'type' => isset($ismulti[$k]) ? intval($ismulti[$k]) : 0);
						if ($qids[$k]) {// edit question
							$qdata['id'] = $qids[$k];
							D('Research_question')->save($qdata);
							//记录更新的问题id
							$updateqids[] = $qids[$k];
							foreach ($option[$k] as $i => $v) {
								if (!isset($aids[$k][$i])) continue;
								$adata = array('qid' => $qids[$k], 'name' => htmlspecialchars($v));
								if (empty($aids[$k][$i])) {//add answer
									D('Research_answer')->add($adata);
								} else {//edit answer
									$adata['id'] = $aids[$k][$i];
									D('Research_answer')->save($adata);
									$updateaids[] = $aids[$k][$i];
								}
							}
						} else {//add question
							if ($qid = D('Research_question')->add($qdata)) {
								foreach ($option[$k] as $v) {
									if (empty($v)) continue;
									$adata = array('qid' => $qid, 'name' => htmlspecialchars($v));
									$aid = D('Research_answer')->add($adata);
								}
							}
						}
					}
					$nouseqid = array_diff($useqids, $updateqids);
					if ($nouseqid) {
						M('Research_question')->where(array('id' => array('in', $nouseqid)))->delete();
					}
					$nouseaid = array_diff($useaids, $updateaids);
					if ($nouseaid) {
						M('Research_answer')->where(array('id' => array('in', $nouseaid)))->delete();
					}
				} else {
					exit(json_encode(array('error_code' => true, 'msg' => '数据有误')));
				}

			} else {
				$research['dateline'] = time();
				if ($dataBase->create() !== false) {
					if ($rid = $dataBase->add($research)) {
						foreach ($question as $k => $q) {
							if (!isset($option[$k]) || (isset($option[$k]) && empty($option[$k])) || empty($q)) continue;
							$qdata = array('rid' => $rid, 'name' => htmlspecialchars($q), 'type' => isset($ismulti[$k]) ? intval($ismulti[$k]) : 0);
							if ($qid = M('Research_question')->add($qdata)) {
								foreach ($option[$k] as $v) {
									if (empty($v)) continue;
									$adata = array('qid' => $qid, 'name' => htmlspecialchars($v));
									$aid = M('Research_answer')->add($adata);
								}
							}
						}
					}
				} else {
					exit(json_encode(array('error_code' => true, 'msg' => '数据有误')));
				}
			}
		
			if ($keys = M('Keyword')->where(array('pid' => $rid, 'token' => $this->token, 'module' => 'Research'))->find()) {
				M('Keyword')->where(array('pid' => $rid, 'token' => $this->token, 'id' => $keys['id']))->save(array('keyword' => $keyword));
			} else {
				M('Keyword')->add(array('token' => $this->token, 'pid' => $rid, 'keyword' => $keyword, 'module' => 'Research'));
			}
			exit(json_encode(array('error_code' => false, 'msg' => 'ok')));
		} else {
			if ($rid) {
				$this->assign('list', $list);
				$this->assign('research', $data);
				$this->display('reedit');
			} else {
				$this->display('readd');
			}
		}
	}
	
	/**
	 * 活动统计
	 */
	public function count()
	{
		$id = intval($this->_get('rid'));
		//$research = M('Research')->where(array('id' => $id , 'token' => $this->token))->find();
		$question = M('Research_question')->where(array('rid' => $id))->order('id asc')->select();
		$list = array();
		foreach ($question as $q) {
			$q['answer'] = M('Research_answer')->where(array('qid' => $q['id']))->order('id asc')->select();
			$q['rowspan'] = count($q['answer']);
			$list[] = $q;
		}
		$this->assign('list', $list);
		$this->display();
	}
	
	/**
	 *属性的删除 
	 */
	public function delResearch() {
		if($this->_get('token') != session('token')){$this->error('非法操作');}
        $id = intval($this->_get('rid'));
        if(IS_GET){                         
            $where = array('id' => $id, 'token' => session('token'));
            $data = M('Research');
            $check = $data->where($where)->find();
            if($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
				$rqdata = M('Research_question')->where(array('rid' => $id))->select();
				$useqids = $useaids = array();
				foreach ($rqdata as $v) {
					$useqids[] = $v['id'];
					$answerobj = M('Research_answer')->where(array('qid' => $v['id']))->order('id asc')->select();
					foreach ($answerobj as $a) {
						$useaids[] = $a['id'];
					}
				}
				if ($useqids) {
					M('Research_question')->where(array('id' => array('in', $useqids)))->delete();
				}
				if ($useaids) {
					M('Research_answer')->where(array('id' => array('in', $useaids)))->delete();
				}
		
				if ($keys = M('Keyword')->where(array('pid' => $check['id'], 'token' => $this->token, 'module' => 'Research'))->find()) {
					M('Keyword')->where(array('pid' => $check['id'], 'token' => $this->token, 'id' => $keys['id']))->delete();
				}
            	$this->success('操作成功',U('Research/index', array('token' => session('token'))));
            } else {
				$this->error('服务器繁忙,请稍后再试',U('Research/index', array('token' => session('token'))));
            }
        }        
	}
	public function cheat(){
		parent::cheat();
		$this->display();
	}
	public function research(){
		parent::index(6);
		$rid = isset($_GET['researchid']) ? intval($_GET['researchid']) : 0;
		if (empty($rid)) {
			$this->redirect(U('Research/index'));
		}
		$this->assign('rid', $rid);
		$this->display();
	
	}
	public function sn(){
		parent::sn(6);
		$this->display('Lottery:sn');
	}
	public function add(){
		$rid = isset($_GET['researchid']) ? intval($_GET['researchid']) : 0;
		$data = M('Research')->where(array('id' => $rid, 'token' => $this->token))->find();
		if (empty($data)) {
			$this->redirect(U('Research/index'));
		}
		$this->assign('research', $data);
		parent::add(6);
	}
	
	public function edit(){
		$rid = isset($_GET['researchid']) ? intval($_GET['researchid']) : 0;
		$data = M('Research')->where(array('id' => $rid, 'token' => $this->token))->find();
		if (empty($data)) {
			$this->redirect(U('Research/index'));
		}
		$this->assign('research', $data);
		parent::edit(6);
	}
}
?>