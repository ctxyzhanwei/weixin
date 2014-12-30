<?php
bpBase::loadAppClass('manage','manage',0);
class m_article extends manage {
	public $content_db;
	public $article_db;
	public $articleObj;
	function __construct() {
		$this->article_db = bpBase::loadModel('article_model');
		$this->content_db = bpBase::loadModel('content_model');
		$this->articleObj=bpBase::loadAppClass('articleObj','article');
		parent::__construct();
		$this->exitWithoutAccess();
		if (isset($_GET['site'])&&$_GET['site']!=$this->siteid){
			exit();
		}
	}
	
	function _isAutoContentExist($autoid,$contentid){
		return $this->content_db->count(array('contentid'=>$contentid,'autoid'=>$autoid));
	}
	function _setFirstImageAsThumb($thisChannel,$contentStr,$imgNo=1){
		$thumbWidht=$thisChannel->thumbwidth;
		$thmbHeight=$thisChannel->thumbheight;
		if (!$thumbWidht||!$thmbHeight){
			return '';
		}
		//get image url
		$contentStr=stripslashes($contentStr);
		
		@preg_match_all('#src="((((?!").)+).(jpg|bmp))"#i',$contentStr,$img_array);
		$img_array_urls=$img_array[1];
		if ($img_array_urls){
			$imgNo=abs(intval($imgNo));
			$imgNo=$imgNo<1?1:$imgNo;
			$imgUrl=$img_array_urls[$imgNo-1];
			if (!strpos($imgUrl,'ttp://')){
				$imgUrl=MAIN_URL_ROOT.$imgUrl;
			}
			$time=SYS_TIME;
			$pathInfo=upFileFolders($time);
			$dstFolder=$pathInfo['path'];
			$rand=rand(0,10000);
			$tempImgPath=ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp.jpg';
			if(file_exists($tempImgPath)){
				@unlink($tempImgPath);
			}
			@httpCopy($imgUrl,$tempImgPath);
			//new start,带水印的图片加缩略图需要裁切掉水印
			if (file_exists(ABS_PATH.'constant'.DIRECTORY_SEPARATOR.'watermark.config.php')){
				@include_once(ABS_PATH.'constant'.DIRECTORY_SEPARATOR.'watermark.config.php');
				if (USE_WATERMARK){
					if (WATERMARK_TYPE!='text'){
						$oImgSize=getimagesize($tempImgPath);//原图尺寸
						//水印尺寸
						$watermarkImageAttr = @getimagesize(ABS_PATH.'editor'.DIRECTORY_SEPARATOR.'ckfinder'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'watermark'.DIRECTORY_SEPARATOR.'logo.png');
						$width=$oImgSize[0];
						$height=$oImgSize[1]-$watermarkImageAttr[1];//新图高度为原图高度减水印高度
						//
						$firstImg=imagecreatefromjpeg($tempImgPath);
						if (function_exists("imagecreatetruecolor")){//GD2.0.1
							$dstScaleImg = imagecreatetruecolor($width,$height);
						}else{
							$dstScaleImg = imagecreate($width,$height);
						}
						imagecopy($dstScaleImg,$firstImg,0,0,0,0,$oImgSize[0],$oImgSize[1]);//裁切
						ImageJPEG($dstScaleImg,ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp.jpg');//保存图片
						imagedestroy($dstScaleImg);
						imagedestroy($firstImg);
					}
				}
			}
			//new end，裁切水印end
			bpBase::loadSysClass('image');
			image::zfResize(ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp.jpg',$dstFolder.$time.$rand.'.jpg',$thumbWidht,$thmbHeight,1|4,2);
			$this->_setThumb($thisChannel,ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp.jpg',$dstFolder,$time.$rand,'jpg');
			@unlink(ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp.jpg');
			//
			$year=date('Y',$time);
			$month=date('m',$time);
			$day=date('d',$time);
			$url=$pathInfo['url'].$time.$rand.'.jpg';
			//
			$location='http://'.$_SERVER['HTTP_HOST'].CMS_DIR_PATH.$url;
			return $location;
		}else {
			return '';
		}
		//
	}
	/**
	 * 生成其他尺寸缩略图
	 *
	 * @param unknown_type $channelObj 相应栏目object
	 * @param unknown_type $folder 存储在哪个文件夹底下
	 * @param unknown_type $fileName 主缩略图名称
	 * @param unknown_type $fileType 主缩略图后缀
	 */
	function _setThumb($channelObj,$dstFile,$folder,$fileName,$fileType='jpg'){
		$this->articleObj->setOtherThumb($channelObj,$dstFile,$folder,$fileName,$fileType);
	}
	function _addAutoLink($contentHtml){
		$config=loadConfig('cmsContent');
		if ($config['autoSerieLink']){
			$keyLink=bpBase::loadAppClass('keyLink','article');
			$contentHtml = stripslashes($contentHtml);
			//必须在mysql_real_escape之前执行，否则有\
			/*********先把title和alt属性处理掉************/
			$linkDatas=array();
			//
			/*
			$auto_db=bpBase::loadModel('autoclassification_model');
			$autos=$auto_db->select('`grade`=3 AND `status`<3');
			if ($autos){
				foreach ($autos as $a){
					array_push($linkDatas,array(0=>$a['name'],1=>CAR_URL_ROOT.'/'.$a['id'],2=>$a['name'],3=>'_blank'));
				}
			}
			*/
			//keywords
			$keywords_db=bpBase::loadModel('keywords_model');
			$keywords=$keywords_db->keywords();
			if ($keywords){
				foreach ($keywords as $kw){
					array_push($linkDatas,array(0=>$kw['keyword'],1=>$kw['link'],2=>$kw['title'],3=>$kw['target']));
				}
			}
			$contentHtml=$keyLink->_keylinks($contentHtml,$linkDatas);
		}
		return $contentHtml;
	}
	function _calContentPageCount($content){
		$article_obj=bpBase::loadAppClass('articleObj','article');
		$contents=$article_obj->contentPagination($content);
		$contentCount=count($contents);
		return $contentCount;
	}
	function _expandlinks($links,$URI){
		
		preg_match("/^[^\?]+/",$URI,$match);

		$match = preg_replace("|/[^\/\.]+\.[^\/\.]+$|","",$match[0]);
		$match = preg_replace("|/$|","",$match);
		$match_part = parse_url($match);
		$match_root =
		$match_part["scheme"]."://".$match_part["host"];
				
		$search = array( 	"|^http://".preg_quote(DOMAIN_NAME)."|i",
							"|^(\/)|i",
							"|^(?!http://)(?!mailto:)|i",
							"|/\./|",
							"|/[^\/]+/\.\./|"
						);
						
		$replace = array(	"",
							$match_root."/",
							$match."/",
							"/",
							"/"
						);			
				
		$expandedLinks = preg_replace($search,$replace,$links);

		return $expandedLinks;
	}
	
	/**删除缓存：根据栏目id和地区获取内容
	 * 
	 */
	function _clearContentCacheWithGeoid($thisChannel,$geoid){
		$channelid=$thisChannel->id;
		$totalCount=$this->article_db->count(array('channel_id'=>$channelid,'geoid'=>$geoid));
		$thisChannelPageSize=intval($thisChannel->pagesize);
		$pageCount=($totalCount/$thisChannelPageSize)+1;
		for ($i=0;$i<$pageCount;$i++){
			$page=$i+1;
			delCache('contentsByChannelID'.$channelid.'Geo'.$geoid.'page'.$page);
		}
		delCache('contentsCountByChannelID'.$channelid.'Geo'.$geoid);
	}
	function articleSet(){
		$articleConstant=bpBase::loadAppClass('articleConstant','article');
		$sourceTypes=$articleConstant->sourceTypes();
		//
		$articleObj=bpBase::loadAppClass('articleObj','article');
		if (isset($_GET['id'])){
			$thisContent=$this->article_db->get_row('`id`='.intval($_GET['id']));
		}else {
			$thisContent->time=SYS_TIME;
			$thisContent->cancomment=1;
			$thisContent->author=$this->realname;
			$thisContent->source=loadConfig('system','siteName');
		}
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		
		$thisChannel=$channelObj->getChannelByID($_GET['channelid']);
		
		$this->_access_ContentUpdate($thisChannel);
		//
		if (!$thisChannel->specialid){
			$siteObj=bpBase::loadAppClass('siteObj','site');
			$thisSite=$siteObj->getSiteByID($thisChannel->site);
			if (!intval($thisSite->main)){
				$_SESSION['siteDir']=$thisSite->siteindex;
				if (intval($thisSite->abspath)){
					$_SESSION['url']=$thisSite->url;
				}else {
					$_SESSION['url']='/'.$thisSite->siteindex;
				}
			}else {
				$_SESSION['siteDir']='';
				$_SESSION['url']='';
			}
		}else {//专题
			$_SESSION['siteDir']='';
			$_SESSION['url']='';
			$special_db=bpBase::loadModel('special_model');
		}
		if (strlen(CMS_DIR_PATH)){
			$_SESSION['cms_dir_path']=CMS_DIR_PATH;
		}
		$_SESSION['canupload']=1;
		//
		$contentConfig=loadConfig('cmsContent');
		$sameTitleDaysLimit=$contentConfig['sameTitleDaysLimit'];
		include $this->showManageTpl('articleSet');
	}
	function _add($row,$thisChannel,$createHtml=0){
		$row['content']=str_replace(array("'"),array('&#039;'),$row['content']);
		//$row['content']=$this->closetags($row['content']);
		if ($row['time']>SYS_TIME){
			$row['pubed']=0;//待发布的文章
		}

		$rt=$this->article_db->insert($row,1);
		$insertID=$rt;
		if ($rt){
			$this->article_db->update(array('taxis'=>$insertID),array('id'=>$insertID));//更新taxis
			
			//生成内容页，或者更新链接地址
			if ($createHtml){
				$tpl=bpBase::loadAppClass('template','template');
				$tpl->createContentPageR($rt,$thisChannel);
			}
			//clear cache
			$article_obj=bpBase::loadAppClass('articleObj','article');
			$article_obj->clearContentsCache($row['channel_id'],'add',$thisChannel);
		}
		return $rt;
	}
	function action_add(){
		$article_obj=bpBase::loadAppClass('articleObj','article');
		$time=SYS_TIME;
		$dates=explode('-',$_POST['adddate']);
		$times=explode(':',$_POST['addtime']);
		$time=mktime(intval($times[0]),intval($times[1]),intval($times[2]),intval($dates[1]),intval($dates[2]),intval($dates[0]));
		//var_dump($_POST);
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisChannel=$channelObj->getChannelByID($_POST['channelid']);
		$title=$_POST['title'];


		//处理子页标题和内容
		$contentsArr=array();
		if ($_POST['content']){
			foreach ($_POST['content'] as $k=>$v){
				$pageTitle=$_POST['pageTitle'][$k];
				$order=$_POST['order'][$k];
				//
				array_push($contentsArr,array('order'=>$order,'pageTitle'=>$pageTitle,'content'=>$v));
			}
			$contentsArr=sort2DArray($contentsArr,'order');
			$titles='';
			$contentStr='';
			$comma='';
			$contentSep='';
			foreach ($contentsArr as $c){
				$titles.=$comma.$c['pageTitle'];
				$comma='|';
				if (strlen($c['content'])){
					$contentStr.=$contentSep.$c['content'];
					$contentSep='<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>';
				}
			}
		}
		//thumb
		$thumb=$_POST['thumb'];
		if (!$thumb&&$_POST['autoThumb']){
			$thumb=$this->_setFirstImageAsThumb($thisChannel,$contentStr,$_POST['autoThumbNo']);
		}
		//
		//clear link
		if (intval($_POST['clearhref'])){
			$contentStr=clearHtmlTagA($contentStr);
		}
		$canComment=intval($_POST['closeComment'])?0:1;

		//$intro
		$intro=$_POST['intro'];
		if (!$intro&&$_POST['autoIntro']){
			$stag=bpBase::loadAppClass('stag','template');
			//
			$handledStagHtml=$stag->handleStag($contentStr);
			$handledStagHtml=remove_html_tag($handledStagHtml);
			$intro=mb_substr($handledStagHtml,0,intval($_POST['autoIntroLen']),'gbk');
		}
		$row['token']=$this->token;
		$row=array('channel_id'=>$thisChannel->id,'title'=>$title,'subtitle'=>$_POST['subtitle'],'link'=>$_POST['link'],'externallink'=>intval($_POST['externallink'][0]),'thumb'=>$thumb,'content'=>$contentStr,'intro'=>$intro,'author'=>$_POST['author'],'source'=>$_POST['source'],'uid'=>$_SESSION['cmsuid'],'time'=>$time,'last_update'=>$time,'keywords'=>$_POST['keywords'],'cancomment'=>$canComment,'titles'=>$titles,'geoid'=>0,'site'=>0,'pagecount'=>0,'sourcetype'=>intval($_POST['sourcetype']));
		//$channelID,$title,$subtitle,$link,$externallink,$thumb,$content,$intro,$author,$source,$time,$uid=0,$keywords='',$autoid='',$pagecount=0,$canComment=1,$titles='',$geoid=0
		//locy采集处理 start
		if (isset($_POST['locoy'])){
			$row['titles']=stripslashes($_POST['titles']);
			$row['titles']=str_replace('<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>','|',$row['titles']);
			$row['content']=stripslashes($_POST['locycontent']);
			$row['content']=str_replace('src="upload/','src="/upload/',$row['content']);
			if (!$thumb&&$_POST['autoThumb']){
				$thumb=$this->_setFirstImageAsThumb($thisChannel,$row['content'],1);
				$row['thumb']=$thumb;
			}
			//
			$autoidAndkeyword=$this->articleObj->getRalateAuto($row['title'].$row['titles'].mb_substr($row['content'],0,600,'gbk'));//根据标题获取相关车型
			$row['autoid']=$autoidAndkeyword['autoid'];
			$row['keywords']=$autoidAndkeyword['keyword'];
			//
			$locoytimes=explode(' ',$_POST['locytime']);
			$dates=explode('-',$locoytimes[0]);
			$times=explode(':',$locoytimes[1]);
			$time=mktime(intval($times[0]),intval($times[1]),intval($times[2]),intval($dates[1]),intval($dates[2]),intval($dates[0]));
			$row['time']=$time;
			$row['last_update']=$time;
		}
		//
		
		//locy采集处理 end
		
		if ($thisChannel->id!=1){
			//$row['content']=$this->_addAutoLink($row['content']);
		}
		$row['pagecount']=$this->_calContentPageCount($row['content']);
		//
		//
		if (!$_POST['site']){
			$row['site']=1;
		}else {
			$row['site']=intval($_POST['site']);
		}
		//
		$sepImageHandle=1;//单独处理内容中的远程网络图片
		/*
		if (file_exists(ABS_PATH.'/config/cms.php')){
			require(ABS_PATH.'/config/cms.php');
			if (defined('CONTENT_IMAGE_SEP_HANDLE')&&CONTENT_IMAGE_SEP_HANDLE){
				$sepImageHandle=1;
			}
		}
		*/
		$row['content']=$article_obj->autoSaveRemoteImage($row['content']);
		
		$siteObj=bpBase::loadAppClass('siteObj','site');
		$thisSite=$siteObj->getSiteByID($thisChannel->site);
		$row['content']=str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$row['content']);
		//
		if (substr($row['keywords'],0,1)!=','){
			$row['keywords']=','.$row['keywords'];
		}
		if ($row['subtitle']==''){
			$row['subtitle']=$row['title'];
		}
		$rt=$this->_add($row,$thisChannel);
		$insertID=$rt;
		if ($rt){
			if ($_POST['contentGroup']){
				$contentgroup_content_db=bpBase::loadModel('contentgroup_content_model');
				foreach ($_POST['contentGroup'] as $k=>$v){
					$groupContentTitle=$_POST['subtitle'];
					if (!$groupContentTitle){
						$groupContentTitle=$title;
					}
					if (defined('CMS_CITY_ID')){
						$geoid=CMS_CITY_ID;
					}else {
						$geoid=intval($row['geoid']);
					}
					$contentgroup_content_db->insert(array('groupid'=>$v,'contentid'=>$rt,'title'=>$groupContentTitle,'geoid'=>$geoid,'taxis'=>$insertID));
					delCache('contentsOfGroup'.$v.'geoid'.$geoid);
				}
			}
			
			//生成
			$tpl=bpBase::loadAppClass('template','template');
			$tpl->createContentPageR($rt,$thisChannel);
			//
			if ($sepImageHandle){
				//echo '<script>window.location.href=\'/'.CMS_DIR.'/saveRemoteImage.php?success=1&id='.$rt.'\';</script>';
			}else {
				//echo '<script>window.location.href=\'/'.CMS_DIR.'/cachesAction.php?success=1&actionType=content_add&autoids='.$row['autoid'].'&contentid='.$rt.'&channelid='.$thisChannel->id.'&site='.$row['site'].'\';</script>';
			}
			delCache('c_contentsOf'.$thisChannel->id);
			showMessage(L('addSuccess'),'?m=article&c=m_article&a=articles&id='.$thisChannel->id.'&site='.$thisChannel->site);
		}
		
	}
	function _access_ContentUpdate($thisChannel){
		/*
		$manageSites=$this->userSites();
		$flag=0;
		$mainSiteAccess=0;//是否有主站操作权限
		if ($manageSites){
			foreach ($manageSites as $ms){
				if ($ms->id==1){
					$mainSiteAccess=1;
				}
				if (intval($thisChannel->site)==intval($ms->id)){
					$flag=1;
					break;
				}
			}
		}
		if (!$mainSiteAccess&&!$flag&&!$this->isAdministrator&&$thisChannel->site!=1){
			exit();
		}
		*/
	}
	function _deleteThumb($thisid,$thumb){
		$thisid=intval($thisid);
		if (strlen($thumb)){
			$isFileUsedByOther=intval($this->article_db->count('thumb=\''.$thumb.'\' AND id!='.$thisid));
			if (file_exists(ABS_PATH.$thumb)&&!$isFileUsedByOther){
				unlink(ABS_PATH.$thumb);
				if (file_exists(ABS.str_replace('.','_big.',$thumb))){
					@unlink(ABS.str_replace('.','_big.',$thumb));
				}
				if (file_exists(ABS.str_replace('.','_small.',$thumb))){
					@unlink(ABS.str_replace('.','_small.',$thumb));
				}
				if (file_exists(ABS.str_replace('.','_middle.',$thumb))){
					@unlink(ABS.str_replace('.','_middle.',$thumb));
				}
			}
		}
	}
	
	function action_update(){
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisContent=$articleObj->getContentByID($_GET['id']);
		$channelID=$thisContent->channel_id;
		$thisChannel=$channelObj->getChannelByID($channelID);
		$this->_access_ContentUpdate($thisChannel);
		if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST'){
			$dates=explode('-',$_POST['adddate']);
			$times=explode(':',$_POST['addtime']);
			$time=mktime(intval($times[0]),intval($times[1]),intval($times[2]),intval($dates[1]),intval($dates[2]),intval($dates[0]));
			//
			$title=$_POST['title'];
			if ($thisChannel->channeltype==3){//auto
				$auto_db=bpBase::loadModel('autoclassification_model');
				$thisAuto=$auto_db->getCfByID(intval($_POST['autoid']));
				if (!strlen($title)){
					$title=$thisAuto->name;
				}
			}
			//处理子页标题和内容
			$contentsArr=array();
			if ($_POST['content']){
				foreach ($_POST['content'] as $k=>$v){
					$pageTitle=$_POST['pageTitle'][$k];
					$order=$_POST['order'][$k];
					//
					array_push($contentsArr,array('order'=>$order,'pageTitle'=>$pageTitle,'content'=>$v));
				}
				$contentsArr=sort2DArray($contentsArr,'order');
			}

			$titles='';
			$contentStr='';
			$comma='';
			$contentSep='';
			foreach ($contentsArr as $c){
				$titles.=$comma.$c['pageTitle'];
				$comma='|';
				if (strlen($c['content'])){
					$contentStr.=$contentSep.$c['content'];
					$contentSep='<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>';
				}
			}
			//clear link
			if (intval($_POST['clearhref'])){
				$contentStr=clearHtmlTagA($contentStr);
			}
			$canComment=intval($_POST['closeComment'])?0:1;
			//thumb
			$thumb=$_POST['thumb'];
			
			if (!$thumb&&$_POST['autoThumb']){
				$thumb=$this->_setFirstImageAsThumb($thisChannel,$contentStr,$_POST['autoThumbNo']);
			}
			//$intro
			$intro=$_POST['intro'];
			if (!$intro&&$_POST['autoIntro']){
				$stag=bpBase::loadAppClass('stag','template');
				//
				$handledStagHtml=$stag->handleStag($contentStr);
				$handledStagHtml=remove_html_tag($handledStagHtml);
				$intro=mb_substr($handledStagHtml,0,intval($_POST['autoIntroLen']),'gbk');
			}
			$row=array('title'=>$title,'subtitle'=>$_POST['subtitle'],'link'=>$_POST['link'],'externallink'=>intval($_POST['externallink'][0]),'thumb'=>$thumb,'content'=>$contentStr,'intro'=>$intro,'author'=>$_POST['author'],'source'=>$_POST['source'],'uid'=>intval($_SESSION['cmsuid']),'time'=>$time,'last_update'=>SYS_TIME,'keywords'=>$_POST['keywords'],'cancomment'=>$canComment,'titles'=>$titles,'geoid'=>intval($_POST['geo_id']),'pagecount'=>0,'sourcetype'=>intval($_POST['sourcetype']));
			$updateCondition=array('id'=>$thisContent->id);
			$ocontent=$thisContent;
			if (intval($ocontent->channel_id)!=1){
				$row['content']=$this->_addAutoLink($row['content']);
			}
			//
			$row['pagecount']=$this->_calContentPageCount($row['content']);
			$row['content']=$articleObj->autoSaveRemoteImage($row['content']);
			//
			$siteObj=bpBase::loadAppClass('siteObj','site');
			$thisSite=$siteObj->getSiteByID($thisChannel->site);
			$row['content']=str_replace('src="/','src="http://'.$_SERVER['HTTP_HOST'].'/',$row['content']);
			//
			if (substr($row['keywords'],0,1)!=','){
				$row['keywords']=','.$row['keywords'];
			}
			$row['content']=str_replace(array("'"),array('&#039;'),$row['content']);
			//$row['content']=$this->closetags($row['content']);
			$rt=$this->article_db->update($row,$updateCondition);

			if ($rt){
				if ($row['geoid']){
					$this->_clearContentCacheWithGeoid($thisChannel,$row['geoid']);
				}
				if ($ocontent->geoid!=$row['geoid']){
					$this->_clearContentCacheWithGeoid($thisChannel,$ocontent->geoid);
				}
				
				delCache('autoContentsOfChannel'.$ocontent->channel_id);
				delCache('c_content'.$thisContent->id);
				//生成内容页，或者更新链接地址
				$tpl=bpBase::loadAppClass('template','template');
				$tpl->createContentPageR($thisContent->id,$thisChannel);
				//delete thumb
				if ($ocontent->thumb&&$ocontent->thumb!=$row['thumb']){
					$this->_deleteThumb($id,$ocontent->thumb);
				}
				//clear cache
				$articleObj->clearContentsCache($ocontent->channel_id,'update',$thisChannel);
				//内容组
				$contentgroup_content_db=bpBase::loadModel('contentgroup_content_model');
				$contentid=$thisContent->id;
				if (defined('CMS_CITY_ID')){//zzqcw
					$geoid=CMS_CITY_ID;
					$groupIDsOfThisContent=$contentgroup_content_db->select(array('geoid'=>$row['geoid'],'contentid'=>$thisContent->id),'groupid');
				}else {
					$geoid=$row['geoid'];
					//$groupIDsOfThisContent=$contentgroup_content_db->select(array('contentid'=>$thisContent->id),'groupid');
				}
				//如果该内容不在选定的内容组里面，则添加
				$postGroupIDs=array();
				if ($_POST['contentGroup']){
					foreach ($_POST['contentGroup'] as $k=>$v){
						if (!$groupIDsOfThisContent||($groupIDsOfThisContent&&!in_array(array('groupid'=>$v),$groupIDsOfThisContent))){
							$contentgroup_content_db->insert(array('groupid'=>$v,'contentid'=>$thisContent->id,'title'=>$row['title'],'geoid'=>$geoid,'taxis'=>$thisContent->taxis));
							delCache('contentsOfGroup'.$v.'geoid'.$geoid);
						}
						array_push($postGroupIDs,$v);
					}
				}
				//如果原来内容组有该内容，而现在没有则删除
				if ($groupIDsOfThisContent){
					foreach ($groupIDsOfThisContent as $k=>$v){
						if (!in_array($v['groupid'],$postGroupIDs)){
							$this->_deleteContentInGroup($thisContent->id,$v['groupid']);
						}
					}
				}
			}
			//专题内容自动生成专题首页
			if ($thisContent->site>99){
				$tpl=bpBase::loadAppClass('template','template');
				$tpl->createIndexPage($thisContent->site);
			}
			delCache('c_contentsOf'.$thisContent->channel_id);
			showMessage(L('updateSuccess'),$_POST['referer']);
			//echo '<script>window.location.href=\'/'.CMS_DIR.'/cachesAction.php?actionType=content_update&autoids='.$row['autoid'].'&oldautoids='.$thisContent->autoid.'&contentid='.$thisContent->id.'&channelid='.$thisContent->channel_id.'&site='.$thisContent->site.'\';</script>';
		}
	}
	function _deleteContentInGroup($contentid,$groupid){
		$contentgroup_content_db=bpBase::loadModel('contentgroup_content_model');
		$contentid=intval($contentid);
		$groupid=intval($groupid);
		if (defined('CMS_CITY_ID')){
			$where=array('geoid'=>CMS_CITY_ID,'contentid'=>$contentid,'groupid'=>$groupid);
			$geoid=CMS_CITY_ID;
		}else {
			$thisContent=$contentgroup_content_db->get_one(array('contentid'=>$contentid,'groupid'=>$groupid));
			$where=array('contentid'=>$contentid,'groupid'=>$groupid);
			$geoid=$thisContent->geoid;
		}
		$rt=$contentgroup_content_db->delete($where);
		if ($rt){
			delCache('contentsOfGroup'.$groupid.'geoid'.$geoid);
		}
		return $rt;
	}
	function articles(){
		$paretID=isset($_GET['id'])?intval($_GET['id']):0;
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisChannel=$channelObj->getChannelByID($_GET['id']);
		if ($thisChannel->token!=$this->token){
			exit();
		}
		if ($thisChannel->site==1){
		//if (!in_array($paretID,$this->accessChannels($this->rights($this->userid)))&&!$this->isAdministrator){
			//exit('对不起，您没有操作权限');
		//}
		}
		$siteObj=bpBase::loadAppClass('siteObj','site');
		if (intval($_GET['site'])>1&&$_GET['site']<100){
			$thisSite=$siteObj->getSiteByID($_GET['site']);
		}
		//
		$crumb='';
		//$comment_db=bpBase::loadModel('content_comment_model');
		
		
		if (intval($_GET['id'])){
			$crumbArr=$channelObj->crumbArr($_GET['id']);
			$arrCount=count($crumbArr);
			for ($i=0;$i<$arrCount;$i++){
				if (intval($crumbArr[$i]['id'])!=0){
					$crumb.='<a href="?m=article&c=m_article&a=articles&id='.$crumbArr[$i]['id'].'">'.$crumbArr[$i]['name'].'</a>-';
				}
			}
		}
		$pageSize=30;
		$page=isset($_GET['page'])?intval($_GET['page']):1;
		if (defined('CMS_CITY_ID')&&$thisChannel->iscity){
			$contents=$this->article_db->listinfo(array('channel_id'=>$_GET['id'],'geoid'=>CMS_CITY_ID),'taxis DESC', $page, $pageSize, $key='',$urlrule = '?m=article&c=m_article&a=articles&id='.$_GET['id'].'&site='.$_GET['site'].'&');
		}else {
			$contents=$this->article_db->listinfo(array('channel_id'=>$_GET['id']),'taxis DESC', $page, $pageSize, $key='',$urlrule = '?m=article&c=m_article&a=articles&id='.$_GET['id'].'&site='.$_GET['site'].'&');
		}
		delCache('c_contentsOf'.$thisChannel->id);
		include $this->showManageTpl('articles');
	}
	function action_taxis(){
		if ($_POST['taxis']){
			$i=0;
			foreach ($_POST['taxis'] as $id=>$taxisValue){
				if ($i==0){
					$articleObj=bpBase::loadAppClass('articleObj','article');
					$thisContent=$articleObj->getContentByID($id);
					$channelID=$thisContent->channel_id;
				}
				$this->article_db->update(array('taxis'=>$taxisValue),array('id'=>$id));
				$i++;
			}
			delCache('autoContentsOfChannel'.$channelID.'p1');
		}
		showMessage(L('setSuccess'),$_SERVER['HTTP_REFERER']);
	}
	function _delete($thisContent,$deleteAttachement=1){
		$id=$thisContent->id;
		$channelID=$thisContent->channel_id;
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisChannel=$channelObj->getChannelByID($channelID);
		$rt=$this->article_db->delete(array('id'=>$id));
		if ($rt){
			if ($thisContent->geoid){
				$this->_clearContentCacheWithGeoid($thisChannel,$thisContent->geoid);
			}

			$articleObj=bpBase::loadAppClass('articleObj','article');
			$articleObj->clearContentsCache($thisContent->channel_id,'add',$thisChannel);
			//
			//$comment_db=bpBase::loadModel('content_comment_model');
			//$comment_db->delete(array('contentid'=>$id));//comments
			//
			delCache('c_content'.$id);
			if (!intval($thisContent->externallink)){
				if (!$isOtherArticleUseFile&&file_exists(ABS_PATH.$thisContent->link)&&!is_dir(ABS_PATH.$thisContent->link)){
					@unlink(ABS_PATH.$thisContent->link);
				}
			}
			//delete thumb
			//$this->_deleteThumb($id,$thisContent->thumb);
			//delete attachement
			if ($deleteAttachement){
				//deleteAttachement($thisContent->content);
			}
			//delete pages
			$contentCount=$thisContent->pagecount;
			for ($j=1;$j<$contentCount;$j++){
				$page=$j+1;
				$path=ABS_PATH.str_replace($id.'.',$id.'-'.$page.'.',$thisContent->link);
				if (file_exists($path)){
					unlink($path);
				}
			}
		}
		return $rt;
	}
	function _deleteContentInAllGroup($contentid){
		/*
		$contentid=intval($contentid);
		$contentgroup_content_db=bpBase::loadModel('contentgroup_content_model');
		$contents=$contentgroup_content_db->get_results('*','',array('contentid'=>$contentid));
		if (defined('CMS_CITY_ID')){
			$where=array('contentid'=>$contentid,'geoid'=>CMS_CITY_ID);
		}else {
			$where=array('contentid'=>$contentid);
		}
		$rt=$contentgroup_content_db->delete($where);
		if ($rt){
			if ($contents){
				foreach ($contents as $thisContent){
					if (defined('CMS_CITY_ID')){
						$geoid=CMS_CITY_ID;
					}else {
						$geoid=$contents[0]->geoid;
					}
					delCache('contentsOfGroup'.$contents[0]->groupid.'geoid'.$geoid);
				}
			}
		}
		*/
		return $rt;
	}
	function action_delete(){
		if (!$_POST['id']){
			showMessage('请选择要删除的内容',$_SERVER['HTTP_REFERER']);
			exit();
		}
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$thisContent=$articleObj->getContentByID($_POST['id'][0]);
		$channelID=$thisContent->channel_id;
		if (intval($channelID)&&$thisContent->site==1){
			$this->exitWithoutAccess('channel_content','delete',array('channelID'=>$channelID));
		}
		foreach ($_POST['id'] as $k=>$id){
			$theContent=$articleObj->getContentByID($id);
			if (strlen($theContent->autoid)){
				$autoids.=$theContent->autoid.',';
			}
			$rt=$this->_delete($theContent);
			$this->_deleteContentInAllGroup($id);
		}
		showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
	}
	function action_toTransferUrl(){
		if (!$_POST['id']){
			showMessage('请选择要转移的内容',$_SERVER['HTTP_REFERER']);
			exit();
		}
		$comma='';
		$idstr='';
		foreach ($_POST['id'] as $k=>$id){
			$idstr.=$comma.$id;
			$comma=',';
		}
		echo '<script>window.location.href=\'contentTransfer.php?site='.$_POST['siteid'].'&ids='.$idstr.'\';</script>';
	}
	function action_transfer(){
		if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST'){
			$articleObj=bpBase::loadAppClass('articleObj','article');
			$channelObj=bpBase::loadAppClass('channelObj','channel');
			$channels=$_POST['channels'];
			$ids=explode(',',$_GET['ids']);
			$ids=array_reverse($ids);
			if ($ids){
				foreach ($ids as $id){
					if ($channels){
						$thisContent=$this->article_db->get_one(array('id'=>$id));
						$thisContentInObj=$articleObj->getContentByID($id);
						foreach ($channels as $c){
							$this->exitWithoutAccess('channel_content','add',array('channelID'=>$c));//权限控制
							if (intval($id)>0){
								$thisChannel=$channelObj->getChannelByID($c);
								if ($_POST['transferType'][0]=='copy'){
									$thisContent['channel_id']=$c;
									if (!$thisContent['externallink']){
										$thisContent['link']='';
									}
									unset($thisContent['id']);
									$this->_add($thisContent,$thisChannel);
								}elseif ($_POST['transferType'][0]=='quote'){
									//$thisContent['channel_id']=$c;
									//$thisContent['link']='';
									//$this->_add($thisContent,$thisChannel);
									//$content->add($c,$thisContent->title,'',$thisContent->link,1,$thisContent->thumb,'','','','',$thisContent->time);
								}
								if ($_POST['transferType'][0]=='cut'){
									$thisContent['channel_id']=$c;
									if (!$thisContent['externallink']){
										$thisContent['link']='';
									}
									unset($thisContent['id']);
									$this->_add($thisContent,$thisChannel);
									$this->_delete($thisContentInObj,0);
								}
							}
						}
					}
				}
			}
			header('Location:index.php?m=article&c=m_article&a=articles&id='.$channels[0].'&site='.$_POST['site']);
		}
	}
	/**
	 * 检查标题是否重复
	 *
	 */
	function action_sameTitleCheck(){
		$contentConfig=loadConfig('cmsContent');
		$sameTitleDaysLimit=$contentConfig['sameTitleDaysLimit'];
		if (intval($sameTitleDaysLimit)){
			$sameTitleDaysLimitSeconds=24*3600*$sameTitleDaysLimit;
			$stopTime=SYS_TIME-$sameTitleDaysLimitSeconds;
			$contents=$this->article_db->select('`time`>'.$stopTime.' AND `title`=\''.$_GET['title'].'\'');
			if ($contents){
				echo 1;//有重复标题
			}else {
				echo 0;
			}
		}else {
			echo 0;
		}
	}
	/**
	 * 删除某地的文章
	 *
	 */
	function deleteContentInLocation(){
		$geoid=intval($_GET['geoid']);
		if ($geoid>0){
			if (getCache('deleteContentInLocation'.$geoid)){
				$articles=unserialize(getCache('deleteContentInLocation'.$geoid));
			}else {
				$articles=$this->article_db->get_results('*','',array('geoid'=>$geoid),'time DESC');
				setCache('deleteContentInLocation'.$geoid,serialize($articles));
			}
			$count=count($articles);
			$i=isset($_GET['i'])?intval($_GET['i']):0;
			$channelObj=bpBase::loadAppClass('channelObj','channel');
			if ($i<$count){
				$thisChannel=$channelObj->getChannelByID($articles[$i]->channel_id);
				if ($thisChannel->iscity){
					$rt=$this->_delete($articles[$i],0);
					$this->_deleteContentInAllGroup($articles[$i]->id);
					$i++;
					$rt=$this->_delete($articles[$i],0);
					$this->_deleteContentInAllGroup($articles[$i]->id);
				}
				$i++;
				showMessage($articles[$i]->geoid.':'.$articles[$i]->title.' '.$i.'/'.$count,'?m=article&c=m_article&a=deleteContentInLocation&geoid='.$geoid.'&i='.$i,1);
			}else {
				delCache('deleteContentInLocation'.$geoid);
				echo 'complete';
			}
		}
	}
	function search(){
		$parms=array('m'=>'article','c'=>'m_article','a'=>'search');
		$siteID=$_GET['siteid'];
		if (isset($_GET['siteid'])&&$_GET['siteid']!=$this->siteid){
			exit();
		}
		$user_db=bpBase::loadModel('user_model');
		$admins=$user_db->select(array('isadmin'=>1));
		$channel=bpBase::loadAppClass('channelObj','channel');
		$display = $channel->channelCreatePageTree($channel->tree(0,$siteID),0);
		//
		//$thisChannel=$channel->getChannelByID($_GET['id']);
		$pageSize=20;
		$start=isset($_GET['start'])?intval($_GET['start']):0;
		if (isset($_GET['startdate'])&&$_GET['startdate']!=''){
			$st=$_GET['startdate'];
			$stArr=explode('-',$st);
			$starttime=mktime(0,0,0,intval($stArr[1]),intval($stArr[2]),intval($stArr[0]));
			$parms['startdate']=$_GET['startdate'];
		}else {
			$starttime=0;
			$st='';
		}
		if (isset($_GET['enddate'])&&$_GET['enddate']!=''){
			$et=$_GET['enddate'];
			$parms['enddate']=$_GET['enddate'];
			$etArr=explode('-',$et);
			$endtime=mktime(23,59,59,intval($etArr[1]),intval($etArr[2]),intval($etArr[0]));
		}else {
			$endtime=0;
			$et='';
		}
		//
		$keyword=$_GET['keyword'];
		$parms['keyword']=$_GET['keyword'];
		$keyword=str_replace('\'','',$keyword);
		$keyword=str_replace('or','',$keyword);
		$keyword=str_replace('and','',$keyword);
		$keyword=str_replace('=','',$keyword);

		$page=isset($_GET['page'])?abs(intval($_GET['page'])):1;
		$articleObj=bpBase::loadAppClass('articleObj','article');
		//计算总数
		$totalSql=$articleObj->searchContentSql($_GET['channel'],$keyword,0,0,$starttime,$endtime,0,$_GET['uid'],$this->siteid);
		
		$total=$this->article_db->get_varBySql($totalSql,'COUNT(id)');
		//
		$contents=$articleObj->searchContents($_GET['channel'],$keyword,$page,$pageSize,$starttime,$endtime,0,$_GET['uid'],$this->siteid);
		$contents=objectsToArrByKey($contents);
		$parmStr=http_build_query($parms);
		$pages=pages($total,$page,$pageSize,'?'.$parmStr.'&');
		//
		include $this->showManageTpl('search');
	}
	//采集
	function collect(){
		if (isset($_GET['id'])){
			$id=intval($_GET['id']);
		}else {
			$id=0;
		}
		$ruleid=$id;
		$spider_rule_db=bpBase::loadModel('spider_rule_model');
		$spider_content_db=bpBase::loadModel('spider_content_model');
		$thisRule=$spider_rule_db->get_one(array('id'=>$id));
		//$ruleConfigs=unserialize($thisRule->configs);
		//$sourceUrls=$ruleConfigs['urls'];
		if (intval($_GET['step'])==2){
			if (file_exists(ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'collectContents'.$ruleid.'.txt')){
				if (!isset($_SESSION['collectArticleCount'])){
					$_SESSION['collectArticleCount']=0;
				}
				$contents=unserialize(file_get_contents(ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'collectContents'.$ruleid.'.txt'));
				if ($contents){
					$i=intval($_GET['i']);//i循环的是内容地址
					$contentsCount=count($contents);
					
					if ($i<$contentsCount){
						$contentid=$contents[$i]->id;
						//content
						$thisContent=$spider_content_db->get_row(array('id'=>$contentid));
						$content=unserialize($thisContent->content);
						//rule,get channelid
						
						$channelid=intval($thisRule['channelid']);
						//
						$content['channel_id']=$channelid;
						$content['time']=$content['addtime'];
						if (!$content['time']){
							$content['time']=SYS_TIME;
						}
						$channelObj=bpBase::loadAppClass('channelObj','channel');
						$thisChannel=$channelObj->getChannelByID($channelid);
						$sameContent=$this->article_db->get_one(array('channel_id'=>$channelid,'title'=>$content['title']));
						if (!$sameContent){//忽略相同标题内容
							//clear links and save image
							//autoid
							$autoidAndkeyword=$this->articleObj->getRalateAuto($content['title']);//根据标题获取相关车型
							$content['autoid']=$autoidAndkeyword['autoid'];
							$content['keywords']=$autoidAndkeyword['keyword'];
							//clear href
							if ($thisRule['clearhref']){
								$content['content']=clearHtmlTagA($content['content']);
								//

							}
							//$intro
							$intro=$content['intro'];
							if (!$intro){
								$txtContent=remove_html_tag($content['content']);
								$intro=mb_substr($txtContent,0,200,'gbk');
							}
							if (strlen($content['title'])){
								if(!$content['thumb']){
									$thumb=$this->_setFirstImageAsThumb($thisChannel,$content['content']);
								}else{
									$thumb=$this->_setFirstImageAsThumb($thisChannel,'<img src="'.$content['thumb'].'" />');
								}
                                //
								if (file_exists(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'cms.php')){
									require(ABS_PATH.'config'.DIRECTORY_SEPARATOR.'cms.php');
									if (defined('CONTENT_IMAGE_SEP_HANDLE')&&CONTENT_IMAGE_SEP_HANDLE){
										$content['content']=$this->articleObj->autoSaveRemoteImage($content['content']);
									}
								}
								if (!$content['time']){
									$content['time']=SYS_TIME;
								}
								//
								$row=array('channel_id'=>$channelid,'title'=>$content['title'],'subtitle'=>'','link'=>'','externallink'=>0,'thumb'=>$thumb,'content'=>$content['content'],'intro'=>$intro,'author'=>$content['author'],'source'=>$content['source'],'uid'=>0,'time'=>$content['time'],'last_update'=>$content['time'],'autoid'=>$content['autoid'],'keywords'=>$content['contentPageCount'],'cancomment'=>1,'titles'=>$content['pagetitle'],'site'=>1);
								if ($channelid!=1){
									$row['content']=$this->_addAutoLink($row['content']);
								}
								$row['pagecount']=$this->_calContentPageCount($row['content']);
								//
								$row['content']=$this->articleObj->autoSaveRemoteImage($row['content']);
								$siteObj=bpBase::loadAppClass('siteObj','site');
								$thisSite=$siteObj->getSiteByID(1);
								if (intval($thisSite->abspath)){
									$row['content']=str_replace('src="/upload','src="'.MAIN_URL_ROOT.'/upload',$row['content']);
								}
								//
								if (substr($row['keywords'],0,1)!=','){
									$row['keywords']=','.$row['keywords'];
								}
								
								$this->_add($row,$thisChannel,1);
								$_SESSION['collectArticleCount']++;
								$tip='';
								$spider_content_db->update(array('handle'=>1),array('id'=>$contentid));
							}else {
								$tip='，该文章没有采集到标题，不能入库';
							}
							
						}else {
							$spider_content_db->update(array('handle'=>1),array('id'=>$contentid));
						}
						
						//采集下一篇内容
						$nextI=$i+1;
						showMessage('正在入库：'.$nextI.'/'.$contentsCount.$tip.'...<a href="?m=article&c=m_article&a=collect&id='.$ruleid.'&step=2&i='.$i.'">刷新</a>&nbsp;&nbsp;<a href="?m=article&c=m_article&a=collect&id='.$ruleid.'&step=2&i='.$nextI.'">跳到下一个</a>','?m=article&c=m_article&a=collect&id='.$ruleid.'&step=2&i='.$nextI,1);
					}else{
						unlink(ABS_PATH.'/upload/collectContents'.$ruleid.'.txt');
						$collectCount=$_SESSION['collectArticleCount'];
						unset($_SESSION['collectArticleCount']);
						showMessage('采集结束，共采集入库'.$collectCount.'篇文章');
					}
				}else {
					showMessage('没有要入库的内容');
				}
			}else {
				$contents=$spider_content_db->get_results('id','','ruleid='.$ruleid.' AND title!=\'\' AND handle=0','id ASC');
				file_put_contents(ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'collectContents'.$ruleid.'.txt',serialize($contents));
				showMessage('正在入库','?m=article&c=m_article&a=collect&id='.$ruleid.'&step=2',1);
			}
		}
	}
	//自动补全html
	function closetags($html) {
		$html=str_replace('</div>','',$html);
		// 不需要补全的标签
		$arr_single_tags = array('meta', 'img', 'br', 'link', 'area');
		// 匹配开始标签
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		// 匹配关闭标签
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		// 计算关闭开启标签数量，如果相同就返回html数据
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		// 把排序数组，将最后一个开启的标签放在最前面
		$openedtags = array_reverse($openedtags);
		// 遍历开启标签数组
		for ($i = 0; $i < $len_opened; $i++) {
			// 如果需要补全的标签
			if (!in_array($openedtags[$i], $arr_single_tags)) {
				// 如果这个标签不在关闭的标签中
				if (!in_array($openedtags[$i], $closedtags)) {
					// 直接补全闭合标签
					$html .= '</' . $openedtags[$i] . '>';
				} else {
					unset($closedtags[array_search($openedtags[$i], $closedtags)]);
				}
			}
		}
		return $html;
	}
	//
	function handleRelateAutoPage(){
		$autoids=explode(',',$_GET['autoids']);
		/*因为只查找跟第一级车型、第三级车型相关的文章，所以只处理第一级车型和第三级车型即可*/
		$g1autoids=array();//第一级别车型id组成一个数组，用来判断相关车型一共有多少个不同的第一级别车型id
		$g3autoids=array();//第3级别车型id组成一个数组，用来判断相关车型一共有多少个不同的第一级别车型id
		//
		if ($autoids){
			$autodb=bpBase::loadModel('autoclassification_model');
			foreach ($autoids as $autoid){
				if (intval($autoid)){
					$thisAuto=$autodb->getCfByID($autoid);
					switch (intval($thisAuto->grade)){
						default:
							break;
						case 1:
							if (!in_array($autoid,$g1autoids)){
								array_push($g1autoids,$autoid);
							}
							$autoWhere=array('grade'=>4,'g1id'=>$thisAuto->id);
							break;
						case 2:
							if (!in_array($thisAuto->parentid,$g1autoids)){
								array_push($g1autoids,$thisAuto->parentid);
							}
							$autoWhere=array('grade'=>4,'g2id'=>$thisAuto->id);
						case 3:
							$g1autoid=intval($thisAuto->g1id);
							if (!in_array($g1autoid,$g1autoids)){
								array_push($g1autoids,$g1autoid);
							}
							//
							if (!in_array($autoid,$g3autoids)){
								array_push($g3autoids,$autoid);
							}
							$autoWhere=array('grade'=>4,'parentid'=>$thisAuto->id);
						case 4:
							$g1autoid=intval($thisAuto->g1id);
							if (!in_array($g1autoid,$g1autoids)){
								array_push($g1autoids,$g1autoid);
							}
							//
							if (!in_array($thisAuto->parentid,$g3autoids)){
								array_push($g3autoids,$thisAuto->parentid);
							}
							$autoWhere=array('id'=>$thisAuto->id);
							break;
					}
				}
			}
		}
		/*静态页列表*/
		$pages=array();
		$caches=array();
		$toHtml=loadConfig('site','tohtml');
		$html_refresh_db=bpBase::loadModel('htmlpage_needrefresh_model');
		if ($g1autoids){
			foreach ($g1autoids as $g1autoid){
				if ($toHtml){
					$html_refresh_db->insert(array('pagetype'=>'brandIndex','parmid'=>$g1autoid,'time'=>SYS_TIME));//品牌主页
				}
				//array_push($caches,cache_articlesOfAuto(array('autoid'=>$g1autoid)));
			}
		}
		if ($g3autoids){
			foreach ($g3autoids as $autoid){
				if ($toHtml){
					$html_refresh_db->insert(array('pagetype'=>'brandIndex','parmid'=>$g1autoid,'time'=>SYS_TIME));//品牌主页
					$html_refresh_db->insert(array('pagetype'=>'serieIndex','parmid'=>$autoid,'time'=>SYS_TIME));//3级主页
				}
				//所有四级主页
				if ($toHtml){
					$childrenCfs=$autodb->get_results('*','',$autoWhere);
					if ($childrenCfs){
						foreach ($childrenCfs as $ccf){
							$html_refresh_db->insert(array('pagetype'=>'autoIndex','parmid'=>$ccf->id,'time'=>SYS_TIME));
						}
					}
				}
				//array_push($caches,cache_articlesOfAuto(array('autoid'=>$autoid)));
			}
		}
		//生成
		$tpl=bpBase::loadAppClass('template','template');
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisChannel=$channelObj->getChannelByID($_GET['channelid']);
		$tpl->createContentPageR($_GET['contentid'],$thisChannel);
		/*下一个页面*/
		$nextUrl='?m=article&c=m_article&a=articles&id='.$_GET['channelid'].'&site='.$_GET['site'];
		showMessage('处理完成',$nextUrl,500);
		
	}
}
?>