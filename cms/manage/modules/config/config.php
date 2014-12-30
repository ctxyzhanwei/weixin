<?php
bpBase::loadAppClass('manage','manage',0);
class config extends manage {
	function __construct() {
		parent::__construct();
	}
	public function cmsContent(){
		$this->exitWithoutAccess();
		if(isset($_POST['doSubmit'])){
			$arr=var_export($_POST['info'],1);
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'cmsContent.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('cmsContent');		
		}
	}
	public function system(){
		$this->exitWithoutAccess('system','manage');
		if(isset($_POST['doSubmit'])){
			//
			$_POST['info']['emailSsl']='ssl';
			$_POST['info']['statisticCode']=base64_encode(stripslashes($_POST['info']['statisticCode']));
			$systemConfig=loadConfig('system');
			$_POST['info']['mobileStatisticCode']=$systemConfig['mobileStatisticCode'];
			$arr=var_export($_POST['info'],1);
			
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			//save as /constant/config.inc.php
			
			delCache('site1');
		
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'system.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('system');		
		}
	}
	public function watermark(){
		$this->exitWithoutAccess('system','manage');
		if(isset($_POST['doSubmit'])){
			//
			$arr=var_export($_POST['info'],1);
			
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			//
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'watermark.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('watermark');		
		}
	}
	public function site(){
		$this->exitWithoutAccess('system','manage');
		//判断是否有相关站点
		$thisSite=$this->site;
		$site_db=M('site');
		//读取pigcms首页回复配置
		$home_db=M('home');
		$homeConfig=$home_db->get_one(array('token'=>$this->token));
		//
		if(IS_POST){
			$row=$_POST['info'];
			$row['statisticcode']=base64_encode($row['statisticcode']);
			if (!$thisSite){
				$siteid=$site_db->insert($row,1);
				//添加默认分类
				if ($siteid){
					$channel_db=M('channel');
					$homeChannelRow=array('name'=>'首页','shortname'=>'首页','channeltype'=>1,'cindex'=>'homepage','thumbwidth'=>0,'thumbheight'=>0,'parentid'=>0,'site'=>$siteid,'time'=>SYS_TIME,'token'=>$this->token,'isnav'=>0);
					$homeChannelID=$channel_db->insert($homeChannelRow,1);
					$channelArrs=array(
					array('name'=>'关于我们','shortname'=>'简介','cindex'=>'aboutus','channeltype'=>1,'isnav'=>1),
					array('name'=>'最新动态','shortname'=>'动态','cindex'=>'news','channeltype'=>1,'isnav'=>1),
					array('name'=>'产品展示','shortname'=>'产品','cindex'=>'products','channeltype'=>1,'isnav'=>1),
					array('name'=>'精彩案例','shortname'=>'案例','cindex'=>'case','channeltype'=>1,'isnav'=>1),
					array('name'=>'联系我们','shortname'=>'联系','cindex'=>'contact','channeltype'=>1,'isnav'=>1),
					array('name'=>'幻灯片','shortname'=>'幻灯片','cindex'=>'focus','channeltype'=>1,'isnav'=>0)
					);
					
					$baseChannelArr=array('thumbwidth'=>0,'thumbheight'=>0,'parentid'=>$homeChannelID,'site'=>$siteid,'time'=>SYS_TIME,'token'=>$this->token);
					$focusChannelID=0;
					if ($homeChannelID){
						$tpl=bpBase::loadAppClass('template','template');
						foreach ($channelArrs as $c){
							$crow=$baseChannelArr;
							$crow['name']=$c['name'];
							$crow['cindex']=$c['cindex'];
							$crow['channeltype']=$c['channeltype'];
							$crow['isnav']=$c['isnav'];
							$crow['shortname']=$c['shortname'];
							$channelid=$channel_db->insert($crow,1);
							if ($c['cindex']=='focus'){
								$focusChannelID=$channelid;
							}
							$tpl->createChannelPageR($channelid);
						}
					}
				}
				//导入pigcms幻灯片
				$flash_db=M('flash');
				$article_db=M('article');
				$flashPics=$flash_db->select(array('token'=>$this->token));
				if ($flashPics){
					foreach ($flashPics as $fp){
						$article_db->insert(array('channel_id'=>$focusChannelID,'token'=>$this->token,'site'=>$siteid,'title'=>$fp['info'],'link'=>$fp['url'],'thumb'=>$fp['img'],'time'=>SYS_TIME,'content'=>$fp['info']));
					}
				}
				//
			}else {
				$site_db->update($row,array('token'=>$this->token));
			}
			delCache('siteByToken'.$row['token']);
			
			if ($this->site['template']){
				showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
			}else {
				showMessage('设置成功，请选择网站模板','?m=template&c=m_template&a=selectTemplate');
			}
			
		}else {
			include $this->showManageTpl('site');		
		}
	}
	public function index(){
		$this->exitWithoutAccess('system','manage');
		if(isset($_POST['doSubmit'])){
			$arr=var_export($_POST['info'],1);
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'index.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('index');		
		}
	}
	
}
?>