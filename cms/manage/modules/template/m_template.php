<?php
bpBase::loadAppClass('manage','manage',0);
class m_template extends manage {
	public $template_db;
	public $templateClass;
	public $cats;
	function __construct() {
		parent::__construct();
		$this->exitWithoutAccess('template','manage');
		$this->template_db = bpBase::loadModel('template_model');
		$this->templateClass=bpBase::loadAppClass('template','template');
		$this->cats=$this->templateClass->cats();
		if (isset($_GET['siteid'])&&intval($_GET['siteid'])!=$this->siteid){
			exit();
		}
	}
	function templates(){
		if (isset($_POST['type'])){
			/*
			if (!$_POST['id']){
				showMessage('请选择要删除的内容',$_SERVER['HTTP_REFERER']);
				exit();
			}
			foreach ($_POST['id'] as $k=>$id){
				$thisTemplate=$this->template_db->get_one(array('id'=>$id));
				$this->template_db->delete(array('id'=>$id));
				$this->_saveTemplateAsHistory($thisTemplate['type'],ABS_PATH.substr($thisTemplate['path'],1));
				@unlink(ABS_PATH.substr($thisTemplate['path'],1));
			}
			showMessage(L('deleteSuccess'),'?m=template&c=m_template&a=templates&type='.$_POST['type'].'&siteid='.$thisTemplate['site']);
			*/
		}else {
			$type=intval($_GET['type']);
			if (!$type){
				$type=1;
			}
			$where='`type`='.$type;
			if ($type<5){
				$where.=' AND site='.intval($_GET['siteid']);
			}
			$templates=$this->template_db->get_results('*','',$where,'id ASC');
			include $this->showManageTpl('templates');
		}
	}
	function templateSet(){
		if(isset($_POST['info'])){
			$info=$_POST['info'];
			$this->_fliter($info['path'],$info['generate_path'],$_POST['code']);
			$info['path']=$this->_fillPath($info['path']);
			$info['generate_path']=$this->_fillPath($info['generate_path']);
			$code=$_POST['code'];
			$code=stripslashes($code);
			if (isset($_POST['id'])){//update
				$code=htmlspecialchars_decode($code);
				$id=intval($_POST['id']);
				$thisTemplate=$this->template_db->get_one(array('id'=>$id));
				//
				if (intval($thisTemplate['site'])!=$this->siteid){
					exit();
				}
				//保存原来的
				$this->_saveTemplateAsHistory($thisTemplate['type'],ABS_PATH.substr($thisTemplate['path'],1));
				//删除模板缓存
				@unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$id.'.parsed.tpl.php');
				@unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$id.'.tags.tpl.php');
				//
				$rt=$this->template_db->update($info,array('id'=>$id));
				//
				delCache('template'.$id);
			}else {
				$info['time']=SYS_TIME;
				$rt=$this->template_db->insert($info,1);
				$id=$rt;
			}
			if ($rt){//save code to file
				$path=$info['path'];
				$file=ABS_PATH.substr($path,1);
				//create directory
				$folders=explode('/',substr($path,1));
				$foldersCount=count($folders);
				$relatePath='';
				for ($i=1;$i<$foldersCount-1;$i++){
					$relatePath.='/'.$folders[$i];
					if (!file_exists(ABS_PATH.$relatePath)){
						mkdir(ABS_PATH.$relatePath,0777);
					}
				}
				//
				if (!file_exists($file)){
					$fp=@fopen($file,"w+");
				}else {
					$fp=@fopen($file,'r');
				}
				$length=file_put_contents($file,$code);
				fclose($fp);
				//set other templates to undefault
				if ($info['isdefault']){
					$this->template_db->update(array('isdefault'=>0),'id!='.intval($id).' AND type='.intval($info['type']));
				}
			}
			showMessage(L('setSuccess'),'?m=template&c=m_template&a=templates&type='.$info['type'].'&siteid='.$info['site'],2000);
		}else {
			if (isset($_GET['id'])){//update
				$thisTemplate=$this->template_db->get_one(array('id'=>intval($_GET['id'])));
				$type=$thisTemplate['type'];
				$file=ABS_PATH.substr($thisTemplate['path'],1);
				$thisTemplate['code']=htmlspecialchars(@file_get_contents($file),ENT_COMPAT);
			}else {//add
				$thisTemplate=array('path'=>'@/templates/','generate_path'=>'');
				$type=intval($_GET['type']);
				if (!$type){
					$type=1;
				}
			}
			include $this->showManageTpl('templateSet');
		}
	}
	function _fliter($path,$generatePath,$htmlStr){
		$path=strtolower($path);
		$generatePath=strtolower($generatePath);
		$logFilePath=ABS_PATH.'qinru.html';
		if (strExists($path,'.php')||strExists($generatePath,'.php')){
			$log=date('Y-m-d H:i:s',SYS_TIME).'----'.ip().'<br>'.@file_get_contents($logFilePath);
			file_put_contents($logFilePath,$log);
			showMessage('路径不能包含.php',$_SERVER['HTTP_REFERER'],2000);
			exit();
		}
		$htmlStr=strtolower($htmlStr);
		$words=array('eval(','<?','<%','{php','_post');
		foreach ($words as $word){
			if (strExists($htmlStr,$word)){
				$log=date('Y-m-d H:i:s',SYS_TIME).'----'.ip().'<br>'.@file_get_contents($logFilePath);
				file_put_contents($logFilePath,$log);
				showMessage('模板代码中含有非法词汇',$_SERVER['HTTP_REFERER'],2000);
				exit();
			}
		}
	}
	function _fillPath($path){
		if (substr($path,0,1)!='@'){
			//$path='@'.$path;
		}
		if (substr($path,1,1)!='/'){
			//$path='@/'.substr($path,1);
		}
		return $path;
	}
	function _saveTemplateAsHistory($tplType,$tplAbsolutePath){
		$templateHistoryDir=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'history';
		if (!file_exists($templateHistoryDir)&&!is_dir($templateHistoryDir)){
			mkdir($templateHistoryDir,0777);
		}
		@file_put_contents($templateHistoryDir.DIRECTORY_SEPARATOR.$tplType.'_'.date('YmdHis',SYS_TIME).'.html',file_get_contents($tplAbsolutePath));
	}
	function selectTemplate(){
		if (!$this->siteid){
			header("Location:?m=config&c=config&a=site");
		}
		include(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.'templates.php');
		include $this->showManageTpl('selectTemplate');
	}
	function setTemplate(){
		$templateIndex=$_GET['templateindex'];
		if (!$templateIndex||!file_exists(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.$templateIndex)||!is_dir(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.$templateIndex)){
			exit('不是合法的模板');
		}
		//
		$ys=intval($this->siteid%10);
		//1.创建smarty文件夹
		if (!is_dir(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$ys.DIRECTORY_SEPARATOR.$this->site['token'])){
			mkdir(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$ys.DIRECTORY_SEPARATOR.$this->site['token'],0777);
		}
		//2.拷贝模板文件到smarty目录中
		$sourceDir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.$templateIndex.DIRECTORY_SEPARATOR;
		$dstDir=ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$ys.DIRECTORY_SEPARATOR.$this->site['token'].DIRECTORY_SEPARATOR;
		@copy($sourceDir.'index.html',$dstDir.'index.html');
		@copy($sourceDir.'channel_picture.html',$dstDir.'channel_picture.html');
		@copy($sourceDir.'channel_text.html',$dstDir.'channel_text.html');
		@copy($sourceDir.'content.html',$dstDir.'content.html');
		@copy($sourceDir.'style.css',$dstDir.'style.css');
		@copy($sourceDir.'footer.html',$dstDir.'footer.html');
		@copy($sourceDir.'header.html',$dstDir.'header.html');
		//3.插入模板数据
		$template_db=M('template');
		$template_db->delete(array('site'=>$this->siteid));//删除原有模板
		$templateRows=array(
		array('name'=>'首页','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/index.html','generate_path'=>'','type'=>'1','isdefault'=>'1','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'文字列表','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/channel_text.html','generate_path'=>'?m=site&c=home&a=channel&channelid={channelID}','type'=>'2','isdefault'=>'1','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'图片列表','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/channel_picture.html','generate_path'=>'?m=site&c=home&a=channel&channelid={channelID}','type'=>'2','isdefault'=>'0','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'内容','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/content.html','generate_path'=>'?m=site&c=home&a=content&contentid={contentID}','type'=>'3','isdefault'=>'1','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'样式css','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/style.css','generate_path'=>'','type'=>'4','isdefault'=>'0','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'顶部','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/header.html','generate_path'=>'','type'=>'4','isdefault'=>'0','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME),
		array('name'=>'底部','path'=>'@/smarty/templates/'.$ys.'/'.$this->site['token'].'/footer.html','generate_path'=>'','type'=>'4','isdefault'=>'0','createhtml'=>'0','site'=>$this->siteid,'time'=>SYS_TIME)
		);
		
		$defaultChannelTemplateID=0;
		$defaultContentTemplateID=0;
		$pictureChanenlTemplateID=0;
		foreach ($templateRows as $trow){
			$templateid=$template_db->insert($trow,1);
			if ($trow['type']=='2'&&$trow['isdefault']=='1'){
				$defaultChannelTemplateID=$templateid;
			}
			if ($trow['type']=='3'&&$trow['isdefault']=='1'){
				$defaultContentTemplateID=$templateid;
			}
			if ($trow['type']=='2'&&$trow['isdefault']=='0'){
				$pictureChanenlTemplateID=$templateid;
			}
		}
		//5.栏目匹配模板
		$channel_db=M('channel');
		$channel_db->update(array('channeltemplate'=>$defaultChannelTemplateID,'contenttemplate'=>$defaultContentTemplateID),array('site'=>$this->siteid));
		$channel_db->update(array('channeltemplate'=>$pictureChanenlTemplateID),array('site'=>$this->siteid,'cindex'=>'products'));
		//6.设置home数据
		$home_db=M('home');
		$home_db->update(array('advancetpl'=>1),array('token'=>$this->token));
		//
		$site_db=M('site');
		$site_db->update(array('template'=>$templateIndex),array('token'=>$this->token));
		delCache('siteByToken'.$this->token);
		//7.生成页面
		$allChannels=$channel_db->select(array('site'=>$this->siteid));
		if ($allChannels){
			$tpl=bpBase::loadAppClass('template','template');
			foreach ($allChannels as $c){
				@$tpl->createChannelPageR($c['id']);
			}
		}
		//
		unset($_SESSION['previewSkin']);
		showMessage(L('setSuccess').'，请在网站内容管理里设置栏目和内容,<a href="../index.php?token='.$this->token.'" target="_blank">点击这里预览首页</a>',$_POST['referer']);
	}
	public function templatePreview(){
		$_SESSION['previewSkin']=$_GET['skin'];
		if (!file_exists(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.$_GET['skin'])||!is_dir(ABS_PATH.'smarty'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpls'.DIRECTORY_SEPARATOR.$_GET['skin'])){
			exit('不是合法的模板');
		}
		header('Location:../index.php?token='.$this->token);
	}
	public function quitTemplatePreview(){
		unset($_SESSION['previewSkin']);
		showMessage('退出预览状态',$_SERVER['HTTP_REFERER']);
	}
}
?>