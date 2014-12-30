<?php
bpBase::loadAppClass('manage','manage',0);
class seo extends manage {
	public $sitemapTypes;
	public $seoObj;
	function __construct() {
		$this->seoObj=bpBase::loadAppClass('seoObj','seo');
		$this->sitemapTypes=$this->seoObj->sitemapTypes;
	}
	function createAllSitemap(){
		$count=count($this->sitemapTypes);
		$i=intval($_GET['i']);
		if ($i<$count-1){
			$type=$this->sitemapTypes[$i]['type'];
			$this->seoObj->createSitemap($type,0);
			$next=$i+1;
			showMessage('正在生成'.$this->sitemapTypes[$i]['name'].'sitemap','?m=seo&c=seo&a=createAllSitemap&i='.$next);
		}else {
			showMessage('正在完成','?m=seo&c=seo&a=sitemaps');
		}
	}
	function sitemapConfig(){
		if(isset($_POST['doSubmit'])){
			$arr=var_export($_POST['info'],1);
			$str="<?php\r\n"."return ".$arr.";"."\r\n?>";
			file_put_contents(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'sitemap.config.php',$str);
			showMessage('设置成功','?m='.ROUTE_MODEL.'&c='.ROUTE_CONTROL.'&a='.ROUTE_ACTION);
		}else {
			include $this->showManageTpl('sitemapConfig');		
		}
	}
	function createSitemap(){
		$type=$_GET['type'];
		$this->seoObj->createSitemap($type);
	}
	function createRobots(){
		$templateFileDir=ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'seo'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
		if (file_exists($templateFileDir.'MY_robots.template.txt')){
			$tplPath=$templateFileDir.'MY_robots.template.txt';
		}else {
			$tplPath=$templateFileDir.'robots.template.txt';
		}
		if(!file_exists($tplPath)){
			showMessage('robots模板文件不能存在',$_SERVER['HTTP_REFERER']);
		}
		if (!is_writable(ABS_PATH.'robots.txt')) {
			showMessage('根目录robots.txt文件没有修改权限',$_SERVER['HTTP_REFERER']);
		}
		$str=file_get_contents($tplPath);
		$searchArr=array('{manageDir}','{cmsDir}','{mainUrlRoot}');
		$replaceArr=array(MANAGE_DIR,CMS_DIR,MAIN_URL_ROOT);
		$str=str_replace($searchArr,$replaceArr,$str);
		file_put_contents(ABS_PATH.'robots.txt',$str);
		showMessage('生成成功，<a href="'.MAIN_URL_ROOT.'/robots.txt" target="_blank">点击预览</a>');
	}
	function sitemaps(){
		include $this->showManageTpl('sitemaps');
	}
	function keywords(){
		$keywords_db=bpBase::loadModel('keywords_model');
		if (!isset($_POST['doSubmit'])) {
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$pageSize=20;
			$rts = $keywords_db->listinfo('', 'time DESC', $page,$pageSize,'','?m=seo&a=keywords&c=seo&');
			$pages = $keywords_db->pages;
			include $this->showManageTpl('keywords');
		}else {
			if (!$_POST['id']){
				showMessage('请选择要删除的内容',$_SERVER['HTTP_REFERER']);
				exit();
			}
			foreach ($_POST['id'] as $k=>$id){
				$keywords_db->delete(array('id'=>$id));
			}
			delCache('c_keywords');
			showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
		}
	}
	function keywordSet(){
		$keywords_db=bpBase::loadModel('keywords_model');
		if (isset($_POST['doSubmit'])) {
			$info=$_POST['info'];
			$info['keyword']=htmlspecialchars($info['keyword'],ENT_COMPAT ,'GB2312');
			$info['title']=htmlspecialchars($info['title'],ENT_COMPAT ,'GB2312');
			$info['link']=htmlspecialchars($info['link'],ENT_COMPAT ,'GB2312');
			$info['target']=htmlspecialchars($info['target'],ENT_COMPAT ,'GB2312');
			if (!isset($_POST['id'])||!intval($_POST['id'])){//add
				$info['time']=SYS_TIME;
				$keywords_db->insert($info);
			}else {
				$keywords_db->update($info,array('id'=>intval($_POST['id'])));
			}
			delCache('c_keywords');
			showMessage(L('setSuccess'),'?m=seo&a=keywords&c=seo');
		}else {
			$id=isset($_GET['id'])?intval($_GET['id']):0;

			$adus=bpBase::loadAppClass('adus','manage');
			$adus->title='设置关键词';

			$adus->opers=array(
			array('text'=>'返回','href'=>$_SERVER['HTTP_REFERER'],'class'=>'back')
			);
			$adus->formAction='?m=seo&c=seo&a=keywordSet';
			$adus->jss=array('');
			$adus->headerHtml='';
			/********************输入项*************************/
			$inputs=array();
			if (!$id){
				$linkValue='http://';
				$targetValue='_blank';
			}else {
				$thisKeyWord=$keywords_db->get_one(array('id'=>$id));
				$linkValue=$thisKeyWord['link'];
				$titleValue=$thisKeyWord['title'];
				$targetValue=$thisKeyWord['target'];
				$keywordValue=$thisKeyWord['keyword'];
			}
			array_push($inputs,array('name'=>'关键词','type'=>'text','validate'=>"'required'",'style'=>'width:180px;','field'=>'info[keyword]','value'=>$keywordValue));
			array_push($inputs,array('name'=>'链接地址','type'=>'text','validate'=>"'required'",'style'=>'width:180px;','field'=>'info[link]','value'=>$linkValue));
			array_push($inputs,array('name'=>'链接title','type'=>'hidden','validate'=>"'required'",'style'=>'width:180px;','field'=>'info[title]','value'=>$titleValue));
			array_push($inputs,array('name'=>'链接target','type'=>'text','validate'=>"'required'",'style'=>'width:180px;','field'=>'info[target]','value'=>$targetValue));
			array_push($inputs,array('name'=>'','type'=>'hidden','validate'=>"",'field'=>'id','value'=>$id));

			$adus->inputs=$inputs;
			$adus->outputPage();
		}
	}
}
?>