<?php
/**
 * 专题
 */
bpBase::loadAppClass('manage','manage',0);
class m_special extends manage {
	public $special_cat_db;
	public $special_db;
	public $special_ext_db;
	function __construct() {
		$disfun=ini_get('disable_functions');
		if (!strpos($disfun,'scandir')===false){
			showmessage('scandir函数被禁用，请配置您的php环境支持该函数','###',10000);
		}
		//$this->exitWithoutAccess('special','manage');
		$this->special_cat_db=bpBase::loadModel('special_cat_model');
		$this->special_db=bpBase::loadModel('special_model');
		$this->special_ext_db=bpBase::loadModel('special_ext_model');
	}
	public function config(){
		if(isset($_POST['doSubmit'])){
			$arr=var_export($_POST['info'],1);
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'special.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('config');
		}
	}
	public function cats(){
		if(isset($_POST['taxis'])){
			foreach ($_POST['taxis'] as $id=>$value){
				$this->special_cat_db->update(array('taxis'=>$value),array('id'=>$id));
				delCache('c_specialCats');
			}
			showMessage('排序成功','?m=special&c=m_special&a=cats');
		}else {
			$cats=$this->special_cat_db->cats();
			include $this->showManageTpl('cats');
		}
	}
	public function catSet(){
		$_SESSION['canupload']=1;
		if (isset($_POST['doSubmit'])){
			$info=$_POST['info'];
			$info['time']=SYS_TIME;
			if (!isset($_POST['id'])){
				$id=$this->special_cat_db->insert($info,1);
				$this->special_cat_db->update(array('taxis'=>$id),array('id'=>$id));
				delCache('c_specialCats');
				showMessage(L('addSuccess'),'?m=special&c=m_special&a=cats');
			}else {//update
				$this->special_cat_db->update($info,array('id'=>$_POST['id']));
				delCache('c_specialCats');
				delCache('c_special'.$_POST['id']);
				showMessage(L('updateSuccess'),$_POST['referer']);
			}
		}else {
			if ($_GET['id']){
				$thisCat=$this->special_cat_db->get($_GET['id']);
			}
			include $this->showManageTpl('catSet');
		}
	}
	public function action_catDelete(){
		$this->special_cat_db->delete(array('id'=>$_GET['id']));
		delCache('c_specialCats');
		delCache('c_special'.$_GET['id']);
		showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
	}
	public function specials(){
		if(isset($_POST['taxis'])){
			foreach ($_POST['taxis'] as $id=>$value){
				$this->special_db->update(array('taxis'=>$value),array('id'=>$id));
			}
			showMessage('排序成功','?m=special&c=m_special&a=specials');
		}else {
			$cats=$this->special_cat_db->cats();
			$cats=arrToArrByKey($cats,'id');
			//
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$pageSize=20;
			if ($_GET['catid']){
				$where=array('catid'=>$_GET['catid']);
			}
			$specials = $this->special_db->listinfo($where, 'taxis DESC', $page,$pageSize,'','?m=special&c=m_special&a=specials&catid='.$_GET['catid'].'&');
			$pages = $this->special_db->pages;
			include $this->showManageTpl('specials');
		}
	}
	public function specialSet(){
		$auto_db=bpBase::loadModel('autoclassification_model');
		if (isset($_POST['doSubmit'])){
			$tpl=bpBase::loadAppClass('template','template');
			$info=$_POST['info'];
			$ext=$_POST['ext'];
			$info['time']=SYS_TIME;
			if ($info['serieid']){
				$thisSerie=$auto_db->getCfByID($info['serieid']);
				$info['companyid']=$thisSerie->parentid;
			}
			if (is_array($info['autoids'])){
				$info['autoids']=implode(',',$info['autoids']);
			}else {
				$info['autoids']='';
			}
			if (!isset($_POST['id'])){//添加专题
				$id=$this->special_db->insert($info,1);
				if ($id){
					$this->special_db->update(array('taxis'=>$id),array('id'=>$id));
					//设置扩展属性
					if ($ext){
						foreach ($ext as $k=>$v){
							$this->special_ext_db->insert(array('specialid'=>$id,'field'=>$k,'value'=>$v));
						}
					}
					/*
					//添加栏目
					$special_defaultchannel_db=bpBase::loadModel('special_defaultchannel_model');
					$channels=$special_defaultchannel_db->catChannels($info['catid']);
					if ($channels){
						$channel_db=bpBase::loadModel('channel_model');
						foreach ($channels as $c){
							$c['specialid']=$id;
							$c['site']=$id;//加在普通栏目表里，一个专题就相当于一个网站
							unset($c['id']);
							unset($c['catid']);
							unset($c['specialcatid']);
							$channel_db->insert($c);
						}
					}
					*/
				}
				if ($info['templateid']){
					$tpl->createIndexPage($id);
				}
				//
				if(intval($_POST['modelid'])){
					showMessage(L('addSuccess').',进行模型导入','?m=special&c=m_special&a=importModel2Special&modelid='.$_POST['modelid'].'&specialid='.$id);
				}else {
					showMessage(L('addSuccess'),'?m=special&c=m_special&a=specials&catid='.$row['catid']);
				}
			}else {//update
				$id=intval($_POST['id']);
				$this->special_db->update($info,array('id'=>$id));
				//设置扩展属性
				$this->special_ext_db->delete(array('specialid'=>$id));
				if ($ext){
					foreach ($ext as $k=>$v){
						$this->special_ext_db->insert(array('specialid'=>$id,'field'=>$k,'value'=>$v));
					}
				}
				delCache('c_special_'.$_POST['id']);
				if ($info['templateid']){
					$tpl->createIndexPage($id);
				}
				showMessage(L('updateSuccess'),$_POST['referer']);
			}
			
		}else {
			$template_db=bpBase::loadModel('template_model');
			$templates=$template_db->select(array('type'=>5),'*','','id ASC');
			//
			$cats=$this->special_cat_db->cats();
			if ($_GET['id']){//update
				$thisRow=$this->special_db->get($_GET['id']);
				if ($thisRow['serieid']){
					$autoids=explode(',',$thisRow['autoids']);
					$childAutos=$auto_db->select('`status`<3 AND `parentid`='.intval($thisRow['serieid']));
				}
				if ($thisRow['competeautoids']){
					$competeautoids=explode(',',$thisRow['competeautoids']);
					$competeautos=$auto_db->select(to_sqls($competeautoids,'','id'));
				}
				if ($thisRow['storeids']){
					$storeids=explode(',',$thisRow['storeids']);
					$store_db=bpBase::loadModel('store_model');
					$stores=$store_db->select(to_sqls($storeids,'','id'));
				}
				$thisCat=$this->special_cat_db->get($thisRow['catid']);
			}else {
				$thisRow=array();
				$config=loadConfig('special');
				$thisRow['urlformat']=$config['urlFormate'];
			}
			include $this->showManageTpl('specialSet');
		}
	}
	//把专题模型导入到创建的专题中
	public function importModel2Special(){
		$modelid=intval($_GET['modelid']);
		$specialid=intval($_GET['specialid']);
		$special_model_db=bpBase::loadModel('special_model_model');
		$thisModel=$special_model_db->get_one(array('id'=>$modelid));
		//0 创建保存路径
		$specialPath=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special';
		if (!file_exists($specialPath)||!is_dir($specialPath)){
			mkdir($specialPath,0777);
		}
		$specialDir=$specialPath.DIRECTORY_SEPARATOR.$thisModel['enname'].DIRECTORY_SEPARATOR;
		if (!file_exists($specialDir)||!is_dir($specialDir)){
			mkdir($specialDir,0777);
		}
		//1 拷贝模型文件
		//扫描specialModel下的文件夹，创建各个文件夹
		$specialModelSub=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'specialModel'.DIRECTORY_SEPARATOR.$thisModel['enname'];
		$files=scandir($specialModelSub);
		if ($files){
			foreach ($files as $f){
				if ($f!='.'&&$f!='..'&&is_dir($specialModelSub.DIRECTORY_SEPARATOR.$f)){
					if (!file_exists($specialDir.$f)||!is_dir($specialDir.$f)){
						mkdir($specialDir.$f,0777);
					}
				}
			}
		}
		recurse_copy(ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'specialModel'.DIRECTORY_SEPARATOR.$thisModel['enname'],$specialPath.DIRECTORY_SEPARATOR.$thisModel['enname']);
		//2 创建专题首页模板
		$specialIndexTemplate=array();
		$modelIndexTemplate=unserialize(base64_decode(file_get_contents($specialDir.'index.template.info.txt')));//获取专题模型首页模板信息
		$specialIndexTemplate['name']=$thisModel['name'].date('Y-m-d',SYS_TIME);
		$specialTemplateFileName=$thisModel['enname'].'_'.date('YmdHis',SYS_TIME).'.index.html';
		$specialIndexTemplate['path']='@/templates/special/'.$specialTemplateFileName;
		$specialIndexTemplate['generate_path']=$modelIndexTemplate['generate_path'];
		file_put_contents(ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special'.DIRECTORY_SEPARATOR.$specialTemplateFileName,file_get_contents($specialDir.'index.template.txt'));//模板代码
		$specialIndexTemplate['type']=5;
		$specialIndexTemplate['createhtml']=1;
		$specialIndexTemplate['time']=SYS_TIME;
		$tpl_db=bpBase::loadModel('template_model');
		$templateid=$tpl_db->insert($specialIndexTemplate,1);
		//3 匹配专题使用的首页模板
		$this->special_db->update(array('templateid'=>$templateid),array('id'=>$specialid));
		delCache('c_special_'.$specialid);
		//4 导入栏目信息
		$channel_db=bpBase::loadModel('channel_model');
		$channels=unserialize(base64_decode(file_get_contents($specialDir.'channels.data.txt')));
		$templates=unserialize(base64_decode(file_get_contents($specialDir.'templates.data.txt')));
		if ($channels){
			//获取默认栏目模板和内容模板
			$tpl=bpBase::loadAppClass('template','template');
			$defaultChannelTemplate=$tpl->getDefaultTemplate(1,2);
			$defaultContentTemplate=$tpl->getDefaultTemplate(1,3);
			$templateids=array();//用于记录模型中模板与写入的模板id之间的关系,模型模板id=>插入的模板id
			$handledTemplateids=array();
			foreach ($channels as $channel){
				unset($channel['id']);
				$channel['specialid']=$specialid;
				$channel['site']=$specialid;
				$channel['time']=SYS_TIME;
				if ($channel['channeltype']==1){//普通栏目，检查有没有对应的栏目模板和内容模板
					//检查是否有栏目模板文件
					if(file_exists($specialDir.$channel['cindex'].'.'.$channel['channeltemplate'].'.channel.template.txt')){
						if (!in_array($channel['channeltemplate'],$handledTemplateids)){//防止重复插入相同的栏目模板
							array_push($handledTemplateids,$channel['channeltemplate']);
							$thisChannelTemplate=array();
							$thisChannelTemplate['name']=$templates[$channel['channeltemplate']]['name'].date('Y-m-d',SYS_TIME);
							$thisChannelTemplate['generate_path']=$templates[$channel['channeltemplate']]['generate_path'];
							$thisChannelTemplateFileName='channel'.date('YmdHis',SYS_TIME).'.html';
							$thisChannelTemplate['path']='@/templates/special/'.$thisChannelTemplateFileName;
							$thisChannelTemplate['type']=$templates[$channel['channeltemplate']]['type'];
							$thisChannelTemplate['createhtml']=$templates[$channel['channeltemplate']]['createhtml'];
							$thisChannelTemplate['time']=SYS_TIME;
							$thisChannelTemplate['site']=1;
							//保存栏目模板代码
							file_put_contents(ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special'.DIRECTORY_SEPARATOR.$thisChannelTemplateFileName,file_get_contents($specialDir.$channel['cindex'].'.'.$channel['channeltemplate'].'.channel.template.txt'));//模板代码
							//插入数据库
							$thisChannelTemplateID=$tpl_db->insert($thisChannelTemplate,1);
							$templateids[$channel['channeltemplate']]=$thisChannelTemplateID;
							$channel['channeltemplate']=$thisChannelTemplateID;
						}else {
							$channel['channeltemplate']=$templateids[$channel['channeltemplate']];
						}
					}else {
						$channel['channeltemplate']=$defaultChannelTemplate['id'];
					}
					//检查是否有内容模板文件
					if(file_exists($specialDir.$channel['cindex'].'.'.$channel['contenttemplate'].'.content.template.txt')){
						if (!in_array($channel['contenttemplate'],$handledTemplateids)){//防止重复插入相同的栏目模板
							array_push($handledTemplateids,$channel['contenttemplate']);
							$thisContentTemplate=array();
							$thisContentTemplate['name']=$templates[$channel['contenttemplate']]['name'].date('Y-m-d',SYS_TIME);
							$thisContentTemplate['generate_path']=$templates[$channel['contenttemplate']]['generate_path'];
							$thisContentTemplateFileName='content'.date('YmdHis',SYS_TIME).'.html';
							$thisContentTemplate['path']='@/templates/special/'.$thisContentTemplateFileName;
							$thisContentTemplate['type']=$templates[$channel['contenttemplate']]['type'];
							$thisContentTemplate['createhtml']=$templates[$channel['contenttemplate']]['createhtml'];
							$thisContentTemplate['time']=SYS_TIME;
							$thisContentTemplate['site']=1;
							//保存栏目模板代码
							file_put_contents(ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special'.DIRECTORY_SEPARATOR.$thisContentTemplateFileName,file_get_contents($specialDir.$channel['cindex'].'.'.$channel['contenttemplate'].'.content.template.txt'));//模板代码
							//插入数据库
							$thisContentTemplateID=$tpl_db->insert($thisContentTemplate,1);
							$templateids[$channel['contenttemplate']]=$thisContentTemplateID;
							$channel['contenttemplate']=$thisContentTemplateID;
						}else {
							$channel['contenttemplate']=$templateids[$channel['contenttemplate']];
						}
					}else {
						$channel['contenttemplate']=$defaultContentTemplate['id'];
					}
				}elseif ($channel['channeltype']==2){//picture，对应图片类内容模板
					$pictureContentTemplate=$tpl_db->get_one(array('name'=>'photos'));
					if ($pictureContentTemplate){
						$channel['contenttemplate']=$pictureContentTemplate['id'];
					}else {
						$channel['contenttemplate']=$defaultContentTemplate['id'];
					}
					$channel['channeltemplate']=$defaultChannelTemplate['id'];
				}else {
					$channel['contenttemplate']=$defaultContentTemplate['id'];
					$channel['channeltemplate']=$defaultChannelTemplate['id'];
				}
				$channelid=$channel_db->insert($channel,1);
				//生成各栏目页
				$tpl->createChannelPageR($channelid);
			}
		}
		//生成专题首页
		$tpl->createIndexPage($specialid);
		
		showMessage('模型导入完成','?m=special&c=m_special&a=specials');
	}
	/**
	 * 每个分类的默认文章分类或者每个专题的分类
	 *
	 */
	public function catChannels(){
		if (isset($_GET['specialid'])||isset($_POST['specialid'])){//专题栏目
			$infoType='special';
			$channel_db=bpBase::loadModel('channel_model');
		}elseif (isset($_GET['catid'])||isset($_POST['catid'])){//每个专题分类的默认文章分类
			$infoType='specialCat';
			$channel_db=bpBase::loadModel('special_defaultchannel_model');
		}
		
		if(isset($_POST['taxis'])){
			foreach ($_POST['taxis'] as $id=>$value){
				$channel_db->update(array('taxis'=>$value),array('id'=>$id));
			}
			if ($infoType=='specialCat'){
				delCache('c_specialCatChannels'.$_POST['catid']);
			}
			showMessage('排序成功',$_SERVER['HTTP_REFERER']);
		}else {
			if ($infoType=='special'){//专题栏目
				$channels=$channel_db->select(array('specialid'=>$_GET['specialid']),'*',$limit='', $order = '`taxis` ASC');
				$thisSpecial=$this->special_db->get($_GET['specialid']);
			}elseif ($infoType=='specialCat'){//每个专题分类的默认文章分类
				$channels=$channel_db->catChannels($_GET['catid']);
				$thisCat=$this->special_cat_db->get($_GET['catid']);
			}
			include $this->showManageTpl('catChannels');
		}
	}
	/**
	 * 删除分类
	 *
	 */
	public function action_deleteChannel(){
		if (!$_POST['id']){
			showMessage('请选择要删除的栏目',$_SERVER['HTTP_REFERER']);
			exit();
		}else {
			if (isset($_GET['specialid'])||isset($_POST['specialid'])){//专题栏目
				$infoType='special';
				$channel_db=bpBase::loadModel('channel_model');
			}elseif (isset($_GET['catid'])||isset($_POST['catid'])){//每个专题分类的默认文章分类
				$infoType='specialCat';
				$channel_db=bpBase::loadModel('special_defaultchannel_model');
			}
		
			$i=0;
			foreach ($_POST['id'] as $k=>$id){
				if ($i==0){
					$thisChannel=$channel_db->get_one(array('id'=>$id));
				}
				$channel_db->delete(array('id'=>$id));
				if ($infoType=='specialCat'){
					delCache('c_special_catChannel'.$id);
				}
				$i++;
			}
			if ($infoType=='specialCat'){
				delCache('c_specialCatChannels'.$thisChannel['catid']);
			}
			showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
		}
	}
	public function action_deleteSpecial(){
		if (!$_POST['id']){
			showMessage('请选择要删除的专题',$_SERVER['HTTP_REFERER']);
			exit();
		}else {
			$i=0;
			foreach ($_POST['id'] as $k=>$id){
				$thisSpecial=$this->special_db->get($id);
				$thisCat=$this->special_cat_db->get_one(array('id'=>$thisSpecial['catid']));
				$this->special_db->delete(array('id'=>$id));
				delCache('c_special_'.$id);
				//删除首页
				$tpl=bpBase::loadAppClass('template','template');
				$filePath=$tpl->createGeneratePath($thisSpecial['templateid'],0,0,$thisSpecial);
				@unlink(ABS_PATH.$filePath);
				//删除栏目
				$channel_db=bpBase::loadModel('channel_model');
				$channel_db->delete(array('specialid'=>$id));
				//删除扩展属性
				$this->special_ext_db->delete(array('specialid'=>$id));
				$i++;
			}
			showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
		}
	}
	public function catChannelSet(){
		if (isset($_GET['specialid'])||isset($_POST['info']['specialid'])){//专题栏目
			$infoType='special';
			$channel_db=bpBase::loadModel('channel_model');
			$thisSpecial=$this->special_db->get($_GET['specialid']);
		}elseif (isset($_GET['catid'])||isset($_POST['info']['catid'])){//每个专题分类的默认文章分类
			$infoType='specialCat';
			$channel_db=bpBase::loadModel('special_defaultchannel_model');
			$thisCat=$this->special_cat_db->get($_GET['catid']);
		}
		if (isset($_POST['doSubmit'])){
			$tpl=bpBase::loadAppClass('template','template');
			$info=$_POST['info'];
			if (!isset($_POST['id'])){
				$info['time']=SYS_TIME;
				$info['site']=$info['specialid'];
				$channelid=$channel_db->insert($info,1);
				$channel_db->update(array('taxis'=>$channelid),array('id'=>$channelid));
				if ($infoType=='specialCat'){
					delCache('c_specialCatChannels'.$_POST['info']['catid']);
				}else {
					$tpl->createChannelPageR($channelid);
				}
				showMessage(L('addSuccess'),$_POST['referer']);
			}else {//update
				$thisChannel=$channel_db->get_one(array('id'=>$_POST['id']));
				if (!isset($info['externallink'])){
					$info['externallink']=0;
				}
				$channel_db->update($info,array('id'=>$_POST['id']));
				if ($infoType=='specialCat'){
					delCache('c_specialCatChannels'.$_POST['info']['catid']);
					delCache('c_special_catChannel'.$_POST['id']);
				}else {
					$tpl->createChannelPageR($thisChannel['id']);
					delCache('channelOfIndex'.$thisChannel['cindex'].'Site'.$thisChannel['site']);
				}
				showMessage(L('updateSuccess'),$_POST['referer']);
			}
		}else {
			$template_db=bpBase::loadModel('template_model');
			$channelTemplates=$template_db->select(array('site'=>1,'type'=>2),'*','','id ASC');
			$contentTemplates=$template_db->select(array('site'=>1,'type'=>3),'*','','id ASC');
			if ($_GET['id']){
				$thisChannel=$channel_db->get_one(array('id'=>$_GET['id']));
			}else {
				$thisChannel=array();
				$thisChannel['pagesize']=20;
				$thisChannel['thumbwidth']=0;
				$thisChannel['thumb2width']=0;
				$thisChannel['thumb3width']=0;
				$thisChannel['thumb4width']=0;
				$thisChannel['thumbheight']=0;
				$thisChannel['thumb2height']=0;
				$thisChannel['thumb3height']=0;
				$thisChannel['thumb4height']=0;
			}
			include $this->showManageTpl('catChannelSet');
		}
	}
	public function selectChannel(){
		$specialid=$_GET['specialid'];
		$channel_db=bpBase::loadModel('channel_model');
		$channels=$channel_db->select(array('specialid'=>$specialid),'*',$limit='', $order = '`taxis` ASC');
		include $this->showManageTpl('selectChannel');
	}
	public function isIndexExist(){
		$index=$_GET['index'];
		$id=$_GET['id'];
		if (get_magic_quotes_gpc()){
			$index=stripslashes($index);
		}
		$index=mysql_real_escape_string($index);
		$where='`specialindex`=\''.$index.'\'';
		if ($id){
			$where.=' AND id!='.intval($id);
		}
		$thisSpecial=$this->special_db->get_one($where);
		echo $thisSpecial?1:0;
	}
	public function createPages(){
		$type=isset($_GET['type'])?$_GET['type']:'content';
		if ($type=='channel'){
			$channelIDStr='';
			$channel_db=bpBase::loadModel('channel_model');
			$channelObj=bpBase::loadAppClass('channelObj','channel',1);
			$channels=$channel_db->select(array('specialid'=>intval($_GET['specialid'])),'*',$limit='', $order = '`taxis` ASC');
			if ($channels){
				$comma='';
				foreach ($channels as $c){
					$channelTotalPage=$channelObj->getChannelTotalPage($c['id']);
					for ($t=1;$t<$channelTotalPage+1;$t++){
						$channelIDStr.=$comma.$c['id'].'.'.$t;//,channelid.pageNum
						$comma=',';
					}
					//清除缓存
					$content=bpBase::loadAppClass('articleObj','article',1);
					$content->clearContentsCache($c['id']);
					$channelObj->clearCrumbCache($c['id']);
				}
			}
			file_put_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'channels.txt',$channelIDStr);
			echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['specialid'].'&type=channel\';</script>';
		}elseif ($type=='content'){
			$article_db=bpBase::loadModel('article_model');
			$contentIDStr='';
			$where=to_sqls($_POST['channels'],'','channel_id');
			$contents=$article_db->select(array('site'=>intval($_GET['specialid'])),'*',$limit='', $order = '`taxis` ASC');
			$comma='';
			if ($contents){
				foreach ($contents as $cc){
					$contentIDStr.=$comma.$cc['id'];
					$comma=',';
				}
			}
			file_put_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'contents.txt',$contentIDStr);
			echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.intval($_GET['specialid']).'&type=content\';</script>';
		}
	}
	public function export(){
		if (isset($_POST['doSubmit'])){
			//1 创建保存路径
			$modelPath=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'specialModel';
			if (!file_exists($modelPath)||!is_dir($modelPath)){
				mkdir($modelPath,777);
			}
			$saveFolder=$modelPath.DIRECTORY_SEPARATOR.$_POST['enname'];
			if (!file_exists($saveFolder)||!is_dir($saveFolder)){
				mkdir($saveFolder,777);
			}
			//2 拷贝专题文件到模型路径
			recurse_copy(ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special'.DIRECTORY_SEPARATOR.$_POST['enname'].DIRECTORY_SEPARATOR,$saveFolder.DIRECTORY_SEPARATOR);
			//3 保存模型信息：名称、简介等
			$modelInfo=array();
			$modelInfo['name']=$_POST['name'];
			$modelInfo['enname']=$_POST['enname'];
			$modelInfo['intro']=$_POST['intro'];
			file_put_contents($saveFolder.DIRECTORY_SEPARATOR.'modelInfo.txt',base64_encode(serialize($modelInfo)));
			//4 创建logo
			$logoPath=str_replace(array(MAIN_URL_ROOT.'/','/'),array(ABS_PATH,DIRECTORY_SEPARATOR),$_POST['thumb']);
			@unlink($saveFolder.DIRECTORY_SEPARATOR.'logo.jpg');
			@rename($logoPath,$saveFolder.DIRECTORY_SEPARATOR.'logo.jpg');
			//5 导出专题首页模板信息
			$thisSpecial=$this->special_db->get($_POST['specialid']);
			$tpl_db=bpBase::loadModel('template_model');
			$thisTemplate=$tpl_db->get_one(array('id'=>intval($thisSpecial['templateid'])));
			//保存专题首页模板
			$tplFile=ABS_PATH.substr($thisTemplate['path'],1);
			$thisTemplate['code']=file_get_contents($tplFile);
			file_put_contents($saveFolder.DIRECTORY_SEPARATOR.'index.template.info.txt',base64_encode(serialize($thisTemplate)));
			file_put_contents($saveFolder.DIRECTORY_SEPARATOR.'index.template.txt',$thisTemplate['code']);
			//6 导出专题使用的栏目
			$channel_db=bpBase::loadModel('channel_model');
			$channels=$channel_db->select(array('site'=>intval($_POST['specialid'])));
			file_put_contents($saveFolder.DIRECTORY_SEPARATOR.'channels.data.txt',base64_encode(serialize($channels)));//保存栏目信息
			
			//7 处理栏目及内容用的模板
			if ($channels){
				//查询出所有用到的栏目和内容templateid
				$templateids=array();
				foreach ($channels as $channel){
					array_push($templateids,$channel['channeltemplate']);
					array_push($templateids,$channel['contenttemplate']);
				}
				$templates=$tpl_db->select(to_sqls($templateids,'','id'));
				$templates=arrToArrByKey($templates);
				file_put_contents($saveFolder.DIRECTORY_SEPARATOR.'templates.data.txt',base64_encode(serialize($templates)));
				foreach ($channels as $channel){
					if ($channel['channeltype']==1){//只处理普通栏目的模板
						//只处理非默认模板
						$thisChannelTemplate=$templates[$channel['channeltemplate']];
						$thisContentTemplate=$templates[$channel['contenttemplate']];
						if (!$thisChannelTemplate['isdefault']){//栏目模板
							$tplFile=ABS_PATH.substr($thisChannelTemplate['path'],1);
							$code=file_get_contents($tplFile);
							file_put_contents($saveFolder.DIRECTORY_SEPARATOR.$channel['cindex'].'.'.$thisChannelTemplate['id'].'.channel.template.txt',$code);
						}
						if (!$thisContentTemplate['isdefault']){//内容模板
							$tplFile=ABS_PATH.substr($thisContentTemplate['path'],1);
							$code=file_get_contents($tplFile);
							file_put_contents($saveFolder.DIRECTORY_SEPARATOR.$channel['cindex'].'.'.$thisContentTemplate['id'].'.content.template.txt',$code);
						}
					}
				}
			}
			showMessage('导出完成','?m=special&c=m_special&a=specials');
		}else {
			$adus=bpBase::loadAppClass('adus','manage');
			$adus->title='导出专题为模型';
			$adus->formAction='?m=special&c=m_special&a=export';
			$adus->tip='导出条件：专题所用的图片、js和css均在“/templates/special/当前专题/”文件夹下';
			/********************输入项*************************/
			//获取专题模板文件夹列表
			$specialPathSub=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'special';
			$files=scandir($specialPathSub);
			$folderArr=array();
			if ($files){
				foreach ($files as $f){
					if ($f!='.'&&$f!='..'&&is_dir($specialPathSub.DIRECTORY_SEPARATOR.$f)){
						array_push($folderArr,array('itemValue'=>$f,'itemText'=>$f,'selected'=>false));
					}
				}
			}
			//
			$inputs=array();
			array_push($inputs,array('name'=>'名称','type'=>'text','validate'=>"'required'",'style'=>'width:180px;','field'=>'name','value'=>''));
			array_push($inputs,array('name'=>'','type'=>'hidden','validate'=>"'required'",'field'=>'specialid','value'=>$_GET['specialid']));
			array_push($inputs,array('type'=>'select','field'=>'enname','name'=>'选择文件夹','data'=>$folderArr,'complement'=>'本专题的css、图片等文件存放文件夹'));
			array_push($inputs,array('name'=>'缩略图','type'=>'thumb','validate'=>"'required'",'style'=>'width:180px;','field'=>'thumb','value'=>'','width'=>800,'height'=>800));
			array_push($inputs,array('name'=>'简介','type'=>'textarea','style'=>'width:80%;font-size:12px;height:60px;','field'=>'intro','value'=>''));
			$adus->inputs=$inputs;
			$adus->outputPage();
		}
	}
	//专题模型
	public function models(){
		$special_model_db=bpBase::loadModel('special_model_model');
		if (isset($_POST['doSumit'])){
			if (!$_POST['id']){
				showMessage('请选择要删除的模型',$_SERVER['HTTP_REFERER']);
				exit();
			}else {
				foreach ($_POST['id'] as $k=>$id){
					$special_model_db->delete(array('id'=>$id));
				}
				showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
			}
		}else{
			$ms=$special_model_db->select();
			include $this->showManageTpl('models');
		}
	}
	public function importModel(){
		$special_model_db=bpBase::loadModel('special_model_model');
		if (isset($_POST['doSubmit'])){
			if ($_POST['info']){
				foreach ($_POST['info'] as $enname){
					$row=$_POST[$enname];
					$row['enname']=$enname;
					$row['time']=SYS_TIME;
					$special_model_db->insert($row);
				}
			}
			showMessage('导入完成','?m=special&c=m_special&a=models');
		}else {
			$models=$special_model_db->select();
			$folderNames=array();//已导入的文件夹
			if ($models){
				foreach ($models as $m){
					array_push($folderNames,$m['enname']);
				}
			}
			//扫描specialModel下的文件夹
			//
			$specialModelSub=ABS_PATH.'templates'.DIRECTORY_SEPARATOR.'specialModel';
			$files=scandir($specialModelSub);
			$folderArr=array();//没被导入的文件夹名称
			if ($files){
				foreach ($files as $f){
					if ($f!='.'&&$f!='..'&&is_dir($specialModelSub.DIRECTORY_SEPARATOR.$f)&&!in_array($f,$folderNames)){
						array_push($folderArr,$f);
					}
				}
			}
			if ($folderArr){
				$ms=array();
				$i=0;
				foreach ($folderArr as $f){
					$modelDir=$specialModelSub.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR;
					$modelInfoStr=base64_decode(file_get_contents($modelDir.'modelInfo.txt'));
					$modelInfo=unserialize($modelInfoStr);
					$modelInfo['logo']='/templates/specialModel/'.$f.'/logo.jpg';
					$ms[$i]=$modelInfo;
					$i++;
				}
				include $this->showManageTpl('importModel');
			}else {
				showMessage('没有可导入的模型',$_SERVER['HTTP_REFERER']);
			}
		}
	}
	//创建专题时选择模型
	public function selectModel(){
		$special_model_db=bpBase::loadModel('special_model_model');
		$ms=$special_model_db->select();
		include $this->showManageTpl('selectModel');
	}
	//添加专题内容专题选择树
	public function contentManageTree(){
		$cats=$this->special_cat_db->cats();
		$specials=$this->special_db->select('','id,url,name,catid','','id DESC');
		$catSpecials=array();
		if ($cats){
			foreach ($cats as $cat){
				$catSpecials[$cat['id']]=array();
			}
		}
		if ($specials){
			foreach ($specials as $s){
				array_push($catSpecials[$s['catid']],$s);
			}
		}
		//
		include $this->showManageTpl('contentManageTree');
	}
	//专题树用到的栏目string
	public function specialChannelsTreeStr(){
		$specialid=intval($_GET['specialid']);
		$channel_db=bpBase::loadModel('channel_model');
		$channels=$channel_db->select(array('specialid'=>$specialid),'*',$limit='', $order = '`taxis` ASC');
		if ($channels){
			foreach ($channels as $c){
				echo '<div style="padding:0 0 0 48px;"><nobr><img src="image/folder.gif" align="absmiddle" /> <a href="?m=article&c=m_article&a=articles&id='.$c['id'].'&site='.$specialid.'" target="sright">'.$c['name'].'</a></nobr></div>';
			}
		}else {
			echo '<div style="padding:0 0 0 48px;">该专题没有栏目</div>';
		}
	}
	//内容管理 选择专题
	public function content_selectSpecial(){
		include $this->showManageTpl('content_selectSpecial');
	}
}
?>