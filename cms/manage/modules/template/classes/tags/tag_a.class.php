<?php
class tag_a extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'siteID',
		'site',
		'channelIndex',
		'contentID',
		'obj'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		$str=parent::removeProperties($str,$this->attributes);
		$middleStr=parent::getMiddleBody($str,'a',$this->gTag);
		if (isset($avs['contentID'])&&$avs['contentID']){
			$articleObj=bpBase::loadAppClass('articleObj','article',1);
			$thisContent=$articleObj->getContentByID($avs['contentID']);
			$valueStr=str_replace('[stl.content.title]',$thisContent->title,$middleStr);
			$link=$thisContent->link;
		}elseif (isset($avs['channelIndex'])&&$avs['channelIndex']){
			$channelObj=bpBase::loadAppClass('channelObj','channel',1);
			if ($avs['site']){//指定了站点
				$siteID=intval($avs['site']);
			}
			$thisChannel=$channelObj->getChannelByIndex($avs['channelIndex'],$siteID);
			//
			$valueStr=str_replace('[stl.channel.name]',$thisChannel->name,$middleStr);
			
			if ($avs['site']||$siteID>0){//指定了站点
				$siteObj=bpBase::loadAppClass('siteObj','site',1);
				$thisSite=$siteObj->getSiteByID($avs['site']);
				if (strExists($link,'http://')||$thisChannel->externallink){
					$link=$thisChannel->link;
				}else {
					$link=$thisSite->url.$thisChannel->link;
				}
				
			}else {
				if (strExists($link,'http://')||$thisChannel->externallink){
					$link=$thisChannel->link;
				}else {
					$link=MAIN_URL_ROOT.$thisChannel->link;
				}
				
			}
		}elseif (isset($avs['siteID'])&&$avs['siteID']){
			$siteObj=bpBase::loadAppClass('siteObj','site',1);
			$thisSite=$siteObj->getSiteByID($avs['siteID']);
			//
			$valueStr=str_replace('[stl.site.name]',$thisSite->name,$middleStr);
			$link=$thisSite->url;
		}
		$str=str_replace('<stl:a','<a href="'.$link.'"',$str);
		$str=str_replace('</stl:a','</a',$str);
		$str=str_replace($middleStr,$valueStr,$str);
		return $str;
	}
}
?>