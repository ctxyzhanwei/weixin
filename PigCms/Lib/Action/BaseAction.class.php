<?php
class BaseAction extends Action{
	public $isAgent;
	public $home_theme;
	public $reg_needCheck;
	public $minGroupid;
	public $reg_validDays;
	public $reg_groupid;
	public $thisAgent;
	public $agentid;
	public $adminMp;
	public $siteUrl;
	public $isQcloud = false;
	protected function _initialize(){
		//检测电脑PC版
		if(GROUP_NAME == 'Home' && MODULE_NAME == 'Index' && ACTION_NAME == 'index'){
			$this->check_company_website();
		}

		if($this->_get('openId') != NULL){
			$this->isQcloud = true;
			if(session('isQcloud') == NULL){
				session('isQcloud',true);
			}
		}

		define('RES',THEME_PATH.'common');
		define('STATICS',TMPL_PATH.'static');
		//Input::noGPC();
		$this->assign('action',$this->getActionName());
		if (C('STATICS_PATH')){
			$staticPath='';
		}else {
			$staticPath='http://s.404.cn';
		}
		$this->assign('staticPath',$staticPath);
		define('STATICS',$staticPath.'/tpl/static');		//********************/
		$this->isAgent=0;
		if (C('agent_version')){
			$thisAgent=M('agent')->where(array('siteurl'=>'http://'.$_SERVER['HTTP_HOST']))->find();
			if ($thisAgent){
				$this->isAgent=1;
			}
		}
		if (!$this->isAgent){
			$this->agentid=0;
			if (!C('site_logo')){
				$f_logo='tpl/Home/pigcms/common/images/logo-pigcms.png';
			}else {
				$f_logo=C('site_logo');
			}
			$f_siteName=C('SITE_NAME');
			$f_siteTitle=C('SITE_TITLE');
			$f_metaKeyword=C('keyword');
			$f_metaDes=C('content');
			$f_qq=C('site_qq');
			$f_ipc=C('ipc');
			$f_qrcode='tpl/Home/pigcms/common/images/ewm2.jpg';
			$f_siteUrl=C('site_url');
			$this->home_theme=C('DEFAULT_THEME');
			$f_regNeedMp=C('reg_needmp')=='true'?1:0;
			$this->reg_needCheck=C('ischeckuser')=='false'?1:0;
			$this->minGroupid=1;
			$this->reg_validDays=C('reg_validdays');
			$this->reg_groupid=C('reg_groupid');
			$this->adminMp=C('site_mp');
		}else {
			$this->agentid=$thisAgent['id'];
			$this->thisAgent=$thisAgent;
			$f_logo=$thisAgent['sitelogo'];
			$f_siteName=$thisAgent['sitename'];
			$f_siteTitle=$thisAgent['sitetitle'];
			$f_metaKeyword=$thisAgent['metakeywords'];
			$f_metaDes=$thisAgent['metades'];
			$f_qq=$thisAgent['qq'];
			$f_qrcode=$thisAgent['qrcode'];
			$f_siteUrl=$thisAgent['siteurl'];
			$f_ipc=$thisAgent['copyright'];
			$this->home_theme=C('DEFAULT_THEME');
			if (file_exists($_SERVER['DOCUMENT_ROOT'].'/tpl/Home/'.'agent_'.$thisAgent['id'])){
				$this->home_theme='agent_'.$thisAgent['id'];
			}
			$f_regNeedMp=$thisAgent['regneedmp'];
			$this->reg_needCheck=$thisAgent['needcheckuser'];
			$minGroup=M('User_group')->where(array('agentid'=>$thisAgent['id']))->order('id ASC')->find();
			$this->minGroupid=$minGroup['id'];
			$this->reg_validDays=$thisAgent['regvaliddays'];
			$this->reg_groupid=$thisAgent['reggid'];
			$this->adminMp=$thisAgent['mp'];
		}
		$this->siteUrl=$f_siteUrl;
		$this->assign('f_logo',$f_logo);
		$this->assign('f_siteName',$f_siteName);
		$this->assign('f_siteTitle',$f_siteTitle);
		$this->assign('f_metaKeyword',$f_metaKeyword);
		$this->assign('f_metaDes',$f_metaDes);
		$this->assign('f_qq',$f_qq);
		$this->assign('f_qrcode',$f_qrcode);
		$this->assign('f_siteUrl',$f_siteUrl);
		$this->assign('f_regNeedMp',$f_regNeedMp);
		$this->assign('f_ipc',$f_ipc);
		$this->assign('reg_validDays',$this->reg_validDays);
		//******************/
	        $this -> assign('f_countsz', htmlspecialchars_decode(base64_decode(C('countsz'))));			
	}

	//添加所有内容,包含关键词
	protected function all_insert($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id){
				$m_arr=array('Img','Text','Voiceresponse','Ordering','Lottery','Host','Product','Selfform','Panorama','Wedding','Vote','Estate','Reservation','Greeting_card');
				if(in_array($name,$m_arr)){
					//isset($_POST['precisions']) ? $precisions = 1: $precisions = 0 ;
					$this->handleKeyword($id,$name,$_POST['keyword'],intval($_POST['precisions']));

				}

				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	//单一信息添加
	protected function insert($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id==true){
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	//单子信息修改
	protected function save($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id==true){
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	//修改所有内容,包含关键词
	protected function all_save($name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id){
				$m_arr=array(
				'Img',
				'Text',
				'Voiceresponse',
				'Ordering','Lottery',
				'Host','Product',
				'Selfform',
				'Panorama',
				'Wedding',
				'Vote',
				'Estate',
				'Reservation',
				'Carowner','Carset'
				);
				if(in_array($name,$m_arr)){
					$this->handleKeyword(intval($_POST['id']),$name,$_POST['keyword'],intval($_POST['precisions']));

				}
				$this->success('操作成功',U(MODULE_NAME.$back));
			}else{
				$this->error('操作失败',U(MODULE_NAME.$back));
			}
		}
	}
	protected function del_id($name='',$jump=''){
		$name=$name?$name:MODULE_NAME;
		$jump=empty($name)?MODULE_NAME.'/index':$jump;
		$db=D($name);
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if($db->where($where)->delete()){
			$this->success('操作成功',U($jump));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	protected function all_del($id,$name='',$back='/index'){
		$name=$name?$name:MODULE_NAME;
		$db=D($name);
		if($db->delete($id)){
			$this->ajaxReturn('操作成功',U(MODULE_NAME.$back));
		}else{
			$this->ajaxReturn('操作失败',U(MODULE_NAME.$back));
		}
	}

	//通用添加关键词 支持逗号和空格分隔关键词
	public function handleKeyword($id,$module,$keyword='',$precisions=0,$delete=0){
		$db=M('Keyword');
		$token = session('token');
		$db->where(array('pid'=>$id,'token'=>$token,'module'=>$module))->delete();
		$keyword = trim(trim($keyword),',');

		if (!$delete){

			$data['pid']=$id;
			$data['module']=$module;
			$data['token']=$token;

			$flag1 = strpos($keyword,',');
			$flag2 = strpos($keyword,' ');

			if( $flag1 === false &&  $flag2 === false ){
				$pk = explode('|',$keyword);
				if(count($pk) == 2){
					$data['precisions'] = $pk[1];
					$data['keyword'] = $pk[0];
				}else{
					$data['precisions'] = $precisions;
					$data['keyword'] = $keyword;
				}

				$db->add($data);

			}else{
				//关键词 关键|1 关键词|0
				if($flag1 === false){
					$keyword = explode(' ', $keyword);
					foreach ($keyword as $k => $v){
						$pk = explode('|',$v);
						if(count($pk) == 2){
							$data['precisions'] = $pk[1];
							$data['keyword'] = $pk[0];
						}else{
							$data['precisions'] = $precisions;
							$data['keyword'] = $v;
						}
						$db->add($data);
					}


				}else{

					$keyword = explode(',', $keyword);
					foreach ($keyword as $k => $v){
						$pk = explode('|',$v);
						if(count($pk) == 2){
							$data['precisions'] = $pk[1];
							$data['keyword'] = $pk[0];
						}else{
							$data['precisions'] = $precisions;
							$data['keyword'] = $v;
						}
						$db->add($data);
					}
				}
			}
		}
	}

	//判断是否是企业版的PC网站
	protected function check_company_website(){
		//如果当前网址和平台网址一样，则不查询。
		$site_domain = parse_url(C('site_url'));
		$now_host = $_SERVER['SERVER_NAME'];
		if($site_domain['host'] != $now_host){
			$now_website = S('now_website'.$now_host);
			if(empty($now_website)){
				$group_list = explode(',',C('app_group_list'));
				if(in_array('Web',$group_list)){
					$database_pc_site = D('Pc_site');
					$condition_pc_site['site'] = $now_host;
					$now_website = $database_pc_site->field(true)->where($condition_pc_site)->find();
				}
			}
			if(!empty($now_website)){
				$_SESSION['now_website'] = $now_website;
				R('Web/Web_index/index');
				exit;
			}
		}
	}


	
}

