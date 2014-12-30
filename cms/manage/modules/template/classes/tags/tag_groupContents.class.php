<?php
class tag_groupContents extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'totalNum',//how many contents fetched
		'startNum',//which to start
		'titleLen',
		'city',
		'id',//groupid
		'introLen',
		'dateFormat',
		'scope',
		'channelIndex'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$mobileConfig=loadConfig('mobile');
		$content_db=bpBase::loadModel('article_model');
		$avs['sql']=str_replace('{sysTime}',SYS_TIME,$avs['sql']);
		$contents=$content_db->get_resultsBySql($avs['sql']);
		$returnStr='';
		if ($contents){
			$middleStr=parent::getMiddleBody($str,'groupContents',$this->gTag);
			$tags=array('[stl.content.author]','[stl.content.source]','[stl.content.thumb]','[stl.content.content]');
			foreach ($contents as $c){
				$title=$c->title?$c->title:$c->atitle;
				$replaces=array($c->author,$c->source,$c->thumb,$c->content);
				$valueStr=str_replace($tags,$replaces,$middleStr);
				if ($avs['titleLen']){
					$valueStr=str_replace('[stl.content.title]',mb_substr($title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
					if (strlen($c->subtitle)){//判断是否有副标题
						$valueStr=str_replace('[stl.content.subtitle]',mb_substr($title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
					}else {
						$valueStr=str_replace('[stl.content.subtitle]',mb_substr($title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
					}
				}else {
					if (strlen($c->subtitle)){//判断是否有副标题
						$valueStr=str_replace(array('[stl.content.title]','[stl.content.subtitle]'),array($title,$title),$valueStr);
					}else {
						$valueStr=str_replace(array('[stl.content.title]','[stl.content.subtitle]'),array($title,$title),$valueStr);
					}
				}
				//other thumb
				$valueStr=str_replace('[stl.content.thumb2]',str_replace('.jpg','_small.jpg',$c->thumb),$valueStr);
				$valueStr=str_replace('[stl.content.thumb3]',str_replace('.jpg','_middle.jpg',$c->thumb),$valueStr);
				$valueStr=str_replace('[stl.content.thumb4]',str_replace('.jpg','_big.jpg',$c->thumb),$valueStr);
				//
				if ($avs['introLen']){
					$valueStr=str_replace('[stl.content.intro]',mb_substr($c->intro,0,$avs['introLen'],DB_CHARSET),$valueStr);
				}else {
					$valueStr=str_replace('[stl.content.intro]',$c->intro,$valueStr);
				}
				$valueStr=str_replace('[stl.content.link]',$c->link,$valueStr);
				$valueStr=str_replace('[stl.content.fullTitle]',$c->atitle,$valueStr);
				$valueStr=str_replace('[stl.content.oTitle]',$c->atitle,$valueStr);
				$valueStr=str_replace('[stl.content.oSubTitle]',$c->asubtitle,$valueStr);
				$valueStr=str_replace('[stl.fullTitle]',$c->atitle,$valueStr);
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

				if ($firstAutoid){
					$autoclassification_db=bpBase::loadModel('autoclassification_model');
					$firstAuto=$autoclassification_db->getCfByID($firstAutoid);
					if ($firstAuto){
						$valueStr=str_replace('[stl.content.autoName]',$firstAuto->name,$valueStr);
						if ($firstAuto->grade==3){
							$valueStr=str_replace('[stl.content.autoAdvantage]',$firstAuto->advantage,$valueStr);
							$valueStr=str_replace('[stl.content.autoDefect]',$firstAuto->defect,$valueStr);
						}
					}
				}else {
					$valueStr=str_replace('[stl.content.autoName]','',$valueStr);
					$valueStr=str_replace('[stl.content.autoAdvantage]','',$valueStr);
					$valueStr=str_replace('[stl.content.autoDefect]','',$valueStr);
				}
				//
				$returnStr.=$valueStr;
			}
		}
		return $returnStr;
	}
	function getSql($avs,$siteID){
		//properties
		$num=intval($avs['totalNum']);
		$start=$avs['startNum']==null?0:intval($avs['startNum'])-1;
		$channelSql='';
		if ($avs['channelIndex']){
			$channelObj=bpBase::loadAppClass('channelObj','channel',1);
			$thisChannel=$channelObj->getChannelByIndex($avs['channelIndex'],1);
			$channelids=array();
			if (intval($thisChannel->id)){
				$channelSql=' AND A.channel_id='.$thisChannel->id;
				array_push($channelids,$thisChannel->id);
				if ($avs['scope']&&$avs['scope']=='children'){
					$channels=$channelObj->getChannelsByParentID($thisChannel->id);
					if ($channels){
						foreach ($channels as $c){
							array_push($channelids,$c->id);
						}
					}
				}
				$channelSql=' AND '.to_sqls($channelids,'','A.channel_id');
			}
			//
		}
		if (!intval($avs['city'])){
			return 'SELECT A.autoid,A.id,A.site,A.link,A.externallink,A.thumb,CC.title,CC.title AS subtitle,A.title AS atitle,A.subtitle AS asubtitle,A.intro,A.source,A.author,A.time,A.keywords,A.pagecount FROM '.TABLE_PREFIX.'contentgroup_content CC,'.TABLE_PREFIX.'article A WHERE groupid='.$avs['id'].' AND A.id=CC.contentid'.$channelSql.' AND A.time<{sysTime} ORDER BY CC.taxis DESC LIMIT '.$start.','.$num;
		}else {
			return 'SELECT A.autoid,A.id,A.site,A.link,A.externallink,A.thumb,CC.title,CC.title AS subtitle,A.title AS atitle,A.subtitle AS asubtitle,A.intro,A.source,A.author,A.time,A.keywords,A.pagecount FROM '.TABLE_PREFIX.'contentgroup_content CC,'.TABLE_PREFIX.'article A WHERE groupid='.$avs['id'].' AND CC.geoid='.intval($avs['city']).' AND A.id=CC.contentid'.$channelSql.' AND A.time<{sysTime} ORDER BY CC.taxis DESC LIMIT '.$start.','.$num;
		}
	}
}
?>