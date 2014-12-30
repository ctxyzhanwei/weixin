<?php
class RepastAction extends WapAction{
    public $token;
    public $wecha_id = '';
    public $session_dish_info;
    public $session_dish_user;
    public $_cid = 0;
    private $_sms_auth_code = '';//下订单的短信验证
    public function _initialize(){ 
        parent::_initialize();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($agent, "MicroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
        }
        $this->token = isset($_REQUEST['token']) ? $_REQUEST['token'] : session('token');
        $this->assign('token', $this->token);
        $this->wecha_id = isset($_REQUEST['wecha_id']) ? $_REQUEST['wecha_id'] : '';
        if (!$this -> wecha_id){
            $this -> wecha_id = '';
        }
        $this->assign('wecha_id', $this->wecha_id);
        $this->_cid = $_SESSION["session_company_{$this->token}"];
        $this->assign('cid', $this->_cid);
        $this->session_dish_info = "session_dish_{$this->_cid}_info_{$this->token}";
        $this->session_dish_user = "session_dish_{$this->_cid}_user_{$this->token}";
        $menu                    = $this->getDishMenu();
        $count                   = count($menu);
        $this->assign('totalDishCount', $count);
    }
    /**
    * 餐厅分布
    */
    public function index(){       
        $data = M('dish_company');
        $list = $data->select();  
	$id_arr = array();
        foreach ($list as $row) {  
            $id_arr[] = $row['cid'];
        }   
        
        $company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();

        $company_new = array();
        foreach ($company as $row) {
                if (in_array($row['id'], $id_arr)) {
                        $company_new[] = $row;
                }
        }		
				$company = $company_new;
        
        if (count($company) == 1) {
            $this -> redirect(U('Repast/select', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $company[0]['id'])));
        }
        $this->assign('company', $company);
        $this->assign('metaTitle', '餐厅分布');
        $this->display();
    }
    /**
    *就餐形式选择页 
    */
    public function select() {
            unset($_SESSION[$this -> session_dish_user]);
            unset($_SESSION['david_add_dishset']);
            unset($_SESSION['david_add_mymenuset']);
            
            unset($_SESSION['david_add_takeaway']);
            unset($_SESSION['david_add_token']);
            unset($_SESSION['david_add_wecha_id']);            
            
        $istakeaway = 0;
        $cid        = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

        if ($company = M('Company') -> where(array('token' => $this -> token, 'id' => $cid)) -> find()){
            $_SESSION["session_company_{$this->token}"] = $cid;
        } else {
            $this -> redirect(U('Repast/index', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id)));
        }
        if ($dishCompany = M('Dish_company') -> where(array('cid' => $cid)) -> find()){
            $istakeaway = $dishCompany['istakeaway'];
        }
        $this->assign('istakeaway', $istakeaway);
        $this->assign('metaTitle', '点餐选择');
        $this->display();
    }
    /**
    * 餐厅介绍
    */
    public function virtual(){
        $cid     = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $company = M('Company') -> where(array('token' => $this -> token, 'id' => $cid)) -> find();
        $this->assign('company', $company);
        $this->assign('metaTitle', '餐厅介绍');
        $this->display();
    }
    /**
    * 选取餐桌与填写个人信息
    */
    public function selectTable(){//to Repast/saveUser           dish-> 'token' => $this -> token, 'id' => $this -> _cid)   
       // echo $_SESSION[$this -> session_dish_user];
        $takeaway                           = isset($_GET['takeaway']) ? intval($_GET['takeaway']) : 0;
        if (isset($_SESSION['david_add_dishset']) || $_SESSION[$this -> session_dish_user] == 's:8:"wait_msg";') {
            $_SESSION['david_add_mymenuset'] = '1';
            $_GET['takeaway'] = $_SESSION['david_add_takeaway'];
            $this -> token = $_SESSION['david_add_token'];
            $this -> wecha_id = $_SESSION['david_add_wecha_id'];
        } else {//先选择菜单再写电话等信息
            $_SESSION[$this -> session_dish_user] = 's:8:"wait_msg";';
            // unserialize($_SESSION[$this -> session_dish_user]);exit;
            $_SESSION['david_add_takeaway'] = $takeaway;
            $_SESSION['david_add_token'] = $this -> token;      
            $_SESSION['david_add_wecha_id'] = $this -> wecha_id;                 
            $this -> redirect(U('Repast/dish', array('token' => $this -> token, 
                                                      'wecha_id' => $this -> wecha_id,
                                                     'id' => $this -> _cid)));            
        }
        $thisUser = M('Userinfo') -> where(array('token' => $this -> token, 'wecha_id' => $this -> wecha_id)) -> find();
        $this -> assign('thisUser', $thisUser);
        $takeaway = isset($_GET['takeaway']) ? intval($_GET['takeaway']) : 0; //2-现场点餐 0-在线预订
        //$_SESSION[$this -> session_dish_user] = null;
       // unset($_SESSION[$this -> session_dish_user]);
        $time       = time();
        $orderTable = M('Dish_table') -> where(array('reservetime' => array('elt', $time + 2 * 3600), 'reservetime' => array('egt', $time - 2 * 3600), 'cid' => $this -> _cid, 'isuse' => 0)) -> select();
        $tids       = array();
        foreach ($orderTable as $row) {
            $tids[] = $row['tableid'];
        }
        if ($tids) {
            $table = M('Dining_table') -> where(array('id' => array('not in', $tids), 'cid' => $this -> _cid)) -> select();
        }else{
            $table = M('Dining_table') -> where(array('cid' => $this -> _cid)) -> select();
        }
        $dates   = array();
        $dates[] = array('k' => date("Y-m-d"), 'v' => date("m月d日"));
        for ($i = 1; $i <= 90; $i++) {
            $dates[] = array('k' => date("Y-m-d", strtotime("+{$i} days")), 'v' => date("m月d日", strtotime("+{$i} days")));
        }
        $hours = array();
        for ($i = 0; $i < 24; $i++) {
            $hours[] = array('k' => $i, 'v' => $i . "时");
        }
        $seconds = array();
        for ($i = 0; $i < 60; $i++) {
            $seconds[] = array('k' => $i, 'v' => $i . "分");
        }
        
        $dishCompany = M('Dish_company') -> where(array('cid' => $this -> _cid)) -> find();
        $this -> assign('phone_authorize', $dishCompany['phone_authorize']);
        
        $this->assign('dates', $dates);
        $this->assign('seconds', $seconds);
        $this->assign('hours', $hours);
        $this->assign('takeaway', $takeaway);
        $this->assign('tables', $table);
        $this->assign('metaTitle', '填写个人信息');
        $this->assign('time', date("Y-m-d H:i:s"));
        $this->display();
    }
    /**
    * ajax请求获取餐桌信息
    */
    public function getTable(){
        $date       = isset($_POST['redate']) ? htmlspecialchars($_POST['redate']) : '';
        $hour       = isset($_POST['rehour']) ? htmlspecialchars($_POST['rehour']) : '';
        $second     = isset($_POST['resecond']) ? htmlspecialchars($_POST['resecond']) : '';
        $time       = strtotime($date . ' ' . $hour . ':' . $second . ':00');
        $orderTable = M('Dish_table') -> where(array('reservetime' => array('elt', $time + 2 * 3600), 'reservetime' => array('egt', $time - 2 * 3600), 'cid' => $this -> _cid, 'isuse' => 0)) -> select();
        $tids       = array();
        foreach ($orderTable as $row) {
            $tids[] = $row['tableid'];
        }
        if ($tids) {
            $table = M('Dining_table') -> where(array('id' => array('not in', $tids), 'cid' => $this -> _cid)) -> select();
        }else{
            $table = M('Dining_table') -> where(array('cid' => $this -> _cid)) -> select();
        }
        exit(json_encode($table));
    }
    /**
     * 取短信验证码在下订单时
     */
    public function get_sms_auth_code() {
        if ($_POST['tel']) {
            $this->_sms_auth_code = rand(100000, 999999);
            $res = Sms :: sendSms($this -> token . "_" . $this -> _cid, "您的订餐短信验证码是". $this->_sms_auth_code ."请妥善保管", $_POST['tel']);            
            exit(json_encode(array('success' => 1, 'msg' => $this->_sms_auth_code)));
        } else {
            exit(json_encode(array('success' => 0, 'msg' => '电话号码不能为空')));
        }              
    }    
    public function saveUser(){//保存订单  //2-现场点餐 0-在线预订 1-外卖（店铺上设置）
        if ($_POST['phone_authorize'] == 1 && $_POST['tel_auth_code'] != $_POST['tel_auth_code_ajax']) {
            exit(json_encode(array('success' => 0, 'msg' => '您的手机短信验证码错误，不能订餐!')));            
        }        
        $takeaway = isset($_POST['takeaway']) ? intval($_POST['takeaway']) : 0;
        $tel      = $table = $address = $des = $name = '';
        $sex      = $nums = 1;
        $price    = 0;
        if ($takeaway == 1) {
            $dishCompany = M('Dish_company') -> where(array('cid' => $this -> _cid)) -> find();
            if (isset($dishCompany['istakeaway']) && $dishCompany['istakeaway']) $price = $dishCompany['price'];
        }
        if ($takeaway != 2) {
            $tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
            if (empty($tel)) {
                exit(json_encode(array('success' => 0, 'msg' => '电话号码不能为空')));
            }
            $name = isset($_POST['guest_name']) ? $_POST['guest_name'] : '';
            if (empty($name)) {
                exit(json_encode(array('success' => 0, 'msg' => '姓名不能为空')));
            }
            $address     = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
            $sex         = isset($_POST['sex']) ? intval($_POST['sex']) : 0;
            $date        = isset($_POST['redate']) ? htmlspecialchars($_POST['redate']) : '';
            $hour        = isset($_POST['rehour']) ? htmlspecialchars($_POST['rehour']) : '';
            $second      = isset($_POST['resecond']) ? htmlspecialchars($_POST['resecond']) : '';
            $reservetime = strtotime($date . ' ' . $hour . ':' . $second . ':00');
            if ($reservetime < time()) {
                exit(json_encode(array('success' => 0, 'msg' => '预约用餐时间不可以小于当前时间')));
            }
            $nums = isset($_POST['nums']) ? intval($_POST['nums']) : 1;
        } else {
            $reservetime = time() + 600;
        }
        $table                              = isset($_POST['table']) ? intval($_POST['table']) : 0;
        $des                                = isset($_POST['remark']) ? htmlspecialchars($_POST['remark']) : '';
        $data = array('tableid' => $table, 'tel' => $tel, 'takeaway' => $takeaway, 'address' => $address, 'name' => $name, 'sex' => $sex, 'reservetime' => $reservetime, 'price' => $price, 'nums' => $nums, 'des' => $des);
        $_SESSION[$this->session_dish_user] = serialize($data);
        exit(json_encode(array('success' => 1, 'msg' => 'ok')));
        //repast_selecttable 成功后- window.location = "{pigcms::U('Repast/dish', array('token'=>$token, 'wecha_id' => $wecha_id, 'cid' => $cid))}";
    }
    /**
    * 点餐页
    */
    public function dish(){
        $company = M('Company') -> where(array('token' => $this -> token, 'id' => $this -> _cid)) -> find();
        $userInfo = unserialize($_SESSION[$this->session_dish_user]);
        if (empty($userInfo)) {
            $this -> redirect(U('Repast/select', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $this -> _cid)));
        }
        if (isset($_SESSION['david_add_mymenuset'])) {            
            $this -> redirect(U('Repast/mymenu', array('token' => $this -> token, 
                'wecha_id' => $this->wecha_id,
                                                        'id' => $this -> _cid)));             
        }
        $this->assign('metaTitle', $company['name']);
        $this->display();
    }
    /**
    * 菜单列表
    */
    public function GetDishList(){
        $company = M('Company') -> where(array('token' => $this -> token, 'id' => $this -> _cid)) -> find();
        $dish_sort = M('Dish_sort') -> where(array('cid' => $this -> _cid)) -> select();
        $dish = M('Dish') -> where(array('cid' => $this -> _cid)) -> select();
        $dish_like = M('Dish_like') -> where(array('cid' => $this -> _cid, 'wecha_id' => $this -> wecha_id)) -> select();
        $like      = array();
        foreach ($dish_like as $dl) {
            $like[$dl['did']] = 1;
        }
        $mymenu = $this->getDishMenu();
        $list   = array();
        foreach ($dish as $d) {
            $t                   = array();
            $t['id']             = $d['id'];
            $t['aid']            = $d['cid'];
            $t['name']           = $d['name'];
            $t['price']          = $d['price'];
            $t['discount_name']  = '';
            $t['discount_price'] = '';
            $t['class_id']       = $d['sid'];
            $t['pic']            = $d['image'];
            $t['note']           = $d['des'];
            $t['unit']           = $d['unit'];
            $t['tag_name']       = $d['ishot'] ? '推荐' : '';
            $t['html_name']      = '';
            $t['check']          = isset($like[$d['id']]) ? $like[$d['id']] : 0;
            $t['select']         = isset($mymenu[$d['id']]) ? 1 : 0;
            $list[$d['sid']][]   = $t;
        }
        $result = array();
        foreach ($dish_sort as $sort) {
            $r           = array();
            $r['id']     = $sort['id'];
            $r['aid']    = $sort['cid'];
            $r['name']   = $sort['name'];
            $r['dishes'] = isset($list[$sort['id']]) ? $list[$sort['id']] : '';
            $result[]    = $r;
        }
        exit(json_encode($result));
    }
    /**
    * 对某个菜进行喜欢标记操作
    */
    public function dolike(){
        if (empty($this->wecha_id)) {
            exit(json_encode(array('status' => 0)));
        }
        $id    = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $check = isset($_POST['check']) ? intval($_POST['check']) : 0;
        if ($id) {
            $dishLike = D('Dish_like');
            $data = array('did' => $id, 'cid' => $this -> _cid, 'wecha_id' => $this -> wecha_id);
            if ($check) {
                $dishLike->add($data);
            } else {
                $dishLike->where($data)->delete();
                exit(json_encode(array('status' => 1)));
            }
        }
        exit(json_encode(array('status' => 0)));
    }
	/**
	 * 喜欢餐店中的某些菜的列表
	 */
    public function like(){
        if ($this->wecha_id) {
            $mymenu    = $this->getDishMenu();
            $dish_like = M('Dish_like') -> where(array('cid' => $this -> _cid, 'wecha_id' => $this -> wecha_id)) -> select();
            $dids      = array();
            foreach ($dish_like as $like) {
                $dids[] = $like['did'];
            }
            $dish = array();
            if ($dids) {
                $list = M('Dish') -> where(array('id' => array('in', $dids), 'cid' => $this -> _cid)) -> select();
                foreach ($list as $row) {
                    $row['select'] = isset($mymenu[$row['id']]) ? 1 : 0;
                    $dish[]        = $row;
                }
            }
        } else {
            $dish = array();
        }
        $this->assign('dishlist', $dish);
        $this->assign('metaTitle', '我喜欢的菜');
        $this->display();
    }
    /**
    * 点餐操作
    */
    public function editOrder(){
        $id  = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $num = isset($_POST['num']) ? intval($_POST['num']) : 0;
        $des = isset($_POST['des']) ? htmlspecialchars($_POST['des']) : '';
        if ($id) {
            $oldMenu = $this->getDishMenu();
            if (isset($oldMenu[$id])) {
                $oldMenu[$id]['des'] = $des ? $des : $oldMenu[$id]['des'];
                $oldMenu[$id]['num'] += $num;
                if ($oldMenu[$id]['num'] == 0) {
                    unset($oldMenu[$id]);
                }
            } elseif ($num > 0) {
                $oldMenu[$id]['des'] = $des;
                $oldMenu[$id]['num'] = $num;
            }
            $_SESSION[$this->session_dish_info] = serialize($oldMenu);
        }
    }
    /**
    * 我的菜单（我的购物车）
    */
    public function mymenu(){
        if (unserialize($_SESSION[$this -> session_dish_user]) == 'wait_msg') {
            $_SESSION['david_add_dishset'] = 1;
            $this -> redirect(U('Repast/selectTable', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id,
                                                            'id' => $this -> _cid)));               
        }
        if (isset($_SESSION['david_add_mymenuset'])) {
            unset($_SESSION['david_add_dishset']);
        } else {
            //$_SESSION['david_add_dishset'] = 1;
            $this -> redirect(U('Repast/selectTable', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id,
                                                            'id' => $this -> _cid)));             
        }
                    
        $userInfo = unserialize($_SESSION[$this->session_dish_user]);
       
        if (empty($userInfo)) {
            $this -> error('没有填写用餐信息，先填写信息，再提交订单！', U('Repast/select', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $this -> _cid)));
        }
        $menu     = $this->getDishMenu();
        $data     = array();
        $totalNum = $totalPrice = 0;
        if ($menu) {
            $dids     = array_keys($menu);
            $dishList = M('Dish') -> where(array('cid' => $this -> _cid, 'id' => array('in', $dids))) -> select();
            foreach ($dishList as $dish) {
                if (isset($menu[$dish['id']])) {
                    $totalNum += $menu[$dish['id']]['num'];
                    $totalPrice += $menu[$dish['id']]['num'] * $dish['price'];
                    $r          = array();
                    $r['id']    = $dish['id'];
                    $r['name']  = $dish['name'];
                    $r['price'] = $dish['price'];
                    $r['unit'] = $dish['unit'];
                    $r['nums']  = $menu[$dish['id']]['num'];
                    $r['des']   = $menu[$dish['id']]['des'];
                    $data[]     = $r;
                }
            }
        }
        $tableName = '';
        if ($userInfo['tableid']) {
            $diningTable = M('Dining_table') -> where(array('cid' => $this -> _cid, 'id' => $userInfo['tableid'])) -> find();
            $tableName   = isset($diningTable['name']) && isset($diningTable['isbox']) ? ($diningTable['isbox'] ? $diningTable['name'] . '(包厢' . $diningTable['num'] . '座)' : $diningTable['name'] . '(大厅' . $diningTable['num'] . '座)') : '';
        }
        $_SESSION['mymenu_order_tablename'] = $tableName;
        $company = M('Dish_company') -> where(array('cid' => $this -> _cid)) -> find();
        $alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
	$this->assign('company', $company);
	$this->assign('alipayConfig', $alipayConfig);
        $this->assign('tableName', $tableName);
        $this->assign('userInfo', $userInfo);
        $this->assign('totalNum', $totalNum);
        $this->assign('totalPrice', $totalPrice);
        $this->assign('my_dish', $data);
        $this->assign('metaTitle', '我的订单');
	//是否要支付
        unset($_SESSION['david_add_dishset']);
        unset($_SESSION['david_add_mymenuset']);
        
        $this->display();
    }
    public function getInfo(){ //exit(json_encode(array('success' => 1, 'msg' => 'ok')));
        if (empty($this->wecha_id)) {
            exit(json_encode(array('success' => 0, 'msg' => '无法获取您的微信身份，请关注“公众号”，然后回复“订餐”来使用此功能')));
    }
        exit(json_encode(array('success' => 1, 'msg' => 'ok')));
    }
    /**
    * 保存我的订单
    */
    public function saveMyOrder(){
        if (empty($this->wecha_id)) {
            unset($_SESSION[$this->session_dish_info]);
            $this->error('您的微信账号为空，不能订餐!');
            exit(json_encode(array('success' => 0, 'msg' => '您的微信账号为空，不能订餐!')));
        }
        $dishs = $this->getDishMenu();
        if (empty($dishs)) {
            $this->error('没有点餐，请去点餐吧!');
        }
        $userInfo = unserialize($_SESSION[$this -> session_dish_user]);//已有好多信息数组
        if (empty($userInfo)) {
            $this -> error('您的个人信息有误，请重新下单!', U('Repast/selectTable', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $this -> _cid)));
        }
        $userInfo['cid']      = $this->_cid;
        $userInfo['wecha_id'] = $this->wecha_id;
        $userInfo['token']    = $this->token;
        $total                = $price = 0;
        $dids                 = array_keys($dishs);
        $dishList = M('Dish') -> where(array('cid' => $this ->_cid, 'id' => array('in', $dids))) -> select();
        $temp                 = array();
        foreach ($dishList as $r) {
            if (isset($dishs[$r['id']])) {
                $temp[$r['id']] = array('price' => $r['price'], 'num' => $dishs[$r['id']]['num'], 'name' => $r['name'], 'des' => $dishs[$r['id']]['des']);
                $total += $dishs[$r['id']]['num'];
                $price += $dishs[$r['id']]['num'] * $r['price'];
            }
        }
        $takeAwayPrice = 0;
        if (isset($userInfo['price']) && $userInfo['price']) {
            $price += $userInfo['price'];
            $takeAwayPrice = $userInfo['price'];
        }
        $userInfo['total']   = $total;
        $userInfo['price']   = $price;
        $userInfo['info'] = serialize(array('takeAwayPrice' => $takeAwayPrice, 'list' => $temp));
        $userInfo['time']    = time();
        $userInfo['orderid'] = substr($this->wecha_id, -1, 4) . date("YmdHis");
        $doid = D('Dish_order') -> add($userInfo);//send_email
        $dis_order_id = $doid;
        if ($doid) {
            if ($userInfo['takeaway'] != 2) {
                if ($userInfo['takeaway'] == 1) {
                    Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$userInfo['name']}刚刚叫了一份外卖，订单号：{$userInfo['orderid']}，请您注意查看并处理");
                } else {
                    Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$userInfo['name']}刚刚预约了一次用餐，订单号：{$userInfo['orderid']}，请您注意查看并处理");
                }
            }
        /**			
        * 保存个人信息
        */		
            if ($userInfo['tableid']) {
                $table_order = array('cid' => $this -> _cid, 
                    'tableid' => $userInfo['tableid'],
                    'orderid' => $doid,
                    'wecha_id' => $this->wecha_id,
                    'reservetime' => $userInfo['reservetime'],
                    'creattime' => time());
                $doid        = D('Dish_table')->add($table_order);
            }
            $_SESSION[$this->session_dish_info] = $_SESSION[$this->session_dish_user] = '';
            unset($_SESSION[$this -> session_dish_user], $_SESSION[$this -> session_dish_info]);
            $alipayConfig = M('Alipay_config') -> where(array('token' => $this -> token)) -> find();
            $dishCompany = M('Dish_company') -> where(array('cid' => $this -> _cid)) -> find();

            
            $dish_info = unserialize($userInfo['info']);
            $cai_arr_mail = array();
            $cai_arr = array();
            //print_r($dish_info['list']);exit;
            $all_money = 0;
            foreach ($dish_info['list'] as $cai) {
                $c_name  = $cai['name'] . str_repeat(' ', (10-strlen($cai['name'])/3)*2);
                $c_price = str_pad($cai['price'], 5, " ", STR_PAD_RIGHT);
                $cai_arr[] = $c_name . $c_price . $cai['num'];
                
                $c_name  = $cai['name'] . str_repeat(' ', (10-strlen($cai['name'])/3)*3);
                $cai_arr_mail[] = $c_name . $c_price . $cai['num'];
                
                $all_money += $cai['price'] * $cai['num'];
            }
            $email_tpl=
"订单编号：$userInfo[orderid]
联 系 人：$userInfo[name]
电    话：$userInfo[tel]
条目        单价（元）   数量
----------------------------\n" .join(chr(10).chr(13), $cai_arr_mail). "

备注：$userInfo[des]
餐台：" .$_SESSION['mymenu_order_tablename']. "
----------------------------
订餐人数：$userInfo[nums]
总　　价：$all_money
送餐时间：" . date('Y-m-d H:i:s', $userInfo['reservetime']) . "
下单时间：".  date('Y-m-d H:i:s', $userInfo['time']);             
            //发邮件动作
            if ($dishCompany['email_status'] == 1 && $dishCompany['email']) {                                  
                    $to_email       = $dishCompany['email'];
                    $emailuser      = $info['emailuser'];
                    $emailpassword  = $info['emailpassword'];
                    $subject        = "您有新的订单，单号：".$userInfo['orderid']."，预定人：".$userInfo['name'];
                    $body           = $email_tpl;
                    //$this->send_email($subject,$body,$emailuser,$emailpassword,$to_email);
                    
                    $smtpserver = C('email_server'); 
                    $port = C('email_port');
                    $smtpuser = C('email_user');
                    $smtppwd = C('email_pwd');
                    $mailtype = "TXT";
                    $sender = C('email_user');
                    $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
                $to = $to_email;//$list['email']; 
                $subject = $subject;//C('pwd_email_title');
                    //$body = iconv('UTF-8','gb2312',$fetchcontent);inv
                    $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);     
                    D('Dish_order')->save(array('send_email' => 1, 'id'=>$dis_order_id));//是否发过邮件
                    
            }

//短信
	    if ($dishCompany['phone_status'] == 1 && $userInfo['takeaway'] != 2){
                if ($userInfo['takeaway'] == 1){
                    Sms :: sendSms($this -> token . "_" . $this -> _cid, "顾客{$userInfo['name']}刚刚叫了一份外卖，订单号：{$userInfo['orderid']}，请您注意查看并处理【云信使】");
                }else{
                    Sms :: sendSms($this -> token . "_" . $this -> _cid, "顾客{$userInfo['name']}刚刚预约了一次用餐，订单号：{$userInfo['orderid']}，请您注意查看并处理【云信使】");
                }
            }
            //打印 
            //商户代码：0466550ef46d11e391ea00163e02163b
            //API：c4e011af
            //设备编码：4600108698566106
            if ($dishCompany['print_status'] == 1 && $dishCompany['memberCode'] && $dishCompany['feiyin_key'] && $dishCompany['deviceNo']) {
                $company_row = M('Company') -> where(array('id' => $userInfo['cid'])) -> find();
                //$this->printTxt($email_tpl, $dishCompany);
               //echo $company_row[name];
						$str="
				     $company_row[name] 
					
							条目         单价（元） 数量
							----------------------------\n".join(chr(10).chr(8), $cai_arr)."
							
							备注：$userInfo[des]
							餐台：" .$_SESSION['mymenu_order_tablename']. "
							----------------------------
							合计：{$all_money}元 
							
							联 系 人：$userInfo[name]
							订餐人数：$userInfo[nums]
							送货地址：$userInfo[address]
							联系电话：$userInfo[tel]
							送餐时间：" . date('Y-m-d H:i:s', $userInfo['reservetime']) . "
							订购时间：".date("Y-m-d H:i:s");
                
                $print_total = $dishCompany['print_total'];
                for ($i=1; $i<=$print_total; $i++) {    
                    $msgInfo=array(
                            'memberCode'=>$dishCompany['memberCode'],
                            'msgDetail'=>str_replace('[num]', $i, $str),
                            'deviceNo'=>$dishCompany['deviceNo'],
                            'msgNo'=>time()+1,
                            'reqTime' => number_format(1000*time(), 0, '', '')
                    );
                    $content = $msgInfo['memberCode'].$msgInfo['msgDetail'].$msgInfo['deviceNo'].$msgInfo['msgNo'].$msgInfo['reqTime'].$dishCompany['feiyin_key'];
                    $msgInfo['securityCode'] = md5($content);
                    $msgInfo['mode']=2;                   
                          
                    $client = new HttpClient('my.feyin.net');
                    if($client->post('/api/sendMsg',$msgInfo)){
                            $printstate=$client->getContent();
                    }  sleep(3);                  
                }

		if($printstate==0){
                        //echo '打印成功';
			//$this->success('打印成功', U('Printer/index',array('token'=>$this->token)));
		}else{
                    //echo '打印失败';
                    //$this->error('打印失败，错误代码：'.$printstate);
		}                
            }
            
            
            if ($_POST['paymode'] == 1 && $alipayConfig['open'] && $dishCompany['payonline']){
                $this -> success('正在提交中...', U('Alipay/pay', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'success' => 1, 'from' => 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
            }elseif ($_POST['paymode'] == 4 && $this -> fans['balance'] && $dishCompany['payonline']){
                $this -> success('正在提交中...', U('CardPay/pay', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'success' => 1, 'from' => 'Repast', 'orderName' => $userInfo['orderid'], 'single_orderid' => $userInfo['orderid'], 'price' => $price)));
            }else{
                $this -> success('预定成功,进入您的订单页', U('Repast/myOrder', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $this -> _cid, 'success' => 1)));
            }
        }else{
            $this -> error('订单出错，请重新下单');
            exit(json_encode(array('success' => 0, 'msg' => '订单出错，请重新下单')));
        }
    }
	//测试打印 $dishCompany['memberCode'] && $dishCompany['feiyin_key'] && $dishCompany['deviceNo']
	public function printTxt($email_tpl, $dishCompany){
               
		$str="
     微信平台订餐打印
	
条目      单价（元）   数量
----------------------------
番茄炒粉     10.0       1
客家咸香鸡   20.0       1

备注：$userInfo[des]
----------------------------
合计：{$all_money}元 

送货地址：$userInfo[address]
联系电话：$userInfo[tel]
订购时间：".date("Y-m-d H:i:s");
		$msgInfo=array(
			'memberCode'=>$dishCompany['memberCode'],
			'msgDetail'=>$email_tpl,//$str,
			'deviceNo'=>$dishCompany['deviceNo'],
			'msgNo'=>time()+1,
			'reqTime' => number_format(1000*time(), 0, '', '')
		);
		$content = $msgInfo['memberCode'].$msgInfo['msgDetail'].$msgInfo['deviceNo'].$msgInfo['msgNo'].$msgInfo['reqTime'].$dishCompany['feiyin_key'];
		$msgInfo['securityCode'] = md5($content);
		$msgInfo['mode']=2;
		$client = new HttpClient('my.feyin.net');
		if($client->post('/api/sendMsg',$msgInfo)){
			$printstate=$client->getContent();
		}
		if($printstate==0){
                        //echo '打印成功';
			//$this->success('打印成功', U('Printer/index',array('token'=>$this->token)));
		}else{
                    //echo '打印失败';
                    //$this->error('打印失败，错误代码：'.$printstate);
		}
	}
    
//打印方法 $dishCompany['memberCode'] && $dishCompany['feiyin_key'] && $dishCompany['deviceNo']
	public function printTxt_a($email_tpl, $dishCompany){
            $email_tpl = str_replace(chr(13).chr(10), "\r\n", $email_tpl);
			$str=$email_tpl;
			$str .= "\r\n打印时间：".date('Y-m-d H:i:s')."\r\n--------------------------------\r\n";		
			$str="<1B40><1D2111><1B6101>订餐内容<0D0A><1B6100><1D2100><0D0A>".$str;  //初始化打印机加粗居中						
			//$str=iconv('utf-8','gbk',$str);
			//设置打印服务器开始
			$server="http://218.97.194.59:8088/Router/Rest/";  //打印API接口地址
			$appkey= $dishCompany['memberCode'];  //商户编码
                        $appsecret = $dishCompany['feiyin_key'];  // 商户密钥
                        $type = "addPrintContext"  ;//   打印类型
			$printerid = $dishCompany['deviceNo'];  //打印机编号
                        $isrun = "1";   //1为直接打印，非1等待打印
			$printcontext = $str ;    //打印内容
			$printcount= 1;//$printermodel['PrinterCount'];
                        $contentencode=urlencode("$printcontext");
			//$contentencode=$printcontext;
            $url = "$server/?appkey=$appkey&appsecret=$appsecret&type=$type&printerid=$printerid&$isrun=$isrun&printcount=$printcount&printcontext=$contentencode";
                        $content = file_get_contents($url);
			//print_r ('反馈结果'.$content);   //服务器返回结果，成功则返回此订单打印序列号，用于判断修改打印状态及处理状态。
         
			//设置打印服务器结束
			//设置为打印过了
			//$this->product_cart_model->where(array('id'=>$thisOrder['id']))->save(array('printed'=>1,'handled'=>1,'pcid'=>$content));
			//echo "CMD=01	FLAG=0	MESSAGE=成功	DATETIME=".date('YmdHis',$now)."	ORDERCOUNT=".$count."	ORDERID=".$thisOrder['id']."	PRINT=".$str;

    }    
     //发邮件函数
    public function send_email($Subject,$body,$emailuser,$emailpassword,$to_email){
            $where['username']=$this->_post('username');
            $where['email']=$this->_post('email');
            $db=D('Users');
            $list=$db->where($where)->find();
            if($list==false) $this->error('邮箱和帐号不正确',U('Index/regpwd'));

            $smtpserver = C('email_server'); 
            $port = C('email_port');
            $smtpuser = C('email_user');
            $smtppwd = C('email_pwd');
            $mailtype = "TXT";
            $sender = C('email_user');
            $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
            $to = $list['email']; 
            $subject = C('pwd_email_title');
            $code = C('site_url').U('Index/resetpwd',array('uid'=>$list['id'],'code'=>md5($list['id'].$list['password'].$list['email']),'resettime'=>time()));
            $fetchcontent = C('pwd_email_content');
            $fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
            $fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
            $fetchcontent = str_replace('{code}',$code,$fetchcontent);
            $body=$fetchcontent;
            //$body = iconv('UTF-8','gb2312',$fetchcontent);inv
            $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
    }    
    public function clearMyMenu(){
        $_SESSION[$this->session_dish_info] = null;
        unset($_SESSION[$this->session_dish_info]);
    }
    /**
    * 我的订单记录
    */
    public function myOrder(){
        $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
        $where = array('cid' => $this -> _cid, 'wecha_id' => $this -> wecha_id);
        if ($status == 4){
            $where['isuse'] = 1;
            $where['paid']  = 1;
        } elseif ($status == 3) {
            $where['isuse'] = 0;
            $where['paid']  = 1;
        } elseif ($status == 2) {
            $where['isuse'] = 1;
            $where['paid']  = 0;
        } elseif ($status == 1) {
            $where['isuse'] = 0;
            $where['paid']  = 0;
        }
        $dish_order = M('Dish_order')->where($where)->order('id DESC')->select();
        $list       = array();
        foreach ($dish_order as $row) {
            $row['info'] = unserialize($row['info']);
            $list[]      = $row;
        }
        $this->assign('orderList', $list);
        $this->assign('status', $status);
        $this->assign('metaTitle', '我的订单');
        $this->display();
    }
    /**
     * 点餐信息
     */
    public function getDishMenu(){
        if (!isset($_SESSION[$this->session_dish_info]) || !strlen($_SESSION[$this->session_dish_info])) {
            $dish = array();
        } else {
            $dish = unserialize($_SESSION[$this->session_dish_info]);
        }
        return $dish;
    }
    /**
     * 支付成功后的回调函数
     */
    public function payReturn(){
         //TODO 发货的短信提醒
        $orderid = $_GET['orderid'];
        if ($order = M('dish_order') -> where(array('orderid' => $orderid, 'token' => $this -> token)) -> find()){
            if ($order['paid']) {
                Sms::sendSms($this->token . "_" . $this->_cid, "顾客{$order['name']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
            }
            $this -> redirect(U('Repast/myOrder', array('token' => $this -> token, 'wecha_id' => $this -> wecha_id, 'cid' => $this -> _cid)));
        }else{
            exit('订单不存在');
        }
    }
}
?>