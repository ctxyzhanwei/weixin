<?php
class RouterAction extends UserAction{
	public $tokenWhere;
	public $server;
	public function _initialize() {
		parent::_initialize();
		$rk = trim(C('router_key'));
		//if(empty($rk)){
//			$this->error('您好，必须购买[路由器]之后才可以设置WIFI',U('Function/index',array('token'=>session('token'))));
//		}
		$this->tokenWhere=array('token'=>$this->token);
		$this->server='http://wifi.pigcms.cn/';
	}
	public function index(){
		$routerUrl=Router::login($this->token,'wechaid');
		//
		$db=D('Router');
		$where=array('token'=>$this->token);
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->assign('tab','list');
		$this->display();
	}
	public function lists(){
		$record=M('Router')->where(array('id'=>intval($_GET['id'])))->find();
		$wxuser=M('Wxuser')->where(array('token'=>$this->token))->find();
		$this->assign('tab','list');
		$page=intval($_GET['page'])?intval($_GET['page']):1;
		$data=array(
		'page'=>$page,
		'id'=>$record['time'],
		'gw_id'=>$record['time'],
		'a'=>substr(trim(C('router_key')),0,16),
		'b'=>substr($wxuser['routerid'],0,16)
		);

		$rt=$this->curlPost($this->server.'api/node/client?page='.$page.'&id='.$record['gw_id'].'&gw_id='.$record['gw_id'].'&a='.substr(trim(C('router_key')),0,16).'&b='.substr($wxuser['routerid'],0,16),$data);
		$rt=str_replace('callback(','',$rt);

		$rt=substr($rt,0,-1);
		$arr=json_decode($rt,1);
		if (intval($arr['ret'])==0){
		}else {
			$this->error('发生错误：'.$arr['msg'].'，错误代码：'.$arr['ret']);
		}
		$pageinfo=$arr['data']['conf'];
		$list=$arr['data']['list'];
		//
		$this->assign('list',$list);
		$this->assign('pageinfo',$pageinfo);
		$page=new Page($pageinfo['total'],10);
		$this->display();
	}
	public function delete(){
		$where=array();
		$where['token']=$this->token;
		$where['id']=intval($_GET['id']);
		//
		$wxuser=M('Wxuser')->where(array('token'=>$this->token))->find();
		$record=M('Router')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();

		$rt=$this->curlPost($this->server.'api/node/delete?id='.$record['gw_id'].'&a='.substr(trim(C('router_key')),0,16).'&b='.substr($wxuser['routerid'],0,16));
		//exit($this->server.'api/node/delete?id='.$record['gw_id'].'&a='.substr(trim(C('router_key')),0,16).'&b='.substr($wxuser['routerid'],0,16));
		//
		M('Router')->where($where)->delete();
		$this->success('设置成功',U('Router/index'));
	}
	public function set(){
		$this->assign('tab','list');
		$wxuser=M('Wxuser')->where($this->tokenWhere)->find();
		if (!$wxuser['routerid']){
			$this->error('请先设置商家信息',U('Router/config'));
		}
		if (IS_POST){
			if (isset($_GET['id'])){
			}else {
				//$this->curlPost($this->server.'api/node/delete?id='.trim($this->_post('gw_id')).'&a='.substr(trim(C('router_key')),0,16).'&b='.substr($wxuser['routerid'],0,16));
				//
				$rt=$this->curlPost($this->server.'api/node/delete?id='.trim($this->_post('gw_id')).'&a='.substr(trim(C('router_key')),0,16).'&b='.substr($wxuser['routerid'],0,16));
				$data=array(
				'gw_id'=>$this->_post('gw_id'),
				'gw_name'=>$this->_post('gw_name'),
				'wx_bind_type'=>1,
				'public_wechat_id'=>$wxuser['weixin'],
				'qrcode_url'=>$this->_post('qrcode_url'),
				'a'=>substr(trim(C('router_key')),0,16),
				'b'=>substr($wxuser['routerid'],0,16)
				);
				$rt=$this->curlPost($this->server.'api/node/create',$data);
				$rt=str_replace('callback(','',$rt);
				$rt=substr($rt,0,-1);
				$arr=json_decode($rt,1);

				if (intval($arr['ret'])==0){
					$row=array(
					'name'=>$this->_post('gw_name'),
					'wechat'=>$wxuser['weixin'],
					'qrcode'=>$this->_post('qrcode_url'),
					'time'=>time(),
					'token'=>$this->token
					);
					$row['gw_id']=$this->_post('gw_id');
					
					M('Router')->add($row);
					$this->success('设置成功',U('Router/index'));
				}else {
					$this->error('发生错误：'.$arr['msg'].'，错误代码：'.$arr['ret']);
				}
			}
		}else {
			if (!$record){
				
			}
			$this->assign('record',$record);
			$this->display();
		}
	}
	
	public function config(){
		$this->assign('tab','config');
		$db=M('Router_config');
		$wxuser=M('Wxuser')->where($this->tokenWhere)->find();
		
		
		if (!$wxuser['routerid']){
			M('Router_config')->where($this->tokenWhere)->delete();
		}


		$thisCompay=M('Company')->where(array('token'=>$this->token,'isbranch'=>0))->find();
		if (!$thisCompay){
			$this->error('请先在lbs里设置公司信息',U('Company/index',array('token'=>$this->token)));
		}
		$record=$db->where($this->tokenWhere)->find();
		if (IS_POST){
			$data=array(
			'name'=>$thisCompay['name'],
			'contact_name'=>$thisCompay['name'],
			'contact_email'=>$thisCompay['username'],
			'contact_qq'=>$this->_post('contact_qq'),
			'contact_address'=>$thisCompay['address'],
			'contact_phone'=>$thisCompay['tel'],
			'welcome_img'=>$this->_post('welcome_img'),
			'other_img'=>$this->_post('other_img'),
			'a'=>substr(trim(C('router_key')),0,16),
			'reply_content'=>trim($_POST['keyword'])
			);
			$data1=array(
			'token'=>$this->token,
			'welcome_img'=>$this->_post('welcome_img'),
			'other_img'=>$this->_post('other_img'),
			'contact_qq'=>$this->_post('contact_qq'),
			'title'=>$this->_post('title'),
			'info'=>$this->_post('info'),
			'picurl'=>$this->_post('picurl'),
			'keyword'=>$this->_post('keyword')
			);
			if ($record){
				//
				if (strlen(trim($_POST['password']))>0&&strlen(trim($_POST['password']))!=6){
					$this->error('请设置六位数字或字符的密码');
				}
				if (strlen(trim($_POST['password']))==6){
					$data1['password']=md5(trim($_POST['password']));
					$data['password']=trim($_POST['password']);
				}
				//
				$db->where($this->tokenWhere)->save($data1);
				//
				M('Keyword')->where(array('token'=>$this->token,'module'=>'Router_config'))->save(array('keyword'=>$this->_post('keyword')));
				//
				$data['b']=substr($wxuser['routerid'],0,16);
				
				$this->curlPost($this->server.'api/business/edit',$data);
				//
				$this->success('设置成功');
			}else {
				//
				if (strlen(trim($_POST['password']))!=6){
					$this->error('请设置六位数字或字符的密码');
				}
				$data1['password']=md5(trim($_POST['password']));
				$data['password']=trim($_POST['password']);
				//
				
				$rt=$this->curlPost($this->server.'api/business/create',$data);
				
				
				
				$rt=str_replace('callback(','',$rt);
				$rt=substr($rt,0,-1);
				$arr=json_decode($rt,1);
				if (!$arr['data']['business_id']){
					exit($rt);
				}
				$id=$db->add($data1);
				//
				M('Keyword')->add(array('keyword'=>$this->_post('keyword'),'pid'=>$id,'token'=>$this->token,'module'=>'Router_config'));
				//
				if (intval($arr['ret'])==0){

					$rt=M('Wxuser')->where($this->tokenWhere)->save(array('routerid'=>$arr['data']['business_id']));
					if ($rt){
					$this->success('设置成功');
					}else {
						$this->error('设置失败，wxuser表可能缺少字段');
					}
				}else {
					$this->error('发生错误：'.$arr['msg'].'，错误代码：'.$arr['ret']);
				}
			}
		}else {
			$this->assign('info',$record);
			$this->display();
		}
		
	}
	

	

	function curlPost($url, $data,$showError=1){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			exit('curl error:'.$errorno);
		}else{
			return $tmpInfo;
		}
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}


?>