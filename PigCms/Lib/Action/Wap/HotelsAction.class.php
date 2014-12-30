<?php
class HotelsAction extends WapAction {
	
	//public $token;
	
	//public $wecha_id = '';
	
        public $session_dish_info;
	public $session_dish_user;
	public $_cid = 0;
	
	public $offset = 8;
	
	public function _initialize(){
		parent::_initialize();
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if (!strpos($agent, "MicroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		
		//$this->token = isset($_REQUEST['token']) ? $_REQUEST['token'] : session('token');//$this->_get('token');
		
		//$this->assign('token', $this->token);
		//$this->wecha_id	= isset($_REQUEST['wecha_id']) ? $_REQUEST['wecha_id'] : '';
		//$this->assign('wecha_id', $this->wecha_id);
		
		$this->_cid = $_SESSION["session_hotel_{$this->token}"];
		$this->assign('cid', $this->_cid);
		
		$this->session_dish_info = "session_hotel_{$this->_cid}_info_{$this->token}";
		$this->session_dish_user = "session_hotel_{$this->_cid}_user_{$this->token}";
		
		$this->assign('totalDishCount', $count);
	}
	
	/**
	 * 酒店分布
	 */
	public function index() {
		$company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();
		if (count($company) == 1) {
			$this->redirect(U('Hotels/selectdate',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $company[0]['id'])));
		}
		$price = M('Hotels_house_sort')->field('min(vprice) as price, cid')->group('cid')->where(array('token' => $this->token))->select();
		$t = array();
		foreach ($price as $row) {
			$t[$row['cid']]	= $row['price'];
		}
		$list = array();
		foreach ($company as $c) {
			if (isset($t[$c['id']])) {
				$c['price'] = $t[$c['id']];
			} else {
				$c['price'] = 0;
			}
			$list[] = $c;
		}
		$this->assign('company', $list);
		$this->assign('metaTitle', '酒店分布');
		$this->display();
	}
	
        public function selectdate(){
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
		if ($company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find()) {
			$_SESSION["session_hotel_{$this->token}"] = $cid;
		} else {
			$this->redirect(U('Hotels/index',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
		}
		$dates = array();
		$dates[] = array('k' => date("Y-m-d"), 'v' => date("m月d日"));
		for ($i = 1; $i <= 90; $i ++) {
			$dates[] = array('k' => date("Y-m-d", strtotime("+{$i} days")), 'v' => date("m月d日", strtotime("+{$i} days")));
		}
		
		$this->assign('dates', $dates);
		$this->assign('metaTitle', '在线预订客房');
		$this->display();
	}
	
        public function hotel(){
		$in = isset($_GET['check_in_date']) ? htmlspecialchars($_GET['check_in_date']) : '';
		$out = isset($_GET['check_out_date']) ? htmlspecialchars($_GET['check_out_date']) : '';
		
		$days = (strtotime($out) - strtotime($in)) / 86400;
		if ($days < 1) {
			$this->redirect(U('Hotels/selectdate',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
		}
		
		$in = date("Ymd", strtotime($in));
		$out = date("Ymd", strtotime($out));
		
		$company = M('Company')->where(array('id' => $this->_cid))->find();
		
		$sorts = M('Hotels_house_sort')->where(array('cid' => $this->_cid, 'token' => $this->token))->select();
		
		$order = M('Hotels_order')->field('sum(nums) as num, sid')->group('sid')->where(array('startdate' => array('ELT', $in), 'enddate' => array('GT', $in), 'token' => $this->token, 'cid' => $this->_cid, 'status' => array('ELT', 1)))->select();
		$t = array();
		foreach ($order as $o) {
			$t[$o['sid']] = $o['num'];
		}
		
		$imagelist = M("hotels_image")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
//		$houses = M('Hotels_house')->where(array('cid' => $this->_cid, 'token' => $this->token))->select();
//		$h = array();
//		foreach ($houses as $row) {
//			$h[$row['sid']][] = $row;
//		}
		$list = array();
		foreach ($sorts as $s) {
			$s['useHouse'] = isset($t[$s['id']]) ? $t[$s['id']] : 0;
			//$s['houseslist'] = isset($h[$s['id']]) ? $h[$s['id']] : array();
			$list[] = $s;
		}
		$this->assign('count', count($imagelist));
		$this->assign('imagelist', $imagelist);
		$this->assign('company', $company);
		$this->assign('sday', date("m月d日", strtotime($in)));
		$this->assign('eday', date("m月d日", strtotime($out)));
		$this->assign('startdate', $in);
		$this->assign('enddate', $out);
		$this->assign('days', $days);
		$this->assign('list', $list);
		$this->assign('metaTitle', '在线预订客房');
		$this->display();
		
	}
	
        public function order(){
		$in = isset($_GET['startdate']) ? htmlspecialchars($_GET['startdate']) : '';
		$out = isset($_GET['enddate']) ? htmlspecialchars($_GET['enddate']) : '';
		$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
		$days = (strtotime($out) - strtotime($in)) / 86400;
		if ($days < 1) {
			$this->redirect(U('Hotels/selectdate',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
		}
		if ($sort = M('Hotels_house_sort')->where(array('cid' => $this->_cid, 'token' => $this->token, 'id' => $sid))->find()) {
			if ($this->fans['getcardtime'] > 0) {
				$sort['price'] = $sort['vprice'] ? $sort['vprice'] : $sort['price'];
			}
			//是否要支付
			$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			$this->assign('alipayConfig', $alipayConfig);
			$company = M('Company')->where(array('id' => $this->_cid))->find();
			$this->assign('company', $company);
			$this->assign('sort', $sort);
			$this->assign('sday', date("m月d日", strtotime($in)));
			$this->assign('eday', date("m月d日", strtotime($out)));
			$this->assign('startdate', $in);
			$this->assign('enddate', $out);
			$this->assign('days', $days);
			$this->assign('total', $days * $sort['price']);
			$this->assign('metaTitle', '在线预订客房');
			$this->display();
		}
	}
	
	/**
	 * 提交订单
	 */
        public function saveorder(){
		$dataBase = D('Hotels_order');
		if (IS_POST) {
			$price = 0;
			if ($sort = M('Hotels_house_sort')->where(array('cid' => $this->_cid, 'token' => $this->token, 'id' => $_POST['sid']))->find()) {
				if ($this->fans['getcardtime'] > 0) {
					$price = $sort['vprice'] ? $sort['vprice'] : $sort['price'];
				} else {
					$price = $sort['price'];
				}
			}
			$days = (strtotime($_POST['enddate']) - strtotime($_POST['startdate'])) / 86400;
			$sday = date("Y年m月d日", strtotime($_POST['startdate']));
			$eday = date("Y年m月d日", strtotime($_POST['enddate']));
			if ($_POST['startdate'] < date("Ymd") || $days < 1) {
				$this->error('您预定的时间不正确');
			}
			//处理预定房间的数量
			$in = date("Ymd", strtotime($_POST['startdate']));
			$order = M('Hotels_order')->field('sum(nums) as num')->where(array('startdate' => array('ELT', $in), 'enddate' => array('GT', $in), 'token' => $this->token, 'cid' => $this->_cid, 'sid' => $_POST['sid'], 'status' => array('ELT', 1)))->find();
			$oldnum = isset($order['num']) ? $order['num'] : 0;
			$total = $_POST['nums'] + $oldnum;
			$hotelSort = M("Hotels_house_sort")->where(array('id' => $_POST['sid'], 'token' => $this->token))->find();
			if ($total > $hotelSort['houses']) {
				$this->error('您预定的房间数超出总房间数了');
			}
			
			$_POST['orderid'] = $orderid = substr($this->wecha_id, -1, 4) . date("YmdHis");
			$_POST['price'] = $_POST['nums'] * $days * $price;
			$_POST['time'] = time();
		
	
			//保存个人信息
			$userinfo_model = M('Userinfo');
			$thisUser = $userinfo_model->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
			if (empty($thisUser)){
				$userRow = array('tel' => $_POST['tel'], 'truename' => $_POST['name'], 'address' => '');
				$userRow['token'] = $this->token;
				$userRow['wecha_id'] = $this->wecha_id;
				$userRow['wechaname'] = '';
				$userRow['qq'] = 0;
				$userRow['sex'] = -1;
				$userRow['age'] = 0;
				$userRow['birthday'] = '';
				$userRow['info'] = '';
	
				$userRow['total_score'] = 0;
				$userRow['sign_score'] = 0;
				$userRow['expend_score'] = 0;
				$userRow['continuous'] = 0;
				$userRow['add_expend'] = 0;
				$userRow['add_expend_time'] = 0;
				$userRow['live_time'] = 0;
				$userinfo_model->add($userRow);
			}
			if ($dataBase->create() !== false) {
				$action = $dataBase->add();
				if ($action != false ) {
					$company = M('Company')->where(array('id' => $this->_cid, 'token' => $this->token))->find();
					$op = new orderPrint();
					$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $_POST['name'], 'tel' => $_POST['tel'], 'address' => '', 'buytime' => time(), 'orderid' => $_POST['orderid'], 'sendtime' => '', 'price' => $_POST['price'], 'total' => $_POST['nums'], 'list' => array(array('name' => $sort['name'], 'day' => $days, 'price' => $price, 'num' => $_POST['nums'])));
					$msg = ArrayToStr::array_to_str($msg);
					$op->printit($this->token, $this->_cid, 'Hotel', $msg, 0);
					
					Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$_POST['name']}刚刚预定了{$sday}到{$eday}，{$days}天的{$sort['name']}，请您注意查看并处理");
// 					$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
// 					if ($_POST['paymode'] == 1 && $alipayConfig['open']) {
// 						$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'from'=> 'Hotels', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $_POST['price'])));
// 					} elseif ($_POST['paymode'] == 4 && $this->fans['balance']) {
// 						$this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'from'=> 'Hotels', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $_POST['price'])));
// 					} else {
// 						$model = new templateNews();
// 						$model->sendTempMsg('TM00820', array('href' => U('Hotels/my',array('token' => $this->token, 'wecha_id' => $this->wecha_id)), 'wecha_id' => $this->wecha_id, 'first' => '预订房间提醒', 'keynote1' => '订单未支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '预订房间成功，感谢您的光临，欢迎下次再次光临！'));
// 						$this->success('预定成功,进入您的订单页', U('Hotels/my',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
// 					}
					if ($_POST['paymode'] == 4 && $this->fans['balance']) {
						$this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'from'=> 'Hotels', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $_POST['price'])));
					} else {
						$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'from'=> 'Hotels', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $_POST['price'])));
					}
				} else {
					$this->error('操作失败');
				}
			} else {
				$this->error($dataBase->getError());
			}
		}
	}
	
	/**
	 * 我的订单
	 */
        public function my(){
		$company = M('Company')->where(array('id' => $this->_cid, 'token' => $this->token))->find();
		$orders = M('Hotels_order')->where(array('cid' => $this->_cid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'status' => array('lt', 2)))->order('id desc')->limit($this->offset)->select();
		$list = array();
		foreach ($orders as $o) {
			$o['day'] = (strtotime($o['enddate']) - strtotime($o['startdate'])) / 86400;
			$o['startdate'] = date("m月d日", strtotime($o['startdate']));
			$o['enddate'] = date("m月d日", strtotime($o['enddate']));
			$list[] = $o;
		}
		$count = M('Hotels_order')->where(array('cid' => $this->_cid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'status' => array('lt', 2)))->count();
		$totalpage = ceil($count / $this->offset);
		$this->assign('totalpage', $totalpage);
		$this->assign('company', $company);
		$this->assign('list', $list);
		$this->assign('metaTitle', '我的订单');
		$this->display();
	}
	
        public function ajaxorder(){
		$company = M('Company')->where(array('id' => $this->_cid, 'token' => $this->token))->find();
		$page = isset($_GET['page']) && intval($_GET['page']) > 1 ? intval($_GET['page']) : 2;
		$start =($page-1) * $this->offset;
		$orders = M('Hotels_order')->where(array('cid' => $this->_cid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'status' => array('lt', 2)))->order('id desc')->limit($start . ', ' . $this->offset)->select();
		$list = array();
		foreach ($orders as $o) {
			$o['day'] = (strtotime($o['enddate']) - strtotime($o['startdate'])) / 86400;
			$o['startdate'] = date("m月d日", strtotime($o['startdate']));
			$o['enddate'] = date("m月d日", strtotime($o['enddate']));
			$o['hotelname'] = $company['name'];
			$list[] = $o;
		}
		
		$count = M('Hotels_order')->where(array('cid' => $this->_cid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'status' => array('lt', 2)))->count();
		
		$totalpage = ceil($count / $this->offset);
		$page = $totalpage > $page ? intval($page + 1) : 0;
		exit(json_encode(array('page' => $page, 'data' => $list)));
	}
	
	/**
	 * 订单详情
	 */
        public function detail(){
		$id = isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		if ($order = M('Hotels_order')->where(array('cid' => $this->_cid, 'token' => $this->token, 'id' => $id))->find()) {
			$company = M('Company')->where(array('id' => $this->_cid))->find();
			$order['startdate'] = date("m月d日", strtotime($order['startdate']));
			$order['enddate'] = date("m月d日", strtotime($order['enddate']));
			$sort = M('Hotels_house_sort')->where(array('cid' => $this->_cid, 'token' => $this->token, 'id' => $order['sid']))->find();
			$order['housename'] = isset($sort['name']) ? $sort['name'] : '';
			$this->assign('company', $company);
			$this->assign('order', $order);
			$this->assign('metaTitle', '订单详情');
			$this->display();
		} else {
			$this->redirect(U('Hotels/my',array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
		}
	}
	
	/**
	 * 支付成功后的回调函数
	 */
	public function payReturn() {
	   $orderid = $_GET['orderid'];
	   if ($order = M('Hotels_order')->where(array('orderid' => $orderid, 'token' => $this->token))->find()) {
			//TODO 发货的短信提醒
			if ($order['paid']) {
				$sort = M('Hotels_house_sort')->where(array('id' => $order['sid'], 'token' => $this->token))->find();
				$days = (strtotime($order['enddate']) - strtotime($order['startdate'])) / 86400;
				$price = $this->fans['getcardtime'] > 0 ? ($sort['vprice'] ? $sort['vprice'] : $sort['price']) : $sort['price'];
				$company = M('Company')->where(array('id' => $order['cid'], 'token' => $this->token))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $order['name'], 'tel' => $order['tel'], 'address' => '', 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'sendtime' => '', 'price' => $order['price'], 'total' => $order['nums'], 'list' => array(array('name' => $sort['name'], 'day' => $days, 'price' => $price, 'num' => $order['nums'])));
				$msg = ArrayToStr::array_to_str($msg, 1);
				$op->printit($this->token, $this->_cid, 'Hotel', $msg, 1);

				Sms::sendSms($this->token . "_" . $order['cid'], "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
				$model = new templateNews();
				$model->sendTempMsg('TM00820', array('href' => U('Hotels/my',array('token' => $this->token, 'wecha_id' => $this->wecha_id)), 'wecha_id' => $this->wecha_id, 'first' => '预订房间提醒', 'keynote1' => '订单已支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '预订房间成功，感谢您的光临，欢迎下次再次光临！'));
				
			}
			$this->redirect(U('Hotels/my', array('token'=>$this->token, 'wecha_id' => $this->wecha_id)));
	   }else{
	      exit('订单不存在');
	    }
	}
	
        public function cancel(){
		$status = isset($_GET['status']) ? $_GET['status'] : 0;
		$oid = isset($_GET['oid']) ? intval($_GET['oid']) : 0;
		if ($order = M('Hotels_order')->where(array('id' => $oid, 'wecha_id' => $this->wecha_id))->find()) {
			$status = $order['paid'] ? 3 : 2;// 2：取消订单，3：删除订单
			D("Hotels_order")->where(array('id' => $oid, 'wecha_id' => $this->wecha_id))->save(array('status' => $status));
			exit(json_encode(array('error_code' => false, 'msg' => 'ok')));
		}
		exit(array('error_code' => true, 'msg' => '不合法的操作！'));
	}
}
?>