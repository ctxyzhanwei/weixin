<?php
class UserAction extends BaseAction {
	public $userGroup;
	public $token;
	public $user;
	public $userFunctions;
	public $wxuser;
	public $usertplid;
	protected function _initialize() {
		parent::_initialize();
		$userinfo = M('User_group')->where(array('id' => session('gid')))->find();
		$this->assign('userinfo', $userinfo);
		$this->userGroup = $userinfo;
		$users = M('Users')->where(array('id' => $_SESSION['uid']))->find();
		if (session('usertplid') === null || session('usertplid') != (int)$users['usertplid']) {
			session('usertplid', (int)$users['usertplid']);
		}
		$this->usertplid = (int)$users['usertplid'];
		$this->assign('usertplid', session('usertplid'));
		$this->user = $users;
		$this->token = session('token');
		$this->assign('thisUser', $users);
		$allow_pay = array('AlipayAction', 'TenpayAction', 'AlipayReceiveAction');
		$this->assign('viptime', $users['viptime']);
		if (session('uid')) {
			if ($users['viptime'] < time()) {
				if (function_exists('get_called_class')) {
					if (!in_array(get_called_class(), $allow_pay)) {
						$this->error('非常遗憾的告诉您，您的帐号已经到期，请充值后再使用，感谢继续使用我们的系统。', U('User/Alipay/index', array('flag' => 5.3)));
					}
				}else {
					if (!in_array(get_class($this), $allow_pay)) {
						$this->error('非常遗憾的告诉您，您的帐号已经到期，请充值后再使用，感谢继续使用我们的系统。', U('User/Alipay/index', array('flag' => 5.2)));
					}
				}
			}
		}
		$token_open = M('Token_open')->field('queryname')->where(array('token' => $this->token))->find();
		$trans = include('./PigCms/Lib/ORG/FuncToModel.php');
		if (C('agent_version') && $this->agentid) {
			$user_group_where = array('id' => session('gid'), 'agentid' => $this->agentid);
			$func_where = array('agentid' => $this->agentid);
			$function_db = M('Agent_function');
		}else {
			$user_group_where = array('id' => session('gid'));
			$func_where = array('1 = 1');
			$function_db = M('Function');
		}
		$group_func = M('User_group')->where($user_group_where)->getField('func');
		$Afunc = $function_db->where($func_where)->field('id,funname')->select();
		$group_func = explode(',', $group_func);
		foreach ($Afunc as $tk => $tv) {
			if (!in_array($tv['funname'], $group_func)) {
				$not_exist[] = isset($trans[$tv['funname']])?$trans[$tv['funname']]:ucfirst($tv['funname']);
			}
		}
		$this->assign('not_exist', $not_exist);
		$wecha = M('Wxuser')->where(array('token' => session('token'), 'uid' => session('uid')))->find();
		$this->assign('wxuser', $wecha);
		$this->wxuser = $wecha;
		$this->assign('wecha', $wecha);
		$this->assign('wxuser', $wecha);
		$this->assign('token', $this->token);
		$token_open = M('token_open')->field('queryname')->where(array('token' => $this->token))->find();
		$this->userFunctions = explode(',', $token_open['queryname']);
		if (MODULE_NAME != 'Upyun') {
			if (session('uid') == false) {
				$this->redirect('Home/Index/login');
			}
		}else {
			if (isset($_SESSION['administrator']) || isset($_SESSION['agentid']) || isset($_SESSION['uid']) || isset($_SESSION['wapupload'])) {
			}else {
				if (isset($_POST['PHPSESSID'])) {
					session_id($_POST['PHPSESSID']);
				}else {
					$this->redirect('Home/Index/login');
				}
			}
		}
        //子分支的登陆判断
		if (session('companyLogin') == 1 && !in_array(MODULE_NAME, array('Attachment', 'Repast', 'Upyun', 'Hotels', 'Store', 'Classify', 'Catemenu'))) {
			$this->redirect(U('User/Repast/index', array('cid' => session('companyid'))));
		}
        /****************upyun*********************/
		define('UNYUN_BUCKET', C('up_bucket'));
		define('UNYUN_USERNAME', C('up_username'));
		define('UNYUN_PASSWORD', C('up_password'));
		define('UNYUN_FORM_API_SECRET', C('up_form_api_secret'));
		define('UNYUN_DOMAIN', C('up_domainname'));
		$this->assign('upyun_domain', 'http://' . UNYUN_DOMAIN);
		$this->assign('upyun_bucket', UNYUN_BUCKET);
		$token = $this->_session('token');
		if (!$token) {
			if (isset($_GET['token'])) {
				$token = $this->_get('token');
			}else {
				$token = 'admin';
			}
		}
		$options = array();
		$now = time();
		$options['bucket'] = UNYUN_BUCKET;
                /// 空间名
		$options['expiration'] = $now + 600;
                /// 授权过期时间
		$options['save-key'] = '/' . $token . '/{year}/{mon}/{day}/' . $now . '_{random}{.suffix}';
                /// 文件名生成格式，请参阅 API 文档
		$options['allow-file-type'] = C('up_exts');
                /// 控制文件上传的类型，可选
		$options['content-length-range'] = '0,' . intval(C('up_size')) * 1000;
                /// 限制文件大小，可选
		if (intval($_GET['width'])) {
			$options['x-gmkerl-type'] = 'fix_width';
			$options['fix_width '] = $_GET['width'];
		}
                 //$options['return-url'] = C('site_url').'/index.php?g=User&m=Upyun&a=editorUploadReturn'; /// 页面跳转型回调地址
		$policy = base64_encode(json_encode($options));
		$sign = md5($policy . '&' . UNYUN_FORM_API_SECRET);
                /// 表单 API 功能的密匙（请访问又拍云管理后台的空间管理页面获取）
		$this->assign('editor_upyun_sign', $sign);
		$this->assign('editor_upyun_policy', $policy);
	}
	public function canUseFunction($funname) {
                //权限验证
		$queryname = M('token_open')->where(array('token' => $this->token))->getField('queryname');
		$queryname = explode(',', $queryname);
		function map_tolower($v) {
			return strtolower($v);
		}
		$queryname = array_map("map_tolower", $queryname);
		$user_group = M('User_group')->where(array('token' => $this->token, 'id' => intval(session('gid'))))->getField('func');
		$user_group = explode(',', $user_group);
		$user_group = array_map("map_tolower", $user_group);
		if (in_array(strtolower($funname), $queryname) === false || in_array(strtolower($funname), $user_group) === false) {
			$this->error('您还没有开启这个功能的使用权,请到“功能模块”菜单中勾选这个功能', U('Function/index', array('token' => $this->token)));
		}
	}
}

?>