<?php
class tag_contents extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'ID',
		'site',//which site,default this site
		'channelIndex',//which channel,default this channel
		'totalNum',//how many contents fetched
		'scope',//children channels or only specified channel
		'startNum',//which to start
		'titleLen',
		'introLen',
		'cityid',//which city of this content
		'dateFormat',
		'order',//taxis
		'isDynamic',//
		'absPath',//
		'numStart',
		'sqlStr',
		'autoID',
		'autoName',
		'autoAdvantage',
		'autoDefect',
		'autolink',
		'currentItemClass',//当前元素类名
		'isImage',//if content only has thumb
		'cat',
		'minprice',
		'maxprice',
		'exceptChanneIDs'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$csiteID=0,$thisChannelID=0,$contentID=0){//<stl:***></stl:***>
		$content=bpBase::loadAppClass('articleObj','article',1);
		$mobileConfig=loadConfig('mobile');
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$avs['numStart']=isset($avs['numStart'])?$avs['numStart']:1;
		if (!$avs['sqlStr']){
			$isDynamic=$avs['isDynamic']==null?false:$avs['isDynamic'];
			$site=bpBase::loadAppClass('siteObj','site',1);
			if ($avs['site']){
				$thisSite=$site->getSiteByID($avs['site']);
				$siteID=intval($avs['site']);
			}else {
				$siteID=intval($csiteID);
			}
			if ($isDynamic||!isset($avs['sql'])||!strlen($avs['sql'])||$thisChannelID!=0||$contentID!=0){
				$channel=bpBase::loadAppClass('channelObj','channel',1);
				//properties
				$num=intval($avs['totalNum']);
				$start=$avs['startNum']==null?0:intval($avs['startNum'])-1;
				$scope=$avs['scope']==null?'self':'children';
				$order=$avs['order'];
				if ($avs['isImage']==null){
					$isImage='null';
				}else{
					$isImage=$avs['isImage']=='true'?1:0;
				}
				switch ($order){
					case null:
						$order='taxis';
						break;
					case 'Hits':
						$order='viewcount';
						break;
					case 'AddDate':
						$order='time';
						break;
				}


				if ($avs['channelIndex']){
					$thisChannel=$channel->getChannelByIndex($avs['channelIndex'],$siteID);
					$channelID=$thisChannel->id;
				}elseif($avs['channelID']){
					$channelID=$avs['channelID'];
				}else {
					$channelID=$thisChannelID;
				}
			}
			//
			$returnStr='';
			if (!$isDynamic){
				if (isset($avs['sql'])&&strlen($avs['sql'])&&!$thisChannelID){
					$content_db=bpBase::loadModel('article_model');
					$avs['sql']=str_replace('{sysTime}',SYS_TIME,$avs['sql']);
					$contents=$content_db->get_resultsBySql($avs['sql']);
				}else {
					
					$sql=$this->getSql($avs,$siteID,$thisChannelID);
					//$content=bpBase::loadAppClass('articleObj','article',1);
					$content_db=bpBase::loadModel('article_model');
					$avs['sql']=str_replace('{sysTime}',SYS_TIME,$avs['sql']);
					$contents=$content_db->get_resultsBySql($avs['sql']);
					//$contents=$content->getContentsByChannelID($channelID,$num,$order,$start,$scope,$isImage);
				}
				if ($contents){
					$i=0;
					$middleStr=parent::getMiddleBody($str,'contents',$this->gTag);
					$tags=array('[stl.fullTitle]','[stl.content.fullTitle]','[stl.content.author]','[stl.content.source]','[stl.content.thumb]','[stl.content.content]','[stl.content.autoname]');
					foreach ($contents as $c){
						$replaces=array($c->title,$c->title,$c->author,$c->source,$c->thumb,$c->content,$c->autoname);
						$valueStr=str_replace($tags,$replaces,$middleStr);
						if ($avs['titleLen']){
							$valueStr=str_replace('[stl.content.title]',mb_substr($c->title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
							if (strlen($c->subtitle)){//判断是否有副标题
								$valueStr=str_replace('[stl.content.subtitle]',mb_substr($c->subtitle,0,$avs['titleLen'],DB_CHARSET),$valueStr);
							}else {
								$valueStr=str_replace('[stl.content.subtitle]',mb_substr($c->title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
							}
						}else {
							if (strlen($c->subtitle)){//判断是否有副标题
								$valueStr=str_replace(array('[stl.content.title]','[stl.content.subtitle]'),array($c->title,$c->subtitle),$valueStr);
							}else {
								$valueStr=str_replace(array('[stl.content.title]','[stl.content.subtitle]'),array($c->title,$c->title),$valueStr);
							}
						}
						if ($avs['introLen']){
							$valueStr=str_replace('[stl.content.intro]',mb_substr($c->intro,0,$avs['introLen'],DB_CHARSET),$valueStr);
						}else {
							$valueStr=str_replace('[stl.content.intro]',$c->intro,$valueStr);
						}
						if ($avs['absPath']||intval($avs['site'])!=$csiteID){
							if (!$c->externallink){
								if ($i==0){
									$thisSite=$site->getSiteByID($c->site);
								}
								if (!$thisSite->url){
									$thisSite->url=MAIN_URL_ROOT;
								}
								$valueStr=str_replace('[stl.content.link]',$thisSite->url.$c->link,$valueStr);
							}else {
								if (substr($c->link,0,1)!='h'){
									$thisSite=$site->getSiteByID($c->site);
									$valueStr=str_replace('[stl.content.link]',$thisSite->url.$c->link,$valueStr);
								}else {
									$valueStr=str_replace('[stl.content.link]',$c->link,$valueStr);
								}
							}
						}else {
							$valueStr=str_replace('[stl.content.link]',$c->link,$valueStr);
						}
						//手机版链接
						if ($c->externallink){
							$articleInfo=$articleObj->getLinkInfo($c->link);
							if ($articleInfo){
								switch ($articleInfo['type']){
									case 'content':
										$mlink=$mobileConfig['homeUrl'].'/article/'.$articleInfo['id'];
										break;
									case 'storeContent':
										$mlink=$mobileConfig['homeUrl'].'/store/'.$articleInfo['storeid'].'/article/'.$articleInfo['id'];
										break;
								}
							}else {
								$mlink=$c->link;
							}
						}else {
							$mlink=$mobileConfig['homeUrl'].'/article/'.$c->id;
						}
						$valueStr=str_replace('[stl.content.mlink]',$mlink,$valueStr);
						//time
						$valueStr=str_replace('[stl.content.time]',date($avs['dateFormat'],$c->time),$valueStr);
						//other thumb
						$valueStr=str_replace('[stl.content.thumb2]',str_replace('.jpg','_small.jpg',$c->thumb),$valueStr);
						$valueStr=str_replace('[stl.content.thumb3]',str_replace('.jpg','_middle.jpg',$c->thumb),$valueStr);
						$valueStr=str_replace('[stl.content.thumb4]',str_replace('.jpg','_big.jpg',$c->thumb),$valueStr);
						//viewcount
						$valueStr=str_replace('[stl.content.viewcount]',$c->viewcount,$valueStr);
						//num
						$valueStr=str_replace('[stl.content.num]',$i+intval($avs['numStart']),$valueStr);
						//auto info
						$autoids=explode(',',$c->autoid);
						$firstAutoid=0;
						if ($autoids){
							foreach ($autoids as $autoid){
								if (intval($autoid)){
									$firstAutoid=$autoid;
									break;
								}
							}
						}
						$valueStr=str_replace('[stl.content.autoID]',$firstAutoid,$valueStr);
						if (intval($c->autograde)==1){
							if (URL_REWRITE){
								$valueStr=str_replace('[stl.content.autolink]',CAR_URL_ROOT.'/brand-'.$firstAutoid.'.html',$valueStr);
							}else {
								$valueStr=str_replace('[stl.content.autolink]',CAR_URL_ROOT.'/brand.php?id='.$firstAutoid,$valueStr);
							}
						}else {
							if (URL_REWRITE){
								$valueStr=str_replace('[stl.content.autolink]',CAR_URL_ROOT.'/'.$firstAutoid,$valueStr);
							}else {
								$valueStr=str_replace('[stl.content.autolink]',CAR_URL_ROOT.'/g3auto.php?id='.$firstAutoid,$valueStr);
							}
						}
						if ($firstAutoid){
							$valueStr=str_replace('[stl.content.autodisplay]','',$valueStr);
						}else {
							$valueStr=str_replace('[stl.content.autodisplay]',' style="display:none"',$valueStr);
						}
						//THIS AUTO
						if (strExists($valueStr,'[stl.content.auto')){
							if ($firstAutoid){
								$autoclassification_db=bpBase::loadModel('autoclassification_model');
								$firstAuto=$autoclassification_db->getCfByID($firstAutoid);
								$autoObj=bpBase::loadAppClass('autoObj','auto',1);
								$smallLogo=$autoObj->getLogo($firstAuto->id,$firstAuto->logo,'s',$firstAuto->grade);
								$middleLogo=$autoObj->getLogo($firstAuto->id,$firstAuto->logo,'m',$firstAuto->grade);
								if ($firstAuto){
									$valueStr=str_replace('[stl.content.autoName]',$firstAuto->name,$valueStr);
									if ($firstAuto->grade==3){
										$valueStr=str_replace('[stl.content.autoAdvantage]',$firstAuto->advantage,$valueStr);
										$valueStr=str_replace('[stl.content.autoDefect]',$firstAuto->defect,$valueStr);
									}
								}else {
									$valueStr=str_replace('[stl.content.autoName]','',$valueStr);
								}
								if ($c->thumb){
									$valueStr=str_replace(array('[stl.content.autoSmallLogo]','[stl.content.autoMiddleLogo]'),array($c->thumb,$c->thumb),$valueStr);
								}else {
									$valueStr=str_replace('[stl.content.autoSmallLogo]',$smallLogo,$valueStr);
									$valueStr=str_replace('[stl.content.autoMiddleLogo]',$middleLogo,$valueStr);
								}
							}else {
								$valueStr=str_replace('[stl.content.autoName]','',$valueStr);
								$valueStr=str_replace('[stl.content.autoAdvantage]','',$valueStr);
								$valueStr=str_replace('[stl.content.autoDefect]','',$valueStr);
							}
						}
						//内容中的经销商信息
						if (strExists($valueStr,'[stl.content.store')){
							$stag=bpBase::loadAppClass('stag','template');
							$storeid=$stag->getFirstTagValue($c->content,'store');
							$store_db=bpBase::loadModel('store_model');
							$thisStore=$store_db->getStoreByStoreID($storeid);
							$valueStr=str_replace('[stl.content.storeShortName]',$thisStore->shortname,$valueStr);
							$valueStr=str_replace('[stl.content.storeName]',$thisStore->name,$valueStr);
							$valueStr=str_replace('[stl.content.storeLink]',$thisStore->url,$valueStr);
						}
						//current class
						if ($contentID==$c->id){
							$valueStr=str_replace('[stl.content.currentItemClass]',$avs['currentItemClass'],$valueStr);
						}else {
							$valueStr=str_replace('[stl.content.currentItemClass]','',$valueStr);
						}
						//
						$returnStr.=$valueStr;
						$i++;
					}
				}
				return $returnStr;
			}else {
				return '<script src="http://'.$_SERVER['HTTP_HOST'].'/api/moopha_javascript.php?type=contents&channelID='.$channelID.'&num='.$num.'&order='.$order.'&scope='.$scope.'&isImage='.$isImage.'&site='.$siteID.'"></script>';
			}
		}else {//调用论坛帖子等
			$returnStr='';
			$content_db=bpBase::loadModel('article_model');
			$contents=$content_db->selectBySql($avs['sqlStr']);
			if ($contents){
				$i=0;
				$middleStr=parent::getMiddleBody($str,'contents',$this->gTag);
				foreach ($contents as $c){
					$valueStr=$middleStr;
					//time
					$valueStr=str_replace('[stl.content.time]',date($avs['dateFormat'],$c['time']),$valueStr);
					//num
					$valueStr=str_replace('[stl.content.num]',$i+intval($avs['numStart']),$valueStr);
					//替换属性
					foreach ($c as $k=>$v){
						$valueStr=str_replace('[stl.content.'.$k.']',$v,$valueStr);
					}
					//
					$returnStr.=$valueStr;
					$i++;
				}
			}
			return $returnStr;
		}
	}
	function getSql($avs,$siteID,$thisChannelID=0){
		if (!$avs['sqlStr']){
			//properties
			$num=intval($avs['totalNum']);
			$start=$avs['startNum']==null?0:intval($avs['startNum'])-1;
			$scope=$avs['scope']==null?'self':'children';
			$order=$avs['order'];
			if ($avs['isImage']==null){
				$isImage=null;
			}else{
				$isImage=$avs['isImage']=='true'?1:0;
			}
			switch ($order){
				case null:
					$order='taxis';
					break;
				case 'Hits':
					$order='viewcount';
					break;
				case 'AddDate':
					$order='time';
					break;
			}
			$channel=bpBase::loadAppClass('channelObj','channel',1);
			//properties
			$site=$avs['site']==null?$siteID:$avs['site'];
			$site=$site==0?1:$site;
			if ($avs['channelIndex']){
				$thisChannel=$channel->getChannelByIndex($avs['channelIndex'],$site);
				$channelID=$thisChannel->id;
			}else {
				if ($avs['channelID']){
					$channelID=intval($avs['channelID']);
				}else {
					$channelID=intval($avs['ID']);
				}
			}
			$content=bpBase::loadAppClass('articleObj','article',1);
			//排除栏目
			$exceptChanneIDs='';
			$exceptChannelSql='';
			if ($avs['exceptChanneIDs']){
				$exceptChanneIDs=explode(',',$avs['exceptChanneIDs']);
				if (count($exceptChanneIDs)){
					$exceptChannelSql=' AND channel_id NOT IN (';
					$comma='';
					foreach ($exceptChanneIDs as $exid){
						$exceptChannelSql.=$comma.$exid;
						$comma=',';
					}
					$exceptChannelSql.=')';
				}
			}
			if (!$avs['cityid']){
				$sql=$this->getSelectContentsSimpleSql($channelID,$num,$order,$start,$scope,$isImage,$exceptChanneIDs);
			}else {
				$sql='SELECT * FROM '.TABLE_PREFIX.'article WHERE channel_id=\''.$channelID.'\' AND geoid='.intval($avs['cityid']).$exceptChannelSql.' AND time<{sysTime} ORDER BY '.$order.' DESC LIMIT '.$start.','.$num;
			}
			if ($avs['minprice']||$avs['maxprice']){
				if ($avs['cat']){
					if (!in_array($avs['cat'],$content->cats)){
						$avs['cat']='news';
					}
				}else {
					$avs['cat']='news';
				}
				$where='C.cat=\''.$avs['cat'].'\' AND A.id=C.contentid';
				if ($avs['minprice']){
					$minprice=intval($avs['minprice'])/10000;
					$where.=' AND C.minprice>'.$minprice;
				}
				if ($avs['maxprice']){
					$maxprice=intval($avs['maxprice'])/10000;
					$where.=' AND C.minprice<'.$maxprice;
				}
				$where.=' AND A.time<{sysTime}';
				$sql='SELECT DISTINCT A.* FROM '.TABLE_PREFIX.'article A,'.AUTO_TABLE_PREFIX.'content C WHERE '.$where.' ORDER BY A.'.$order.' DESC LIMIT '.$start.','.$num;
			}
		}else {
			$sql=$avs['sqlStr'];
		}
		return $sql;
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
		if ($getCount){
			$sql='SELECT id,site,link,externallink,thumb,title,subtitle,intro,source,author,time,keywords,pagecount,content,autoname,autograde,autoid,viewcount FROM '.TABLE_PREFIX.'article WHERE '.$selectCondition.$exceptChannelSql.' AND time<{sysTime} ORDER BY '.$orderBy.' DESC LIMIT '.$start.','.$getCount;
		}else {
			$sql='SELECT id,site,link,externallink,thumb,title,subtitle,intro,source,author,time,keywords,pagecount,content,autoname,autograde,autoid,viewcount FROM '.TABLE_PREFIX.'article WHERE '.$selectCondition.$exceptChannelSql.' AND time<{sysTime} ORDER BY '.$orderBy.' DESC';
		}
		return $sql;
	}
}
?>