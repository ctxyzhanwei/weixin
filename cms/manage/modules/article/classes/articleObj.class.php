<?php
class articleObj {
	public $cats;
	function __construct(){
		$this->article_db = bpBase::loadModel('article_model');
		$this->cats=array('video','news','guide','comment','market');
	}
	/**
 * 获取文章里面第一个车型的主页地址
 *
 * @param unknown_type $autoids
 * @param unknown_type $grade
 * @return unknown
 */
	public function getAutoLinkInArticle($autoids,$grade){
		//auto info
		$autoids=explode(',',$autoids);
		$firstAutoid=0;
		if ($autoids){
			foreach ($autoids as $autoid){
				if (intval($autoid)){
					$firstAutoid=$autoid;
					break;
				}
			}
			if (intval($grade)==1){
				if (URL_REWRITE){
					return CAR_URL_ROOT.'/brand-'.$firstAutoid.'.html';
				}else {
					return CAR_URL_ROOT.'/brand.php?id='.$firstAutoid;
				}
			}else {
				if (URL_REWRITE){
					return CAR_URL_ROOT.'/'.$firstAutoid;
				}else {
					return CAR_URL_ROOT.'/g3auto.php?id='.$firstAutoid;
				}
			}
		}else {
			return '';
		}
	}
	/**
	 * 根据内容页的相关车型获取每页的车型信息
	 *
	 * @param unknown_type $autoids
	 */
	function getAutoInfoOfArticle($autoids,$page=1){
		$autoids=explode(',',$autoids);
		$ids=array();
		if ($autoids){
			$i=0;
			foreach ($autoids as $autoid){
				if (intval($autoid)){
					array_push($ids,$autoid);
				}
			}
		}
		$page=$page-1;
		if ($autoids[$page]){
			$autoclassification_db=bpBase::loadModel('autoclassification_model');
			//$autoObj=bpBase::loadAppClass('autoObj','auto');
			//
			$autoid=$autoids[$page];
			$auto=$autoclassification_db->getCfByID($autoid);
			return $auto;
		}else {
			return null;
		}
	}
	function getAutoContentsCountByChannelID($channelID){
		$channelID=intval($channelID);
		$cacheName='autoContentsCountOfChannel'.$channelID;
		$crt=getCache($cacheName);
		if ($crt){
			return unserialize($crt);
		}else {
			$channelObj=bpBase::loadAppClass('channelObj','channel');
			//子栏目
			$channels=$channelObj->getChannelsByParentID($channelID);
			$channelSql='channel_id='.$channelID;
			if ($channels){
				foreach ($channels as $c){
					$channelSql.=' OR channel_id='.$c->id;
				}
			}
			//
			$count=$this->article_db->count('('.$channelSql.') AND time<'.SYS_TIME);
			setZendCache(serialize($count),$cacheName);
			return intval($count);
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
	public function setOtherThumb($channelObj,$dstFile,$folder,$fileName,$fileType='jpg'){
		bpBase::loadSysClass('image');
		if (intval($channelObj->thumb2width)&&intval($channelObj->thumb2height)){
			image::zfResize($dstFile,$folder.$fileName.'_small.'.$fileType,$channelObj->thumb2width,$channelObj->thumb2height,1|4,2);
		}
		if (intval($channelObj->thumb3width)&&intval($channelObj->thumb3height)){
			image::zfResize($dstFile,$folder.$fileName.'_middle.'.$fileType,$channelObj->thumb3width,$channelObj->thumb3height,1|4,2);
		}
		if (intval($channelObj->thumb4width)&&intval($channelObj->thumb4height)){
			image::zfResize($dstFile,$folder.$fileName.'_big.'.$fileType,$channelObj->thumb4width,$channelObj->thumb4height,1|4,2);
		}
	}
	public function autoSaveRemoteImage($str,$baseURI=''){
		$str=stripslashes($str);
		$watermark=bpBase::loadSysCLass('watermark');
		$img_array = array();
		//$str = stripslashes($str);
		if (get_magic_quotes_gpc()){
			$str = stripslashes($str);
		}
		preg_match_all('#src="(http://(((?!").)+).(jpg|gif|bmp|png))"#i',$str,$img_array);
		$img_array_urls=array_unique($img_array[1]);
		$dstFolder=ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'images';
		@chmod($dstFolder,0777);
		if ($baseURI){
			$img_array_urls=$this->_expandlinks($img_array_urls,$baseURI);
			if ($img_array_urls){
				exit();
			}
		}
		if ($img_array_urls){
			$i=0;
			$time=SYS_TIME;
			foreach ($img_array_urls as $k=>$v){
				if (!strpos($v,$_SERVER['HTTP_HOST'])){//不保存本站的
				    
					$filenames=explode('.',$v);
					$filenamesCount=count($filenames);
					//
					$year=date('Y',$time);
					$month=date('m',$time);
					$pathInfo=upFileFolders($time);
					$dstFolder=$pathInfo['path'];

					$rand=randStr(4);
					$filePath=$dstFolder.$time.$rand.'.'.$filenames[$filenamesCount-1];
					//
					@httpCopy($v,$filePath,5);
					//自动缩放
					$imgInfo = @getimagesize($filePath);
					$maxPicWidth=intval(loadConfig('cmsContent','maxPicWidth'));
					$maxPicWidth=$maxPicWidth<1?500:$maxPicWidth;
					if ($imgInfo[0]>$maxPicWidth){
						$newWidth=$maxPicWidth;
						$newHeight=$imgInfo[1]*$newWidth/$imgInfo[0];
						bpBase::loadSysClass('image');
						image::zfResize($filePath,$filePath,$newWidth,$newHeight,1,2,0,0,1);
					}
					//
					if (file_exists($filePath)){
						$watermark->wm($filePath);
						$str=str_replace($v,'http://'.$_SERVER['HTTP_HOST'].CMS_DIR_PATH.$pathInfo['url'].$time.$rand.'.'.$filenames[$filenamesCount-1],$str);
					}
				}
				$i++;
			}
		}
		return $str;
	}
	public function getContentByID($id){
		$id=intval($id);
		$crt=getCache('c_content'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			$content=$this->article_db->get_row(array('id'=>$id));
			$stag=bpBase::loadAppClass('stag','template');
			$content->content=$stag->handleStag($content->content);
			setZendCache(serialize($content),'c_content'.$id);
			return $content;
		}
	}
	public function getSelectContentsSql($channelID,$getCount=0,$orderBy='taxis',$start=0,$scope='self',$onlyImg=null,$where='',$getTotalCount=0,$site=0){
		$useWhere=strlen($where)?1:0;
		/*select what?*/
		$getCount=intval($getCount);
		if (!$getTotalCount){
			if (!$useWhere){
				$selectWhat='C.id,C.site,C.link,C.externallink,C.thumb,C.title,C.subtitle,C.intro,C.source,C.author,C.time,C.keywords,C.pagecount,C.viewcount,C.taxis,C.autoname,C.autograde,C.autoid,C.cancomment';
			}else {
				$selectWhat='C.*,CAV.value';
			}
		}else {
			$selectWhat='COUNT(C.id) AS count';
		}
		/*select from?*/
		$article_db=M('article');
		if (!$useWhere){
			$selectFrom=$article_db->table_name;
		}else {
			//$selectFrom=TABLE_PREFIX.'article C,'.TABLE_PREFIX.'content_attributevalue CAV';
		}
		/*select condition?*/
		$channelID=intval($channelID);
		if ($channelID){
			//scope
			switch ($scope){
				default:
				case 'self':
					$selectCondition='C.channel_id='.$channelID;
					break;
				case 'children':
					$channel=bpBase::loadAppClass('channelObj','channel',1);
					$descents=$channel->getChannelsByParentID($channelID);
					$cidStr=$channelID;
					if ($descents){
						$comma=',';
						foreach ($descents as $item){
							if ($item->id){
								$cidStr.=$comma.$item->id;
							}
						}
						$selectCondition='channel_id IN ('.$cidStr.')';
					}else {
						$selectCondition='C.channel_id='.$channelID;
					}
					break;
			}
		}else {
			$selectCondition='C.channel_id!=0';
		}
		//is image
		if ($onlyImg=='null'||is_null($onlyImg)){
		}else {
			if ($onlyImg==0){
				$selectCondition.=' AND (C.thumb IS NULL OR C.thumb=\'\')';
			}else {
				$selectCondition.=' AND C.thumb IS NOT NULL AND C.thumb!=\'\'';
			}
		}
		//personal attribute
		if ($useWhere){
			$selectCondition.=' AND C.id=CAV.contentid AND ('.$where.')';
		}
		//site
		$site=intval($site);
		if ($site){
			$selectCondition.=' AND C.site='.$site;
		}
		/*limit*/
		$limitSql='';
		if (!$getTotalCount){
			$start=intval($start);
			if ($getCount!=0||$start!=0){
				if ($getCount!=0){
					$limitSql=' LIMIT '.$start.','.$getCount;
				}else {
					$limitSql=' LIMIT '.$start.',100';
				}
			}else {
				$limitSql='';
			}
		}
		/*order*/
		$orderSql='';
		if (!$getTotalCount){
			$orderSql=' ORDER BY '.$orderBy.' DESC';
		}
		/*sql*/
		$sql='SELECT '.$selectWhat.' FROM '.$selectFrom.' WHERE '.$selectCondition.$orderSql.$limitSql;
		return $sql;
	}
	public function getContentsByChannelID($channelID,$getCount=0,$orderBy='taxis',$start=0,$scope='self',$onlyImg='null',$where='',$site=0){
		$sql=$this->getSelectContentsSql($channelID,$getCount,$orderBy,$start,$scope,$onlyImg,$where,0,$site);
		$contents=$this->article_db->get_resultsBySql($sql);
		return $contents;
	}
	public function getContentsCountByChannelID($channelID,$orderBy='taxis',$scope='self',$onlyImg=null,$where=''){
		$sql=$this->getSelectContentsSql($channelID,0,$orderBy,0,$scope,$onlyImg,$where,1);
		$rt=$this->article_db->selectBySql($sql);
		return $rt[0]['count'];
	}
	public function getSelectContentsSimpleSql($channelID,$getCount=0,$orderBy='taxis',$start=0,$scope='self',$onlyImg=null,$exceptChannelids=''){
		/*select what?*/
		$start=intval($start);
		$getCount=intval($getCount);
		/*select condition?*/
		$channelID=intval($channelID);
		$selectCondition='';
		if ($channelID){
			//scope
			switch ($scope){
				default:
				case 'self':
					$selectCondition='channel_id='.$channelID;
					break;
				case 'children':
					$channel=bpBase::loadAppClass('channelObj','channel',1);
					$descents=$channel->getChannelsByParentID($channelID);
					$cidStr=$channelID;
					if ($descents){
						$comma=',';
						foreach ($descents as $item){
							if ($item->id){
								$cidStr.=$comma.$item->id;
							}
						}
						$selectCondition='channel_id IN ('.$cidStr.')';
					}else {
						$selectCondition='channel_id='.$channelID;
					}
					break;
			}
		}else {
			$selectCondition='channel_id!=0';
		}
		if (!in_array($orderBy,array('taxis','time','viewcount'))){
			$orderBy='time';
		}
		//is image
		if (is_null($onlyImg)){
		}else {
			if ($onlyImg==0){
				$selectCondition.=' AND (thumb IS NULL OR thumb=\'\')';
			}else {
				$selectCondition.=' AND thumb IS NOT NULL AND thumb!=\'\'';
			}
		}
		$exceptChannelSql='';
		if ($exceptChannelids&&is_array($exceptChannelids)){
			$exceptChannelSql=' AND channel_id NOT IN (';
			$comma='';
			foreach ($exceptChannelids as $exid){
				$exceptChannelSql.=$comma.$exid;
				$comma=',';
			}
			$exceptChannelSql.=')';
		}
		$article_db=M('article');
		if ($getCount){
			$sql='SELECT id,site,link,externallink,thumb,title,subtitle,intro,source,author,time,keywords,pagecount,content,autoname,autograde,autoid,viewcount FROM '.$article_db->table_name.' WHERE '.$selectCondition.$exceptChannelSql.' AND time<'.SYS_TIME.' ORDER BY '.$orderBy.' DESC LIMIT '.$start.','.$getCount;
		}else {
			$sql='SELECT id,site,link,externallink,thumb,title,subtitle,intro,source,author,time,keywords,pagecount,content,autoname,autograde,autoid,viewcount FROM '.$article_db->table_name.' WHERE '.$selectCondition.$exceptChannelSql.' AND time<'.SYS_TIME.' ORDER BY '.$orderBy.' DESC';
		}
		return $sql;
	}
	/**
	 * 列表页为动态页面时，需要缓存数据，此function用于清除缓存，仅支持二级栏目
	 *
	 * @param unknown_type $channelid
	 * @param unknown_type $oper
	 */
	public function clearContentsCache($channelid,$oper='add',$thisChannel=''){
		$channelid=intval($channelid);
		$channel=bpBase::loadAppClass('channelObj','channel',1);
		if ($thisChannel==''){
			$thisChannel=$channel->getChannelByID($channelid);
		}
		//清除本栏目的缓存
		$contentsCount=$this->getListContentsCountByChannelID($channelid);
		if ($oper=='add'){
			$contentsCount++;
		}
		$thisChannelPageSize=intval($thisChannel->pagesize);
		$y=$contentsCount%$thisChannelPageSize;
		$pageCount=$y==0?$y:$y+1;
		for ($i=1;$i<$y+1;$i++){
			delCache('autoContentsOfChannel'.$channelid.'p'.$i);
		}
		//清除父栏目的缓存
		$parentChannel=$channel->getChannelByID($thisChannel->parentid);
		if ($parentChannel){
			$contentsCount=$this->getListContentsCountByChannelID($thisChannel->parentid);
			if ($oper=='add'){
				$contentsCount++;
			}
			$thisChannelPageSize=intval($parentChannel->pagesize);
			$y=$contentsCount%$thisChannelPageSize;
			$pageCount=$y==0?$y:$y+1;
			for ($i=1;$i<$y+1;$i++){
				delCache('autoContentsOfChannel'.$thisChannel->parentid.'p'.$i);
			}
		}
		//如果是增加或删除操作，需要把总数缓存清理掉
		if ($oper=='add'||$oper=='delete'){
			delCache('autoContentsCountOfChannel'.$channelid);
			delCache('autoContentsCountOfChannel'.$thisChannel->parentid);
		}
	}
	/**
	 * 仅用于动态栏目列表页的内容总数统计
	 *
	 * @param unknown_type $channelID
	 * @return unknown
	 */
	function getListContentsCountByChannelID($channelID){
		$channelID=intval($channelID);
		$crt=getCache('autoContentsCountOfChannel'.$channelID);
		if ($crt){
			return unserialize($crt);
		}else {
			$channel=bpBase::loadAppClass('channelObj','channel',1);
			//子栏目
			$channels=$channel->getChannelsByParentID($channelID);
			$channelIDArr=array($channelID);
			if ($channels){
				foreach ($channels as $c){
					array_push($channelIDArr,$c->id);
				}
			}
			//
			$where = to_sqls($channelIDArr, '', 'channel_id');
			$count=$this->article_db->get_var($where,'COUNT(id)','','');
			setZendCache(serialize($count),'autoContentsCountOfChannel'.$channelID);
			return intval($count);
		}
	}
	function contentPagination($content){
		$c=str_replace(array("\r\n","\r","\n"),'',$content);
		$contents1=explode('<div style="page-break-after:',$c);
		$contents=array();
		if (count($contents1)>1){
			$i=0;
			foreach ($contents1 as $c1){
				if ($i>0){
				$firstDivEndTagPos=strpos($c1,'</div>');
				$realC=substr($c1,$firstDivEndTagPos+6);
				array_push($contents,$realC);
				}else {
					array_push($contents,$c1);
				}
				$i++;
			}
		}else {
			array_push($contents,$content);
		}
		//$sepStr='<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>';
		//@$c=preg_replace('#<div style="page-break-after:(|[a-z =<>\/\"\:\;\&]+)>(((?!div).)+|)<\/div>#i',$sepStr,$c);
		//$contents=explode($sepStr,$c);
		return $contents;
	}
	function updateLink($id,$link){
		$thisContent=$this->getContentByID($id);
		if (!intval($thisContent->externallink)){
			$id=intval($id);
			$rt=$this->article_db->update(array('link'=>$link),array('id'=>$id));
			if ($rt){
				$content_db = bpBase::loadModel('content_model');
				//修改接地址
				//$content_db->update(array('link'=>$link),array('contentid'=>$id));
				delCache('content'.$id);
			}
			return $rt;
		}else {
			return 0;
		}
	}
	function updateLastCreateTime($id){
		$id=intval($id);
		$rt=$this->article_db->update(array('lastcreate'=>SYS_TIME),array('id'=>$id));
		if ($rt){
			delCache('content'.$id);
		}
		return $rt;
	}
	/**
	 * 获取某个栏目下的车型，每个文章只能关联一个车型，比如添加在cms里面的车型排行榜
	 *
	 * @param unknown_type $channelID
	 * @return unknown
	 */
	function getAutoContentsByChannelID($channelID,$page=1){
		$channelID=intval($channelID);
		$page=intval($page);
		$crt=getCache('autoContentsOfChannel'.$channelID.'p'.$page);
		if ($crt){
			return unserialize($crt);
		}else {
			$channelObj=bpBase::loadAppClass('channelObj','channel',1);
			//获取多少条
			$thisChannel=$channelObj->getChannelByID($channelID);
			$pageSize=intval($thisChannel->pagesize)?intval($thisChannel->pagesize):30;
			if ($page){
				$start=($page-1)*$pageSize;
			}else {
				$start=0;
			}
			//子栏目
			$channels=$channelObj->getChannelsByParentID($channelID);
			$cidArr=array($channelID);
			if ($channels){
				foreach ($channels as $c){
					array_push($cidArr,$c->id);
				}
			}
			$where = to_sqls($cidArr, '', 'channel_id').' AND time<'.SYS_TIME;
			$contents=$this->article_db->get_results('*','',$where,'taxis DESC',$start.','.$pageSize);
			//
			$contents=$this->convertAutoContents($contents);
			setZendCache(serialize($contents),'autoContentsOfChannel'.$channelID.'p'.$page);
			return $contents;
		}
	}
	/**
	 * 把普通内容转换为有汽车属性的内容
	 *
	 * @param object $contents
	 */
	function convertAutoContents($contents){
		$autoIDArr=array();
		if ($contents){
			foreach ($contents as $c){
				if (intval($c->autoid)){
					array_push($autoIDArr,intval($c->autoid));
				}
			}
		}
		//
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
		$where = to_sqls($autoIDArr, '', 'id');
		$autos=$autoclassification_db->get_results('*','',$where);
		$autos=objectsToArrByKey($autos,'id');
		if ($contents){
			$i=0;
			foreach ($contents as $c){
				if (intval($c->autoid)){
					$contents[$i]->auto=$autos[intval($c->autoid)];
				}
				$i++;
			}
		}
		return $contents;
	}
	function getArticleCat($id){
		$id=intval($id);
		$crt=getCache('articleCat'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			//$cat=$this->article_db->get_varBySql('SELECT `cat` FROM '.AUTO_TABLE_PREFIX.'content WHERE `contentid`='.$id,'cat');
			setZendCache(serialize($cat),'articleCat'.$id);
			return $cat;
		}
	}
	/**
	 * 某文章的相关文章
	 *
	 * @param unknown_type $contentID
	 * @param unknown_type $getCount
	 * @return unknown
	 */
	
	function relateContentsOfSpecifiedContent($contentID,$start=0,$getCount=0,$thisContent){
		$contentID=intval($contentID);
		$start=intval($start);
		$getCount=intval($getCount);
		$sql=$this->relateContentsOfSpecifiedContentSql($contentID,$start,$getCount,$thisContent);
		$contents=$this->article_db->selectBySql($sql,ARRAY_A);
		return $contents;
	}
	function viewCountAdd($id,$count=1){
		$id=intval($id);
		$count=intval($count);
		$rt=$this->article_db->update(array('viewcount'=>'+='.$count),array('id'=>$id));
		//
		$now=SYS_TIME;
		$year=date('Y',$now);
		$week=date('W',$now);
		$content_viewlog_db=bpBase::loadModel('content_viewlog_model');
		$updateRt=$content_viewlog_db->update(array('viewcount'=>'+=1'),array('contentid'=>$id,'year'=>$year,'week'=>$week));
		if (!$updateRt){
			//insert
			$content_viewlog_db->insert(array('contentid'=>$id,'year'=>$year,'week'=>$week));
		}
		return $rt;
	}
	function viewRanks($channelID,$count=10){
		$channelID=intval($channelID);
		$count=intval($count);
		$now=SYS_TIME;
		$year=date('Y',$now);
		$week=date('W',$now);
		$day=date('N',$now);
		global $permanentCache;
		//每天更新一次缓存
		$crt=getCache('contentsRanks'.$channelID.'year'.$year.'week'.$week.'day');
		if ($crt){
			return unserialize($crt);
		}else {
			$channelObj=bpBase::loadAppClass('channelObj','channel');
			//子栏目
			$channels=$channelObj->getChannelsByParentID($channelID);
			$channelSql='(A.channel_id='.$channelID;
			if ($channels){
				foreach ($channels as $c){
					$channelSql.=' OR A.channel_id='.$c->id;
				}
			}
			$channelSql.=')';
			//计算上一天的数据
			if ($day>1){
				$day=$day-1;
			}else {
				$day=7;
				if ($week!=1){
					$week=$week-1;
				}else {
					$week=52;
					$year=$year-1;
				}
			}
			$sql='SELECT A.id,A.channel_id,A.site,A.title,A.subtitle,A.link,A.externallink,A.thumb,A.intro,A.author,A.source,A.keywords,A.time FROM '.TABLE_PREFIX.'article A,'.TABLE_PREFIX.'content_viewlog V WHERE '.$channelSql.' AND V.contentid=A.id AND V.year='.$year.' AND V.week='.$week.' ORDER BY V.viewcount DESC LIMIT 0,'.$count;
			$rts=$this->article_db->get_resultsBySql($sql);
			if (!$rts){
				$rts=$this->article_db->get_results('id,channel_id,site,title,subtitle,link,externallink,thumb,intro,author,source,keywords,time',TABLE_PREFIX.'article A',$channelSql,'time DESC','0,'.$count);
			}
			setZendCache(serialize($rts),'contentsRanks'.$channelID.'year'.$year.'week'.$week.'day');
			return $rts;
		}
	}
	/**
	 * 阅读排行
	 */
	function viewRanksByCat($cat,$count=10,$orderBy='viewcount',$useCache=1){
		$channelID=intval($channelID);
		$count=intval($count);
		$now=SYS_TIME;
		$year=date('Y',$now);
		$week=date('W',$now);
		$day=date('N',$now);
		$cat=$this->getCat($cat);
		//每天更新一次缓存
		$orderBy=in_array($orderBy,array('viewcount','time'))?$orderBy:'viewcount';
		$crt=getCache('contentsRanks'.$cat.'year'.$year.'week'.$week.'day'.$orderBy);
		if ($crt&&$useCache){
			return unserialize($crt);
		}else {
			//计算上一天的数据
			if ($day>1){
				$day=$day-1;
			}else {
				$day=7;
				if ($week!=1){
					$week=$week-1;
				}else {
					$week=52;
					$year=$year-1;
				}
			}
			$stopTime=SYS_TIME-7*24*3600;
			switch ($orderBy){
				case 'viewcount':
					$orderBySql='V.viewcount DESC';
					break;
				case 'time':
					$orderBySql='A.time DESC';
					break;
			}
			
			if ($orderBy=='time'){
				$rts=$this->article_db->get_results('id,channel_id,site,title,subtitle,link,externallink,thumb,intro,author,source,keywords,time','','atype=\''.$cat.'\' AND `time`>'.$stopTime,'time DESC','0,'.$count);
			}else {
				$sql='SELECT A.id,A.channel_id,A.site,A.title,A.subtitle,A.link,A.externallink,A.thumb,A.intro,A.author,A.source,A.keywords,A.time FROM '.TABLE_PREFIX.'article A,'.TABLE_PREFIX.'content_viewlog V WHERE A.atype=\''.$cat.'\' AND V.contentid=A.id AND V.year='.$year.' AND V.week='.$week.' AND A.time>'.$stopTime.' ORDER BY '.$orderBySql.' LIMIT 0,'.$count;
				$rts=$this->article_db->get_resultsBySql($sql);
			}
			if (!$rts){
				$rts=$this->article_db->get_results('id,channel_id,site,title,subtitle,link,externallink,thumb,intro,author,source,keywords,time','','atype=\''.$cat.'\' AND `time`>'.$stopTime,'time DESC','0,'.$count);
			}
			setZendCache(serialize($rts),'contentsRanks'.$cat.'year'.$year.'week'.$week.'day'.$orderBy);
			return $rts;
		}
	}
	/**
	 * 验证文章类别
	 *
	 * @param unknown_type $cat
	 * @return unknown
	 */
	function getCat($cat){
		if (!in_array($cat,$this->cats)){
			return $this->cats[0];
		}else {
			return $cat;
		}
	}
	function searchContentSql($channelID=0,$keywords='',$page=0,$count=0,$starttime=0,$endtime=0,$exceptChannel=0,$uid=0,$siteid=1){
		$channelID=intval($channelID);
		$keywords=format_bracket($keywords);
		$starttime=intval($starttime);
		$endtime=intval($endtime);
		$exceptChannel=intval($exceptChannel);
		$keywordsArr=explode(' ',$keywords);
		$normalWords=array('的','什么');
		$keywordsSql='';
		if ($keywordsArr){
			foreach ($keywordsArr as $k){
				if (strlen($k)&&!in_array($k,$normalWords)){
					$keywordsSql.=' OR content LIKE \'%'.$k.'%\' OR title LIKE \'%'.$k.'%\'';
				}
			}
		}
		$keywordsSql=substr($keywordsSql,4);
		if (strlen($keywordsSql)){
			$keywordsSql=' AND ('.$keywordsSql.')';
		}
		//time
		if (!$starttime&&!$endtime){
			$timeSql='';
		}elseif ($endtime==0){
			$timeSql=' AND time>'.$starttime.' AND time<'.SYS_TIME;
		}else {
			$timeSql=' AND time>'.$starttime.' AND time<'.$endtime;
		}
		//
		//if (strlen($keywordsSql)){
		$start=0;
		if ($page>1){
			$start=($page-1)*$count;
		}
		$limitSql='';
		if ($count){
			$limitSql.=' LIMIT '.$start.','.$count;
		}
		$channelIDSql='';
		if ($exceptChannel){
			$exceptChannelSql=' AND channel_id!='.$exceptChannel;
		}
		$uid=intval($uid);
		$uidSql='';
		if ($uid){
			$uidSql=' AND uid='.$uid;
		}
		
		if ($channelID){
			$channelObj=bpBase::loadAppClass('channelObj','channel');
			$descentChannels=$channelObj->allDescentChannels($channelID);
			$descentsCount=count($descentChannels);
			$channelIDArr=array($channelID);
			if ($descentChannels){
				foreach ($descentChannels as $c){
					array_push($channelIDArr,$c->id);
				}
			}
			$channelIDSql=' AND '.to_sqls($channelIDArr,'','channel_id');
		}else {
			$channelIDSql='';
		}
		$article_db=M('article');
		if (!$page&&!$count){
			$sql='SELECT COUNT(id) FROM '.$article_db->table_name.' WHERE site='.$siteid.$channelIDSql.$timeSql.$keywordsSql.$exceptChannelSql.$uidSql;
		}else {
			$sql='SELECT * FROM '.$article_db->table_name.' WHERE site='.$siteid.$channelIDSql.$timeSql.$keywordsSql.$exceptChannelSql.$uidSql.' ORDER BY time DESC'.$limitSql;
		}
		return $sql;
	}
	function searchContents($channelID=0,$keywords='',$page=0,$count=0,$starttime=0,$endtime=0,$exceptChannel=0,$uid=0,$siteid=1){
		$sql=$this->searchContentSql($channelID,$keywords,$page,$count,$starttime,$endtime,$exceptChannel,$uid,$siteid);
		$contents=$this->article_db->get_resultsBySql($sql);
		return $contents;
	}
	/**
	 * 按照栏目和地区获取内容列表
	 *
	 * @param unknown_type $channelid
	 * @param unknown_type $geoid
	 * @param unknown_type $page
	 * @return unknown
	 */
	function getContentsByChannelIDAndGeo($channelid,$geoid=0,$page=1){
		$channelid=intval($channelid);
		$page=intval($page);
		$geoid=intval($geoid);
		$cacheName='contentsByChannelID'.$channelid.'Geo'.$geoid.'page'.$page;
		$cache=getCache($cacheName);
		if ($cache){
			return unserialize($cache);
		}else{
			$channel=bpBase::loadAppClass('channelObj','channel',1);
			$thisChannel=$channel->getChannelByID($channelid);
			$thisChannelPageSize=intval($thisChannel->pagesize);
			$start=($page-1)*$thisChannelPageSize;
			$articles=$this->article_db->get_results('*','',array('channel_id'=>$channelid,'geoid'=>$geoid),'taxis DESC',$start.','.$thisChannelPageSize);
			if ($articles){
				$i=0;
				foreach ($articles as $a){
					if (!$a->externallink&&!strpos($a->link,'ttp://')){
						//$articles[$i]->link=MAIN_URL_ROOT.$a->link;
					}
					if (!strpos($a->thumb,'ttp://')){
						$articles[$i]->thumb=MAIN_URL_ROOT.$a->thumb;
					}
				}
			}
			setZendCache(serialize($articles),$cacheName);
			return $articles;
		}
	}
	function relateArticlesOfContent($contentid){
		$str='';
		$thisContent=$this->getContentByID($contentid);
		$relateContents=$this->relateContentsOfSpecifiedContent($thisContent->id,0,5,$thisContent);
		if ($relateContents){
			foreach ($relateContents as $c){
				$str.='<li>·<a href="'.$c['link'].'" style="font-size:14px;line-height:25px">'.$c['title'].'</a> <i style="color:#666">'.date('Y-m-d',$c['time']).'</i></li>';
			}
		}
		return $str;
	}
	function getRalateAuto($contentHtml){
		$auto_db=bpBase::loadModel('autoclassification_model');
		$autos=$auto_db->get_results('id,name','','grade=3 AND status<3');
		$autoids='';
		$keywords=',';
		if ($autos){
			$i=0;
			$comma=',';
			foreach ($autos as $a){
				$p = '/('.$a->name.')/i';
				if (@preg_match($p,$contentHtml)&&strlen($a->name)){
					$autoids.=$a->id.$comma;
					$keywords.=$a->name.$comma;
					if ($i>2){
						break;
					}
					$i++;
				}
			}
		}
		return array('autoid'=>$autoids,'keyword'=>$keywords);
	}
	function getAutoContentsByCat($cat,$start=0,$getcount=10){
		if (!in_array($cat,$this->cats)){
			$cat='news';
		}
		$start=intval($start);
		$getcount=intval($getcount);
		$contents=$this->article_db->get_results('id,channel_id,site,title,subtitle,link,externallink,thumb,intro,author,source,keywords,time,autoid,autoname,autograde','','`atype`=\''.$cat.'\'','taxis DESC',$start.','.$getcount);
		return $contents;
	}
	/**
	 * 根据url判断文章类型，文章id，因为有些文章是外部链接，移动版无法直接读取外部链接
	 *
	 * @param unknown_type $url
	 */
	function getLinkInfo($url){
		if (!strExists($url,'http:')||strExists($url,DOMAIN_ROOT)){//肯定是站内的
			if (strExists($url,'store/')){//经销商新闻
				$urls=explode('/',$url);
				$count=count($urls);
				$id=str_replace('.html','',$urls[$count-1]);
				//
				$store_content_db=bpBase::loadModel('store_content_model');
				$thisContent=$store_content_db->get($id);
				//
				return array('type'=>'storeContent','id'=>$id,'storeid'=>$thisContent->storeid);
			}else {//普通文章
				$urls=explode('/',$url);
				$count=count($urls);
				$id=str_replace(array('.html','.shtml'),'',$urls[$count-1]);
				return array('type'=>'content','id'=>$id);
			}
		}else {//站外的地址
			return null;
		}
	}
}