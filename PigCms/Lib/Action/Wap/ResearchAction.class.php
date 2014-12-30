<?php
class ResearchAction extends LotteryBaseAction {
	//public $token;
	//public $wecha_id = '';
	
	public $_rid = 0;
	
	public $_research;
	
	public function __construct(){
		parent::__construct();
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if (!strpos($agent, "MicroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		
		//$this->token = isset($_REQUEST['token']) ? htmlspecialchars($_REQUEST['token']) : '';//$this->_get('token');
		
		//$this->assign('token', $this->token);
		//$this->wecha_id	= isset($_REQUEST['wecha_id']) ? $_REQUEST['wecha_id'] : '';
		
		//$this->assign('wecha_id', $this->wecha_id);
		$this->wecha_id = isset($_REQUEST['wecha_id']) ? htmlspecialchars($_REQUEST['wecha_id']) :  '';//$this->_get('wecha_id');
		$this->token = isset($_REQUEST['token']) ? htmlspecialchars($_REQUEST['token']) :  '';//$this->_get('token');
		$this->_rid = isset($_REQUEST['reid']) ? intval($_REQUEST['reid']) :  0;
		if ($this->_research = M('Research')->where(array('id' => $this->_rid, 'token' => $this->token))->find()) {
			$this->assign('research', $this->_research);
			$this->assign('rid', $this->_rid);
			$this->assign('metaTitle', $this->_research['title']);
		} else {
			exit('调研不存在');
		}
		
	}
	
	/**
	 * 调研首页
	 */
	public function index()
	{
		$status = 0;
		if ($this->_research['starttime'] > time()) {
			$status = 1;
		} elseif ($this->_research['endtime'] < time()) {
			$status = 2;
		} else {
			$rcount = M('Research_result')->where(array('rid' => $this->_rid, 'wecha_id' => $this->wecha_id))->count();
			//$reuslt = M('Research_result')->where(array('rid' => $this->_rid, 'wecha_id' => $this->wecha_id))->order('id asc')->select();
			//$question = M('Research_question')->where(array('rid' => $this->_rid))->order('id asc')->select();
			$qcount = M('Research_question')->where(array('rid' => $this->_rid))->count();
			if ($rcount >= $qcount) {
				$status = 3;
			}
		}
		$this->assign('status', $status);
		//$this->assign('metaTitle', '微调研');
		$this->display();
	}
	
	/**
	 *回答问题
	 */
	public function answer()
	{
		if (empty($this->wecha_id)) {
			$this->error("无法获取您的微信号信息，请您关注该公众号后参与此活动", U("Research/index", array('reid' => $this->_rid, 'token' => $this->token)));
			die;
		}
		$reuslt = M('Research_result')->where(array('rid' => $this->_rid, 'wecha_id' => $this->wecha_id))->order('id asc')->select();
		$qids = array();
		foreach ($reuslt as $r) {
			$qids[] = $r['qid'];
		}
		if (IS_POST) {
			$qid = isset($_POST['qid']) ? intval($_POST['qid']) : 0;
			$aids = isset($_POST['answers']) ? htmlspecialchars($_POST['answers']) : '';
			if (empty($qid) || empty($aids) || empty($this->_rid)) {
				exit(json_encode(array('error_code' => true, 'msg' => '不合法的操作')));
			}
			if (empty($qids)) {
				 M('Research')->where(array('id' => $this->_rid, 'token' => $this->token))->setInc('nums', 1);
			}
			$data = array('qid' => $qid, 'wecha_id' => $this->wecha_id, 'rid' => $this->_rid, 'aids' => $aids);
			if ($r = D('Research_result')->add($data)) {
				$aids = explode(",", $aids);
				M('Research_answer')->where(array('id' => array('in', $aids), 'qid' => $qid))->setInc('nums', 1);
				exit(json_encode(array('error_code' => false, 'msg' => 'ok')));
			}
		}
		
		
		$que = array();
		$question = M('Research_question')->where(array('rid' => $this->_rid))->order('id asc')->select();
		foreach ($question as $q) {
			if (!in_array($q['id'], $qids)) {
				$que = $q;
				break;
			}
		}
		if (empty($que)) {
			$this->success("参加完毕，现在进行自动抽奖", U("Research/lotter", array('reid' => $this->_rid, 'wecha_id' => $this->wecha_id, 'token' => $this->token)));
		}
		$answer = M('Research_answer')->where(array('qid' => $que['id']))->order('id asc')->select();
		$maxsel = $que['type'] ? count($answer) : 1;
		$this->assign('question', $que);
		$this->assign('maxsel', $maxsel);
		$this->assign('answer', $answer);
		$this->assign('metaTitle', $que['name']);
		$this->display();
	}
	
	/**
	 *  查看已经回答的问题
	 */
	public function detail()
	{
		$nextqid = isset($_GET['nextqid']) ? intval($_GET['nextqid']) : 0;
		$que = array();
		if ($nextqid) {
			$question = M('Research_question')->where(array('rid' => $this->_rid, 'id' => array('egt', $nextqid)))->order('id asc')->limit(2)->select();
			$que = $question[0];
			$nextqid = isset($question[1]['id']) ? $question[1]['id'] : 0;
		} else {
			$question = M('Research_question')->where(array('rid' => $this->_rid))->order('id asc')->limit(2)->select();
			$que = $question[0];
			$nextqid = isset($question[1]['id']) ? $question[1]['id'] : 0;
		}
		
		if ($que) {
			$answer = M('Research_answer')->where(array('qid' => $que['id']))->order('id asc')->select();
			$reuslt = M('Research_result')->where(array('qid' => $que['id'], 'wecha_id' => $this->wecha_id))->find();
			$aids = array();
			if (isset($reuslt['aids']) && $reuslt['aids']) {
				$aids = explode(",", $reuslt['aids']);
			}
			foreach ($answer as &$row) {
				$row['select'] = 0;
				if (in_array($row['id'], $aids)) {
					$row['select'] = 1;
				}
			}
		}
		
		$maxsel = $que['type'] ? count($answer) : 1;
		$this->assign('question', $que);
		$this->assign('nextqid', $nextqid);
		$this->assign('maxsel', $maxsel);
		$this->assign('answer', $answer);
		$this->assign('metaTitle', $que['name']);
		$this->display();
	}
	
	public function lotter()
	{
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$que = $qids = array();
		$finishcount = M('Research_result')->where(array('rid' => $this->_rid, 'wecha_id' => $this->wecha_id))->count();
		$questioncount = M('Research_question')->where(array('rid' => $this->_rid))->count();
		if ($finishcount < $questioncount) {
			$this->redirect(U("Research/index", array('reid' => $this->_rid, 'wecha_id' => $this->wecha_id, 'token' => $this->token)));
		}
		$token		= $this->token;
		$wecha_id	= $this->wecha_id;
		$id 		= $this->_research['lid'];
		if (empty($id)) {
			$this->redirect(U("Research/index", array('reid' => $this->_rid, 'wecha_id' => $this->wecha_id, 'token' => $this->token)));
		}
		$redata	  = M('Lottery_record');
		$where	  = array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id);
		$record 	= $redata->where(array('token'=>$token,'wecha_id'=>$wecha_id,'lid'=>$id,'islottery'=>1))->find();
		if (!$record){
			$record 	= $redata->where($where)->order('id DESC')->find();
		}
		if (!$record){
			$record['id']=0;
			$record['lid']=$id;
		}
		$record['wecha_id']=$wecha_id;
		$this->assign('record',$record);
		$Lottery =	M('Lottery')->where(array('id'=>$id,'token'=>$token,'type'=>6))->find(); 
		if ($this->wecha_id&&!$this->fans&&$Lottery['needreg']){
			$this->error('请先完善个人资料再参加活动',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>MODULE_NAME.'/index|id:'.intval($id))));
		}
		
		$Lottery['renametel']=$Lottery['renametel']?$Lottery['renametel']:'手机号';
		$Lottery['renamesn']=$Lottery['renamesn']?$Lottery['renamesn']:'SN码';
		$data = $Lottery;
		$data['info']=nl2br($data['info']);
		$data['endinfo']=nl2br($data['endinfo']);
		$data['info']=str_replace('&lt;br&gt;','<br>',$data['info']);
		$data['endinfo']=str_replace('&lt;br&gt;','<br>',$data['endinfo']);
		$this->assign('Research',$data);
		//
		$return=$this->prizeHandle($token,$wecha_id,$Lottery);
		//
		if ($return['end']==2){//过期
			$data['usenums'] = 3;
			$data['endinfo'] = $Lottery['endinfo'];
			$this->assign('Research',$data);
			$this->display();
			exit();
		}
		if ($return['end']==3){//中过奖了，抽奖次数已经用完
			$data['usenums'] = 2;
			$data['sncode']	 = $record['sn'];
			$data['uname']	 = $record['wecha_name'];
			$data['winprize']	= $this->getPrizeName($Lottery,$record['prize']);
		}else {
			if ($return['end']==-1) {//抽奖次数已经用完
				//次数已经达到限定
				$data['usenums'] = 1;
				$data['winprize']	= '抽奖次数已用完';
			} elseif ($return['end']==-2) {//
				//次数已经达到限定
				$data['usenums'] = 1;
				$data['winprize']	= '当天次数已用完';
			} else {
				$data['zjl'] 		= $return['zjl'];
				$data['wecha_id']	= $wecha_id;
				$data['lid']		= $id;
				$data['winprize']	= $this->getPrizeName($Lottery,$return['winprize']);
			}
		}
		

		$data['usecout'] 	= intval($record['usenums']);
		$data['zjl'] = isset($data['zjl']) ? $data['zjl'] : 0;
		$this->assign('Research',$data);

		$prizeStr='<p>一等奖: '.$Lottery['fist'];
		if ($Lottery['displayjpnums']){
			$prizeStr.='奖品数量:'.$Lottery['fistnums'];
		}
		$prizeStr.='</p>';
		if ($Lottery['second']){
			$prizeStr.='<p>二等奖: '.$Lottery['second'];
			if ($Lottery['displayjpnums']){
				$prizeStr.='奖品数量:'.$Lottery['secondnums'];
			}
			$prizeStr.='</p>';
		}
		if ($Lottery['third']){
			$prizeStr.='<p>三等奖: '.$Lottery['third'];
			if ($Lottery['displayjpnums']){
				$prizeStr.='奖品数量:'.$Lottery['thirdnums'];
			}
			$prizeStr.='</p>';
		}
		$this->assign('prizeStr',$prizeStr);
		$this->display();
	
		
	}
}
?>