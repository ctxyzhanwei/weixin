<?php
class tag_location extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'separator',
		'linkClass',
		'target'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		//properties
		$seperator=$avs['separator'];
		$linkClass=$avs['linkClass'];
		$target=$avs['target']==null?'_self':$avs['target'];
		//
		$channel=bpBase::loadAppClass('channelObj','channel',1);
		$crumbArr=$channel->crumbArr($channelID);
		$arrCount=count($crumbArr);
		//
		$site=bpBase::loadAppClass('siteObj','site',1);
		if ($siteID<100){
			$thisSite=$site->getSiteByID($siteID);
		}else {
			$special_db=bpBase::loadModel('special_model');
			$thisSpecial=$special_db->get($siteID);
			$thisSite->main=false;
			$thisSite->url=$thisSpecial['url'];
			$crumbArr[0]['name']='专题：'.$thisSpecial['name'];
		}
		$currentChannel=$channel->getChannelByID($channelID);
		$returnStr='';
		if (intval($thisSite->main)){
			$returnStr.='<a href="/" class="'.$linkClass.'" target="'.$target.'">'.$crumbArr[0]['name'].'</a>'.$seperator;
		}else {
			$returnStr.='<a href="'.$thisSite->url.'" class="'.$linkClass.'" target="'.$target.'">'.$crumbArr[0]['name'].'</a>'.$seperator;
		}
		for($i=1;$i<$arrCount;$i++){
			if (strlen($crumbArr[$i]['name'])){
				$returnStr.='<a href="'.$crumbArr[$i]['link'].'" class="'.$linkClass.'" target="'.$target.'">'.$crumbArr[$i]['name'].'</a>'.$seperator;
			}
		}
		$returnStr.='<a href="'.$currentChannel->link.'" class="'.$linkClass.'" target="'.$target.'">'.$currentChannel->name.'</a>';
		return $returnStr;
	}
}
?>