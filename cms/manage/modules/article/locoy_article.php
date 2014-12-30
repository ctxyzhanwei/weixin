<?php
class locoy_article {
	public $content_db;
	public $article_db;
	public $articleObj;
	function __construct() {
		$this->article_db = bpBase::loadModel('article_model');
		$this->content_db = bpBase::loadModel('content_model');
		$this->articleObj=bpBase::loadAppClass('articleObj','article');
		$cmsConfig=loadConfig('cmsContent');
		if (!$cmsConfig['openGather']){
			exit('gather function is closed');
		}
	}
	function _handleRealteAutoArticle($contentid,$title,$thisChannel,$autoids,$returnKeyword=0,$link='',$site=1,$geoid=0,$time=0){
		if (!$thisChannel->ex){
		$contentid=intval($contentid);
		$channelid=$thisChannel->id;
		$site=intval($site);
		$geoid=intval($geoid);
		$time=intval($time);
		if (strlen($autoids)){
			//判断属于哪个类别
			$catName=$thisChannel->autotype;
			//车型
			$firstAutoGrade=0;
			$firstAutoName='';//把第一个车型数据写入文章记录中
			$keyword=',';
			$autoidArr=explode(',',$autoids);
			//查出所有相关车数据
			$auto_db=bpBase::loadModel('autoclassification_model');
			$where=to_sqls($autoidArr,'','id');
			$autos=$auto_db->select($where);
			$autos=arrToArrByKey($autos);
			if ($autoidArr){
				$i=0;
				foreach ($autoidArr as $autoid){
					if (intval($autoid)){
						$thisAuto=$autos[$autoid];
						$keyword.=$thisAuto['name'].',';
						switch (intval($thisAuto['grade'])){
							case 3:
								$row=array('autoid'=>$autoid,'contentid'=>$contentid,'cat'=>$catName,'minprice'=>$thisAuto['minprice'],'maxprice'=>$thisAuto['maxprice'],'autotype'=>$thisAuto['type'],'name'=>$thisAuto['name'],'grade'=>$thisAuto['grade'],'g3autoid'=>$thisAuto['id'],'g3name'=>$thisAuto['name'],'g3grade'=>$thisAuto['g3grade'],'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								$row=array('autoid'=>$thisAuto['g2id'],'contentid'=>$contentid,'cat'=>$catName,'name'=>'','grade'=>2,'g3autoid'=>$thisAuto['id'],'g3name'=>$thisAuto['name'],'g3grade'=>$thisAuto['g3grade'],'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								$row=array('autoid'=>$thisAuto['g1id'],'contentid'=>$contentid,'cat'=>$catName,'name'=>'','grade'=>1,'g3autoid'=>$thisAuto['id'],'g3name'=>$thisAuto['name'],'g3grade'=>$thisAuto['g3grade'],'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								break;
							case 2:
								$row=array('autoid'=>$thisAuto['id'],'contentid'=>$contentid,'cat'=>$catName,'name'=>$thisAuto['name'],'grade'=>2,'g3autoid'=>0,'g3name'=>'','g3grade'=>0,'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								$row=array('autoid'=>$thisAuto['g1id'],'contentid'=>$contentid,'cat'=>$catName,'name'=>'','grade'=>1,'g3autoid'=>0,'g3name'=>'','g3grade'=>0,'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								break;
							case 1:
								$row=array('autoid'=>$thisAuto['id'],'contentid'=>$contentid,'cat'=>$catName,'name'=>$thisAuto['name'],'grade'=>1,'g3autoid'=>0,'g3name'=>'','g3grade'=>0,'title'=>$title,'link'=>$link,'site'=>$site,'geoid'=>$geoid,'time'=>$time);
								$this->content_db->insert($row);
								break;
						}
						if ($i==0){
							$firstAutoGrade=intval($thisAuto['grade']);
							$firstAutoName=$thisAuto['name'];
							$firstAutoType=intval($thisAuto['type']);
						}
						$i++;
					}
				}
			}
		}
		//caches
		if ($catName=='comment'||$catName=='market'){
			$cache_db=bpBase::loadModel('cache_model');
			if ($catName=='comment'){
				$cache_db->add('','pingceIndex','','permanentCache',0);
				$cache_db->add('','pingceList','','permanentCache',0);
			}
			if ($catName=='market'){
				$cache_db->add('','hangqingIndex','','permanentCache',0);
				$cache_db->add('','hangqingList','','permanentCache',0);
			}
		}
		//
		$updateRow=array('autoname'=>$firstAutoName,'autograde'=>$firstAutoGrade,'autotype'=>$firstAutoType);
		if ($returnKeyword){
			$updateRow['keywords']=$keyword;
			$this->article_db->update($updateRow,array('id'=>$contentid));
			return $keyword;
		}else {
			$this->article_db->update($updateRow,array('id'=>$contentid));
		}
		}
	}
	function _isAutoContentExist($autoid,$contentid){
		return $this->content_db->count(array('contentid'=>$contentid,'autoid'=>$autoid));
	}
	function _deleteRealteAutoArticle($contentid){
		$this->content_db->delete(array('contentid'=>$contentid));
	}
	function _setFirstImageAsThumb($thisChannel,$contentStr,$imgNo=1){
		$thumbWidht=$thisChannel->thumbwidth;
		$thmbHeight=$thisChannel->thumbheight;
		if (!$thumbWidht||!$thmbHeight){
			return '';
		}
		//get image url
		$contentStr=stripslashes($contentStr);
		
		@preg_match_all('#src="((((?!").)+).(jpg))"#i',$contentStr,$img_array);
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
						if(file_exists($tempImgPath)&&$this->pictype($tempImgPath)=='jpeg'){
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
			$location=MAIN_URL_ROOT.$url;
			return $location;
		}else {
			return '';
		}
		//
	}
	function pictype ( $file )
	{
		$header = file_get_contents ( $file , 0 , NULL , 0 , 5 );
	
		//echo bin2hex($header);
		if ( $header { 0 }. $header { 1 }== "\x89\x50" )
		{
			return 'png' ;
		}
		else if( $header { 0 }. $header { 1 } == "\xff\xd8" )
		{
			return 'jpeg' ;
		}
		else if( $header { 0 }. $header { 1 }. $header { 2 } == "\x47\x49\x46" )
		{
				
			if( $header { 4 } == "\x37" )
				return 'gif87' ;
			else if( $header { 4 } == "\x39" )
				return 'gif89' ;
		}
	
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
			$auto_db=bpBase::loadModel('autoclassification_model');
			$autos=$auto_db->select('`grade`=3 AND `status`<3');
			if ($autos){
				foreach ($autos as $a){
					array_push($linkDatas,array(0=>$a['name'],1=>CAR_URL_ROOT.'/'.$a['id'],2=>$a['name'],3=>'_blank'));
				}
			}
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
	function _add($row,$thisChannel,$createHtml=0){
		if (get_magic_quotes_gpc()){
			$row['content']=mysql_real_escape_string(stripslashes($row['content']));
		}else {
			$row['content']=mysql_real_escape_string($row['content']);
		}
		//$row['content']=$this->closetags($row['content']);
		$rt=$this->article_db->insert($row,1);
		$insertID=$rt;
		if ($rt){
			if ($row['geoid']){
				$this->_clearContentCacheWithGeoid($thisChannel,$row['geoid']);
			}
			$this->article_db->update(array('taxis'=>$insertID),array('id'=>$insertID));//更新taxis
			$this->_handleRealteAutoArticle($insertID,$row['title'],$thisChannel,$row['autoid'],0,$row['link'],$row['site'],$row['geoid'],$row['time']);
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
		$titles=str_replace("'","\'",$titles);
		$row=array('channel_id'=>$thisChannel->id,'title'=>$title,'subtitle'=>$_POST['subtitle'],'link'=>$_POST['link'],'externallink'=>$_POST['externallink'][0],'thumb'=>$thumb,'content'=>$contentStr,'intro'=>$intro,'author'=>$_POST['author'],'source'=>$_POST['source'],'uid'=>$_SESSION['cmsuid'],'time'=>$time,'last_update'=>$time,'autoid'=>$_POST['autoid'],'keywords'=>$_POST['keywords'],'cancomment'=>$canComment,'titles'=>$titles,'geoid'=>$_POST['geo_id'],'atype'=>$thisChannel->autotype,'site'=>0,'pagecount'=>0);
		//$channelID,$title,$subtitle,$link,$externallink,$thumb,$content,$intro,$author,$source,$time,$uid=0,$keywords='',$autoid='',$pagecount=0,$canComment=1,$titles='',$geoid=0
		//locy采集处理 start
		if (isset($_POST['locoy'])){
			$row['titles']=stripslashes($_POST['titles']);
			$row['titles']=str_replace('<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>','|',$row['titles']);
			$row['titles']=str_replace("'","\'",$row['titles']);
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
			$row['content']=$this->_addAutoLink($row['content']);
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
		if (!$sepImageHandle){
			$row['content']=$article_obj->autoSaveRemoteImage($row['content']);
		}
		$siteObj=bpBase::loadAppClass('siteObj','site');
		$thisSite=$siteObj->getSiteByID($thisChannel->site);
		if (strpos($_SERVER['HTTP_HOST'],'ahauto')>0||intval($thisSite->abspath)){
			$row['content']=str_replace('src="/upload','src="'.MAIN_URL_ROOT.'/upload',$row['content']);
		}
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
			//专题内容自动生成专题首页
			if ($row['site']>99){
				$tpl->createIndexPage($row['site']);
			}
			//
			if ($sepImageHandle){
				echo '<script>window.location.href=\'/'.CMS_DIR.'/saveRemoteImage.php?success=1&id='.$rt.'\';</script>';
			}else {
				echo '<script>window.location.href=\'/'.CMS_DIR.'/cachesAction.php?success=1&actionType=content_add&autoids='.$row['autoid'].'&contentid='.$rt.'&channelid='.$thisChannel->id.'&site='.$row['site'].'\';</script>';
			}
		}
		
	}
	function _access_ContentUpdate($thisChannel){
		if (isah()){
			return 1;
		}
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
			$this->_deleteRealteAutoArticle($id);
			$articleObj=bpBase::loadAppClass('articleObj','article');
			$articleObj->clearContentsCache($thisContent->channel_id,'add',$thisChannel);
			//
			$comment_db=bpBase::loadModel('content_comment_model');
			$comment_db->delete(array('contentid'=>$id));//comments
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
		return $rt;
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