<?php
bpBase::loadAppClass('tag','template');
class template {
	var $gtag;
	function __construct($globalTag='stl'){
		$this->gtag=$globalTag;
		$this->template_db = bpBase::loadModel('template_model');
	}
	public function parseStr($str,$id){
		$this->addIDtoFirstLayerTagInTemplate($siteid,$templateid=$id,$file='',$str);
		return $this->parseFirstLayerTag($templateid=$id,$siteid,0,0,ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'temp.html');
	}
	public function get($id){
		$id=intval($id);
		$crt=getCache('template'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			$template=$this->template_db->get_row(array('id'=>$id));
			$file=ABS_PATH.substr($template->path,1);
			if (!file_exists($file)){
				$fp=@fopen($file,"w+");
			}else {
				$fp=@fopen($file,'r');
			}
			$code=@file_get_contents($file);
			@fclose($fp);
			$template->code=$code;
			setZendCache(serialize($template),'template'.$id);
			return $template;
		}
	}
	public function cats(){
		return array(1=>'首页模板',2=>'栏目模板',3=>'内容模板',4=>'单页模板');
	}
	public function getDefaultTemplate($siteID,$type){
		$siteID=intval($siteID);
		$type=intval($type);
		$r=$this->template_db->get_one(array('site'=>$siteID,'type'=>$type,'isdefault'=>1));
		if (!$r){
			$r=$this->template_db->get_one(array('site'=>$siteID,'type'=>$type),'*','id ASC');
		}
		return $r;
	}
	function toAbsolutePath($templatePath){
		$templatePath=str_replace('@','',$templatePath);
		if (substr($templatePath,0,1)!='/'){
			$templatePath='/'.$templatePath;
		}
		return ABS_PATH.$templatePath;
	}
	function getTemplateInfoByPath($templatePath){
		$absPath=template::toAbsolutePath($templatePath);
		$slashesArr=explode('/',$absPath);
		$fileFullName=$slashesArr[count($slashesArr)-1];
		$fileFullNames=explode('.',$fileFullName);
		$fileType=$fileFullNames[count($fileFullNames)-1];
		$fileName=str_replace('.'.$fileType,'',$fileFullName);
		return array('absPath'=>$absPath,'fileFullName'=>$fileFullName,'fileType'=>$fileType,'fileName'=>$fileName);
	}
	function createIndexPage($siteid=1){
		$siteid=intval($siteid);
		if (!ABS_PATH.'templatesCache'&&!is_dir(ABS_PATH.'templatesCache')){
			mkdir(ABS_PATH.'templatesCache',777);
		}
		if ($siteid<100){//站点
			$template=$this->getDefaultTemplate($siteid,1);//获取首页模板的数据库信息
			if (!$template){
				showMessage('没有默认的模板，请在模板管理里面设置','?m=template&c=m_template&a=templates&siteid='.$siteid,2000);
				exit();
			}
			//如果缓存不存在则分析模板
			if (!file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template['id'].'.parsed.tpl.php')){
				$templateInfo=$this->getTemplateInfoByPath($template['path']);
				//parse first layer tags
				$this->addIDtoFirstLayerTagInTemplate($siteid,$template['id'],$templateInfo['absPath']);
			}
			$this->parseFirstLayerTag($template['id'],$siteid,0,0);

			//生成静态首页
			if (defined('NEW_INDEX')&&NEW_INDEX&&!loadConfig('index','notToHtml')){
				$sitePage=bpBase::loadAppClass('sitePage','site');
				$sitePage->index(0);
			}else {
				if ((loadConfig('site','tohtml')||!loadConfig('index','notToHtml'))&&$siteid==1){
					$snoopy=bpBase::loadSysClass('Snoopy','',1);
					if(!strpos(MAIN_URL_ROOT,'localhost')){
						//echo 'st';
						$snoopy->fetch('http://'.$_SERVER['HTTP_HOST'].'/index.php');
					}else {
						$snoopyRt=$snoopy->fetch('http://127.0.0.1/index.php');
					}
					//file_put_contents(ABS_PATH.'/index.html',$snoopy->result);
				}
			}
			if (isah()){
				$this->createSinglePage(193);
			}
			if ($siteid>1){//ah子站
				$site=bpBase::loadAppClass('siteObj','site');
				$thisSite=$site->getSiteByID($siteid);
				$child_siteMoudleClass=bpBase::loadAppClass('sitePage','site');
				$geo_db=bpBase::loadModel('geo_model');
				$thisGeo=$geo_db->get_one(array('geoindex'=>$thisSite->siteindex));
				if ($thisGeo){
					$child_siteMoudleClass->childSiteIndex($thisGeo['id']);
				}
			}
		}else{//专题
			$special_db=bpBase::loadModel('special_model');
			$thisSpecial=$special_db->get_one(array('id'=>$siteid));
			if (!$thisSpecial['templateid']){
				showMessage('没有选择模板，请设置模板后再操作','?m=special&c=m_special&a=specialSet&id='.$siteid,2000);
				exit();
			}
			$template_db=bpBase::loadModel('template_model');
			$template=$template_db->get_one(array('id'=>$thisSpecial['templateid']));
			if (!$template){
				showMessage('模板不存在，请在模板管理里面设置','?m=template&c=m_template&a=templates&siteid='.$siteid.'&type=5',2000);
				exit();
			}
			
			//如果缓存不存在则分析模板
			//if (!file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template['id'].'.parsed.tpl.php')){
				$templateInfo=$this->getTemplateInfoByPath($template['path']);
				//parse first layer tags
				$this->addIDtoFirstLayerTagInTemplate($siteid,$template['id'],$templateInfo['absPath']);
			//}
			$this->parseFirstLayerTag($template['id'],$siteid,0,0);
			//更新专题url
			$specialConfig=loadConfig('special');
			$thisSpecial['urlformat']=$thisSpecial['urlformat']?$thisSpecial['urlformat']:$specialConfig['urlFormate'];
			$url=$this->specialPathReplace($thisSpecial['urlformat'],$thisSpecial['catid'],$thisSpecial);
			$special_db->update(array('url'=>$url),array('id'=>$siteid));
			delCache('c_special_'.$siteid);
		}
	}
	/**
	 * handle template path:clear @ , turn into ralte path,'@/template/index.html'
	 *
	 * @param string $templatePath
	 * @return string
	 */
	function initTemplatePath($templatePath){
		$templatePath=str_replace('@','',$templatePath);
		if (substr($templatePath,0,1)!='/'){
			$templatePath='/'.$templatePath;
		}
		return $templatePath;
	}
	/**
	 * parse the first layer stl tag and save to cache file
	 *
	 * @param string $file
	 */
	function parseTemplate($file){
		//
		$txt=file_get_contents($file);
		//handle tag:include
		include_once('tag/include.php');
		$tag_include=new tag_include();
		while (preg_match('#<'.$this->gtag.':include([a-z0-9 _\-=\"\/@.]+)><\/'.$this->gtag.':include>#i',$txt)) {
			$includeTagStart='<'.$this->gtag.':include';//<stl:include
			$includeTagEnd='</'.$this->gtag.':include>';//</stl:include>
			$includeTagStartPos=strpos($txt,$includeTagStart);
			$includeTagEndPos=strpos($txt,$includeTagEnd);
			$includeStr=substr($txt,$includeTagStartPos,$includeTagEndPos+strlen($includeTagEnd)-$includeTagStartPos);
			//
			$txt=str_replace($includeStr,$tag_include->parse($includeStr),$txt);
		}
		//end of handling tag:include
		//
		$txtLen=strlen($txt);
		if ($txtLen<5){
			return $txt;
		}
		$tagStart='<'.$this->gtag.':';//<stl:
		$i=0;
		while ($i<$txtLen-8){
			$tagInfo=$this->getTagInfo($txt);
			if ($tagInfo){
				//get tag name,eg:contents
				$tagName=$tagInfo['name'];
				//
				$str=$tagInfo['string'];//<stl:contents ..... </stl:contents>
				if ($i<$txtLen){
					//parse tag
					$parsedStr=$this->parseTag($str);
				}
				$preStr=substr($txt,0,strpos($txt,$str));
				$backStr=substr($txt,$tagInfo['tagendpos']+strlen('</'.$this->gtag.':'.$tagName.'>'));
				$txt=$preStr.$parsedStr.$backStr;
				//
			}
			$txtLen=strlen($txt);
			if ($tagInfo['tagendpos']&&intval($tagInfo['tagendpos'])>0){
				$i=$tagInfo['gtagpos']+strlen($parsedStr);
			}else {
				$i=$txtLen+1;
			}
			//
		}
		//
		$txt=str_replace('[stl.constant:BBS_URL_ROOT]',loadConfig('system','bbsUrlRoot'),$txt);
		$txt=str_replace('[stl.fullTitle]','<?php echo $ct->title;?>',$txt);
		$txt=str_replace('[stl.site.name]','<?php echo $currentSite->name;?>',$txt);
		//handle stl.
		$p[0] = '#\[stl.constant:([a-z0-9_\-]+)\]#i';
		$r[0] = '<?php echo $1;?>';
		$txt = preg_replace($p, $r, $txt);
		//
		return $txt;
	}
	/**
	 * parse code section
	 *
	 * @param string $str
	 * @return string
	 */
	function parseTag($str){//......<stl:*>............</stl:*>.......
		while(strpos($str,'</'.$this->gtag.':')){
			$firstTagInfo=$this->getTagInfo($str);
			$tagName=$firstTagInfo['name'];
			$tagStr=$firstTagInfo['string'];
			//
			$dir=substr(__FILE__,0,-7);
			if (strlen($tagName)&&file_exists($dir.'tag/'.$tagName.'.php')){
				include_once($dir.'tag/'.$tagName.'.php');
				$thisTagClassName='tag_'.$tagName;
				$thisTagClass=new $thisTagClassName();
				$parsedTagStr=$thisTagClass->parse($tagStr);
				$str=str_replace($tagStr,$parsedTagStr,$str);
			}else {
				$str=str_replace($tagStr,'',$str);
			}
		}
		return $str;
	}
	/**
	 * get specified tag str
	 *
	 * @param string $str
	 * @return string
	 */
	function getSpecifiedTagStr($templatePath,$tagName){
		$templatePath=$this->initTemplatePath($templatePath);
		$txt=file_get_contents(ABS_PATH.$templatePath);
		
		$tagStart='<'.$this->gtag.':'.$tagName;//<stl:pageContents
		$tagEnd='</'.$this->gtag.':'.$tagName.'>';//<stl:pageContents
		//
		$tagEndPos=strpos($txt,$tagEnd);
		if ($tagEndPos){
			$tagStartPos=strpos($txt,$tagStart);
			return substr($txt,$tagStartPos,$tagEndPos-$tagStartPos+strlen($tagEnd));
		}else {
			return '';
		}
	}
	
	/**
	 * get save absolute path of cache templates
	 *
	 * @param string $templatePath
	 * @return string
	 */
	function getTemplateCachePath($templatePath){
		$arr1=explode('.',$this->initTemplatePath($templatePath));// templates/index.html
		//
		$savePath=ABS_PATH.'/templatesCache'.$arr1[0].'.php';
		return $savePath;
	}
	/**
	 * clear template cache
	 *
	 * @param string $templatePath
	 * @return boolean
	 */
	function clearTemplateCache($templatePath){
		$savePath=$this->getTemplateCachePath($templatePath);
		if (file_exists($savePath)){
			return unlink($savePath);
		}else{
			return false;
		}
	}
	function specialPathReplace($str,$catid,$special){
		$specialConfig=loadConfig('special');
		$special_cat_db=bpBase::loadModel('special_cat_model');
		$cat=$special_cat_db->get_one(array('id'=>intval($catid)));
		return str_replace(array('{domainName}','{folder}','{specialIndex}','{catIndex}'),array(DOMAIN_NAME,$specialConfig['folder'],$special['specialindex'],$cat['enname']),$str);
	}
	//返回生成路径
	function createGeneratePath($tplid,$channelid=0,$contentid=0,$thisSpecial=''){
		$thisTpl=$this->get($tplid);
		if ($thisTpl->type!=5){//非专题模板
			$tplGPath=$thisTpl->generate_path;
		}else {//专题模板的路径根据专题配置来弄
			$tplGPath=$this->specialPathReplace($thisTpl->generate_path,$thisSpecial['catid'],$thisSpecial);
		}
		//init
		$tplGPath=str_replace('@','',$tplGPath);
		if (substr($tplGPath,0,1)!='/'){
			$tplGPath='/'.$tplGPath;
		}
		//
		$site=bpBase::loadAppClass('siteObj','site',1);
		
		$thisSite=$site->getSiteByID($thisTpl->site);
		$tplGPath=str_replace('~','/'.$thisSite->siteindex,$tplGPath);
		$tplGPath=str_replace('{siteIndex}',$thisSite->siteindex,$tplGPath);
		if (intval($channelid)){
			$channel=bpBase::loadAppClass('channelObj','channel',1);
			$thisChannel=$channel->getChannelByID($channelid);
			$tplGPath=str_replace('{channelIndex}',$thisChannel->cindex,$tplGPath);
			$tplGPath=str_replace('{contentID}',$contentid,$tplGPath);
			//
			$articleObj=bpBase::loadAppClass('articleObj','article');
			$thisContent=$articleObj->getContentByID($contentid);
			$tplGPath=str_replace(array('{year}','{month}','{day}'),array(date('Y',$thisContent->time),date('m',$thisContent->time),date('d',$thisContent->time)),$tplGPath);
		}
		//create directory
		$folders=explode('/',$tplGPath);
		$foldersCount=count($folders);
		$relatePath='';
		for ($i=1;$i<$foldersCount-1;$i++){
			$relatePath.='/'.$folders[$i];
			if (!file_exists(ABS_PATH.$relatePath)){
				mkdir(ABS_PATH.$relatePath,0777);
			}
		}
		return $tplGPath;
	}
	
	
	
	
	//new way of parase template
	/**
	 * get tag name and tag string
	 *
	 * @param string $str
	 * @return array
	 */
	function getTagInfo($str){
		$tagStart='<'.$this->gtag.':';//<stl:
		$replaceStr=str_replace($tagStart,'',$str);
		if (strlen($str)>8&&strlen($str)!=strlen($replaceStr)){
			$gtagPos=strpos($str,$tagStart);//"<stl:" position
			//" " position or ">" position(<stl:a> or <stl:a target="blank">),get tag name,eg:contents,
			if (strpos($str,' ',$gtagPos)){
				$sepPos=strpos($str,' ',$gtagPos)>strpos($str,'>',$gtagPos)?strpos($str,'>',$gtagPos):strpos($str,' ',$gtagPos);
			}else {
				$sepPos=strpos($str,'>',$gtagPos);
			}
			//get tag name,eg:contents
			$tagName=substr($str,$gtagPos+strlen($tagStart),$sepPos-$gtagPos-strlen($tagStart));
			$tag=new tag();
			$tags=$tag->tags;
			//if (in_array($tagName,$tags)){
				//position of tag end
				$fullTagEnd='</'.$this->gtag.':'.$tagName.'>';
				$tagendPos=strpos($str,$fullTagEnd,$sepPos);
				if ($tagendPos){
					//
					$tagstr=substr($str,$gtagPos,$tagendPos+strlen($fullTagEnd)-$gtagPos);//<stl:contents ..... </stl:contents>
					return array('name'=>$tagName,'string'=>$tagstr,'tagendpos'=>$tagendPos,'gtagpos'=>$gtagPos);
				}else {
					return '';
				}
			//}else {
				//return '';
			//}
		}else {
			return '';
		}
	}
	/**
	 * handle template path:clear @ , turn into ralte path,'@/template/index.html'
	 *
	 * @param string $templatePath
	 * @return string
	 */
	function getTemplateFunctionFilePath($templateid){
		//
		$savePath=ABS_PATH.'/templatesCache/'.$templateid.'.function.php';
		return $savePath;
	}
	function getParsedTemplateFilePath($templateid){
		//
		$savePath=ABS_PATH.'/templatesCache/'.$templateid.'.parsed.tpl.php';
		return $savePath;
	}
	function getTemplateTagsFilePath($templateid){
		//
		$savePath=ABS_PATH.'/templatesCache/'.$templateid.'.tags.tpl.php';
		return $savePath;
	}
	/**
	 * parse template,read the first layer tag,create three files:*.parsed.php(replace first layer tag with <tag_tagname_id/>),*.tags.php(first layers tag array),*.functin.php(php functions,create from the tags in templates)
	 *
	 * @param string $file
	 * @return unknown
	 */
	function addIDtoFirstLayerTagInTemplate($siteid,$templateid,$file,$txt='',$channelID=0){
		//
		if (!$txt){
			$txt=file_get_contents($file);
		}
		//handle tag:include
		$tag_include=bpBase::loadTagClass('tag_include',1);
		while (preg_match('#<'.$this->gtag.':include([a-z0-9 _\-=\"\/@.]+)><\/'.$this->gtag.':include>#i',$txt)) {
			$includeTagStart='<'.$this->gtag.':include';//<stl:include
			$includeTagEnd='</'.$this->gtag.':include>';//</stl:include>
			$includeTagStartPos=strpos($txt,$includeTagStart);
			$includeTagEndPos=strpos($txt,$includeTagEnd);
			$includeStr=substr($txt,$includeTagStartPos,$includeTagEndPos+strlen($includeTagEnd)-$includeTagStartPos);
			//
			$txt=str_replace($includeStr,$tag_include->parse($siteid,$includeStr),$txt);
		}
		
		//end of handling tag:include
		//
		//对每个标签进行编号
		/*
		while (preg_match('#<'.$this->gtag.':include([a-z]+)>#i',$txt)) {
			$includeTagStart='<'.$this->gtag.':include';//<stl:include
			$includeTagEnd='</'.$this->gtag.':include>';//</stl:include>
			$includeTagStartPos=strpos($txt,$includeTagStart);
			$includeTagEndPos=strpos($txt,$includeTagEnd);
			$includeStr=substr($txt,$includeTagStartPos,$includeTagEndPos+strlen($includeTagEnd)-$includeTagStartPos);
			//
			$txt=str_replace($includeStr,$tag_include->parse($includeStr),$txt);
		}
		*/
		//
		$txtLen=strlen($txt);
		if ($txtLen<5){
			file_put_contents($this->getParsedTemplateFilePath($templateid),$txt);
			file_put_contents($this->getTemplateTagsFilePath($templateid),'<?php $tagsArr=array();?>');
			return $txt;
		}
		$tagStart='<'.$this->gtag.':';//<stl:
		$i=0;
		$tagsArr=array();
		//
		while (strExists($txt,'<stl:')){
			$tagInfo=$this->getTagInfo($txt);
			if ($tagInfo){//replac first layer tags
				$txt=str_replace($tagInfo['string'],'<tag_'.$tagInfo['name'].'_'.$i.'/>',$txt);
				$avs=array();
				//把标签的属性值也写入数组中
				if ($thisTagClass=bpBase::loadTagClass('tag_'.$tagInfo['name'])){
					$avs=$thisTagClass->getAttributeValues($tagInfo['string'],$thisTagClass->attributes);
					$sql='';
					if ($tagInfo['name']=='contents'||$tagInfo['name']=='groupContents'){
						$sql=$thisTagClass->getSql($avs,$siteid,$channelID);
					}
					$avs['sql']=$sql;
				}
				$tagInfo['avs']=$avs;
			}
			array_push($tagsArr,$tagInfo);
			$i++;
			//
		}
		$tagsArrStr=var_export($tagsArr,true);
		$txt=handleConstant($txt);//处理常量
		file_put_contents($this->getParsedTemplateFilePath($templateid),$txt);
		file_put_contents($this->getTemplateTagsFilePath($templateid),'<?php
		
		$tagsArr='.$tagsArrStr.';
		');
		//
		return $txt;
	}
	function parseFirstLayerTag($templateid,$siteID=0,$channelID=0,$contentID=0,$saveFilePath='',$tagsArr=array(),$pagination=array('pageSize'=>20,'totalCount'=>0,'currentPage'=>1,'urlPrefix'=>'','urlSuffix'=>''),$obj=null,$type='',$onlyTags=array(),$exceptTags=array()){
		if ($type=='channel'&&file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$templateid.'.channel.parsed.tpl.php')){
			$templateHtml=file_get_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$templateid.'.channel.parsed.tpl.php');
		}else {
			$templateHtml=file_get_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$templateid.'.parsed.tpl.php');
		}
		//
		$dir=substr(__FILE__,0,-7);
		$i=0;
		if (!$tagsArr){
			include(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$templateid.'.tags.tpl.php');
		}
		if ($tagsArr){
			foreach ($tagsArr as $tag){
				$parseThisTag=true;
				if (count($onlyTags)&&!in_array($tag['name'],$onlyTags)){//如果标签不在限制的解析标签中则不解析
					$parseThisTag=false;
				}
				if (in_array($tag['name'],$exceptTags)){
					$parseThisTag=false;
				}
				if ($parseThisTag&&bpBase::loadTagClass('tag_'.$tag['name'])){
					$thisTagClassName='tag_'.$tag['name'];
					$thisTagClass=bpBase::loadTagClass('tag_'.$tag['name'],1);
					$returnStr=$thisTagClass->getValue($tag['string'],$tag['avs'],$siteID,$channelID,$contentID,$pagination,$obj);
					$templateHtml=str_replace('<tag_'.$tag['name'].'_'.$i.'/>',$returnStr,$templateHtml);
				}
				$i++;
			}
		}
		//保存路径
		if (!$saveFilePath){
			if ($siteID<100){
				$thisSpecial='';
				$specialIndex='';
			}else {//专题首页
				$special_db=bpBase::loadModel('special_model');
				$thisSpecial=$special_db->get_one(array('id'=>$siteID));
				$specialIndex=$thisSpecial['specialindex'];
			}
			$tplGPath=$this->createGeneratePath($templateid,$channelID,$contentID,$thisSpecial);
			$saveFilePath=ABS_PATH.$tplGPath;
		}
		//stag
		if (strExists($templateHtml,'[stl.')){
			$stag=bpBase::loadAppClass('stag','template');
			$templateHtml=$stag->handleStag($templateHtml);
		}
		file_put_contents($saveFilePath,$templateHtml);
		return $templateHtml;
	}
	
	function createChannelPageR($channelID,$page=1){
		//channel
		$channelID=intval($channelID);
		$channelObj=bpBase::loadAppClass('channelObj','channel',1);
		$thisChannel=$channelObj->getChannelByID($channelID);
		//分析模板
		$template=$this->get($thisChannel->channeltemplate);
		if (intval($template->createhtml)){//如果模板要求生成html页面
			$content=bpBase::loadAppClass('articleObj','article',1);
			if (file_exists(ABS_PATH.'/templatesCache/'.$template->id.'.parsed.tpl.php')){//如果存在模板缓存文件
			}else {//如果不存在模板缓存文件，则建立缓存文件
				$templateInfo=$this->getTemplateInfoByPath($template->path);
				//parse first layer tags
				$this->addIDtoFirstLayerTagInTemplate($thisChannel->site,$template->id,$templateInfo['absPath'],'',$channelID);
			}
			//
			if (file_exists(ABS_PATH.'/templatesCache/'.$template->id.'.tags.tpl.php')){
				@require_once(ABS_PATH.'/templatesCache/'.$template->id.'.tags.tpl.php');
				$pageSize=20;
				//判断是否有分页标签
				$isPagination=false;
				//每页多少条
				$tagIndex=0;
				$tag_pageContentsIndex=0;//只替换pageContents和pageItems,查找出pageContents的标签次序
				$tag_pageItemsIndex=0;//查找出pageItems的标签次序
				if ($tagsArr){
					foreach ($tagsArr as $t){
						if ($t['name']=='pageContents'){
							//
							$isPagination=true;
							$tag_pageContentsIndex=$tagIndex;
							//
							$pageSize=$t['avs']['pageNum'];
						}
						if ($t['name']=='pageItems'){
							$tag_pageItemsIndex=$tagIndex;
						}
						$tagIndex++;
					}
				}
				if ($isPagination){
					$totalCount=$content->getContentsCountByChannelID($channelID,'taxis','children');
					$totalPage=$totalCount%$pageSize>0?intval($totalCount/$pageSize)+1:$totalCount/$pageSize;
					$totalPage=$totalPage<1?1:$totalPage;
				}else {
					$totalCount=1;
					$totalPage=1;
				}
			
				//默认只生成第一页和最后一页，因为最后一页可能只有一条，用户可能访问不到
				for ($i=1;$i<$totalPage+1;$i++){
					if ($i==$page||($page==1&&$i==$totalPage)){
						$paths=$this->getChannelFileSavePath($template->generate_path,array('channelIndex'=>$thisChannel->cindex,'page'=>$i,'siteID'=>$thisChannel->site));
						$urlParts=explode('.',$paths['urlPath']);
						$this->parseFirstLayerTag($template->id,$thisChannel->site,$channelID,0,ABS_PATH.$paths['savaPath'],$tagsArr,array('pageSize'=>$pageSize,'totalCount'=>$totalCount,'currentPage'=>$i,'urlPrefix'=>$urlParts[0],'urlSuffix'=>'.'.$urlParts[1]),null,'channel');
					}
				}
				if ($page==1){
					//更新栏目链接地址
					$channelObj->updateLink($channelID,$paths['urlPath']);
				}
				$channelObj->updateLastCreateTime($channelID);
			}
		}else {//栏目页不生成html文件，只更新栏目的链接地址
			if ($page<2){
				//更新栏目链接地址
				$paths=$this->getChannelFileSavePath($template->generate_path,array('channelIndex'=>$thisChannel->cindex,'page'=>1,'siteID'=>$thisChannel->site),0);
				$channelObj->updateLink($channelID,$paths['urlPath']);
			}
		}
	}
	function getChannelFileSavePath($generatePath,$arr=array('channelIndex'=>'','page'=>'','siteID'=>''),$createhtml=1){
		$tplGPath=str_replace('@','',$generatePath);
		if (substr($tplGPath,0,1)!='/'&&substr($tplGPath,0,1)!='~'&&substr($tplGPath,0,1)!='?'){
			$tplGPath='/'.$tplGPath;
		}
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$thisChannel=$channelObj->getChannelByIndex($arr['channelIndex'],$arr['siteID']);
		$tplGPath=str_replace('{channelID}',$thisChannel->id,$tplGPath);
		$tplGPath=str_replace('{channelIndex}',$arr['channelIndex'],$tplGPath);
		$tplGPath=str_replace(array('{year}','{month}','{day}'),array(date('Y',SYS_TIME),date('m',SYS_TIME),date('d',SYS_TIME)),$tplGPath);
		$urlPath=$tplGPath;
		//子站和专题
		$siteID=intval($arr['siteID']);
		/*
		if (str_replace('~','',$tplGPath)!=$tplGPath&&$siteID<100){
			$siteObj=bpBase::loadAppClass('siteObj','site',1);
			$thisSite=$siteObj->getSiteByID($siteID);
			$urlPath=str_replace('~','',$tplGPath);
			$tplGPath=str_replace('~','/'.$thisSite->siteindex,$tplGPath);
			//$urlPath=str_replace('~/{$siteIndex}','',$tplGPath);
		}
		*/
		//create directory
		$folders=explode('/',$tplGPath);
		$foldersCount=count($folders);
		$relatePath='';
		for ($i=1;$i<$foldersCount-1;$i++){
			$relatePath.='/'.$folders[$i];
			if (!file_exists(ABS_PATH.$relatePath)&&$createhtml){
				mkdir(ABS_PATH.$relatePath,0777);
			}
		}
		if ($arr['page']>1){
			$tplGPath=str_replace('.','-'.$arr['page'].'.',$tplGPath);
		}
		return array('savaPath'=>$tplGPath,'urlPath'=>$urlPath);
	}
	function getSinglePageFileSavePath($generatePath){
		$tplGPath=str_replace('@','',$generatePath);
		return $tplGPath;
	}
	function getContentFileSavePath($generatePath,$arr=array('contentID'=>'','page'=>'','siteID'=>''),$createhtml=1){
		$tplGPath=str_replace('@','',$generatePath);
		if (substr($tplGPath,0,1)!='/'&&substr($tplGPath,0,1)!='~'&&substr($tplGPath,0,1)!='?'){
			$tplGPath='/'.$tplGPath;
		}
		
		$tplGPath=str_replace('{contentID}',$arr['contentID'],$tplGPath);
		//
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$thisContent=$articleObj->getContentByID($arr['contentID']);
		$tplGPath=str_replace('{channelID}',$arr['channelID'],$tplGPath);
		$tplGPath=str_replace('{channelIndex}',$arr['channelIndex'],$tplGPath);
		$tplGPath=str_replace(array('{year}','{month}','{day}'),array(date('Y',$thisContent->time),date('m',$thisContent->time),date('d',$thisContent->time)),$tplGPath);
		$urlPath=$tplGPath;
		//子站
		if (str_replace('~','',$tplGPath)!=$tplGPath){
			$siteObj=bpBase::loadAppClass('siteObj','site',1);
			$thisSite=$siteObj->getSiteByID($arr['siteID']);
			$urlPath=str_replace('~','',$tplGPath);
			$tplGPath=str_replace('~','/'.$thisSite->siteindex,$tplGPath);
			
			//$urlPath=str_replace('~/{$siteIndex}','',$tplGPath);
		}
		//create directory
		$folders=explode('/',$tplGPath);
		$foldersCount=count($folders);
		$relatePath='';
		for ($i=1;$i<$foldersCount-1;$i++){
			$relatePath.='/'.$folders[$i];
			if (!file_exists(ABS_PATH.$relatePath)&&$createhtml){
				mkdir(ABS_PATH.$relatePath,0777);
			}
		}
		if ($arr['page']>1){
			$tplGPath=str_replace('.','-'.$arr['page'].'.',$tplGPath);
		}
		return array('savaPath'=>$tplGPath,'urlPath'=>$urlPath);
	}
	//生成内容页
	function createContentPageR($contentID,$thisChannel=''){
		$channelObj=bpBase::loadAppClass('channelObj','channel',1);
		$articleObj=bpBase::loadAppClass('articleObj','article',1);
		//channel
		$contentID=intval($contentID);
		$thisContent=$articleObj->getContentByID($contentID);
		if ($thisChannel==''){
			$thisChannel=$channelObj->getChannelByID($thisContent->channel_id);
		}
		//
		$template=$this->get($thisChannel->contenttemplate);
		if ($thisContent->time>SYS_TIME){
			return '';
		}
		if (intval($template->createhtml)&&intval($thisChannel->channeltype)!=2){//图片栏目内容不生成静态页面
			//分析模板
			if (file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template->id.'.parsed.tpl.php')){
			}else {
				$templateInfo=$this->getTemplateInfoByPath($template->path);
				//parse first layer tags
				$this->addIDtoFirstLayerTagInTemplate($thisChannel->site,$template->id,$templateInfo['absPath'],'',$thisChannel->id);
			}
			if (file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template->id.'.tags.tpl.php')){
				require(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template->id.'.tags.tpl.php');
				//
				if (intval($thisContent->pagecount)<2){
					$contents=array($thisContent->content);
					$totalCount=1;
					$totalPage=$totalCount;
				}else {
					$contents=$articleObj->contentPagination($thisContent->content);
					$totalCount=count($contents);
					$totalPage=$totalCount;
				}
				//
				for ($i=1;$i<$totalPage+1;$i++){
					$paths=$this->getContentFileSavePath($template->generate_path,array('channelID'=>$thisChannel->id,'channelIndex'=>$thisChannel->cindex,'contentID'=>$contentID,'page'=>$i,'siteID'=>$thisContent->site));
					$urlParts=explode('.',$paths['urlPath']);
					$thisContent->content=$contents[$i-1];
					$this->parseFirstLayerTag($template->id,$thisContent->site,$thisContent->channel_id,$contentID,ABS_PATH.$paths['savaPath'],$tagsArr,array('pageSize'=>1,'totalCount'=>$totalCount,'currentPage'=>$i,'urlPrefix'=>$urlParts[0],'urlSuffix'=>'.'.$urlParts[1]),$thisContent,'content');
				}
				//更新栏目链接地址
				$articleObj->updateLink($contentID,$paths['urlPath']);
				$articleObj->updateLastCreateTime($contentID);
			}
		}else {
			$paths=$this->getContentFileSavePath($template->generate_path,array('channelID'=>$thisChannel->id,'channelIndex'=>$thisChannel->cindex,'contentID'=>$contentID,'page'=>1,'siteID'=>$thisContent->site),$template->createhtml);
			$articleObj->updateLink($contentID,$paths['urlPath']);
		}
	}
	function createSinglePage($templateid){
		$templateid=intval($templateid);
		//分析模板
		$template=$this->get($templateid);
		if ($template){
			$siteid=1;
			if (file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$templateid.'.parsed.tpl.php')){
			}else {
				$templateInfo=$this->getTemplateInfoByPath($template->path);
				//parse first layer tags
				$this->addIDtoFirstLayerTagInTemplate($siteid,$template->id,$templateInfo['absPath']);
			}
			include(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template->id.'.tags.tpl.php');
			//
			$this->parseFirstLayerTag($template->id,$siteid,0,0,ABS_PATH.$this->getSinglePageFileSavePath($template->generate_path),$tagsArr,array(),null,'singlePage');
		}
	}
}
function handleConstant($txt){
	//handle stl.
	$p[0] = '#\[stl.constant:([a-z0-9_\-]+)\]#i';
	$r[0] = '$1';
	$txt = preg_replace_callback($p,create_function('$matches','return eval("return $matches[1];");'),$txt);
	return $txt;
}
?>