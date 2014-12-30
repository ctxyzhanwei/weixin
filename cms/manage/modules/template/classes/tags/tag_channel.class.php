<?php
class tag_channel extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'site',//
		'channelIndex',//
		'type',//eg. title
		'wordNum',
		'upLevel',
		'obj'//content object name
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		//
		$site=$avs['site']==null?$siteID:$avs['site'];
		$site=intval($site);
		//instance
		$channelObj=bpBase::loadAppClass('channelObj','channel');
		$upLevel=$avs['upLevel']==null?0:intval($avs['upLevel']);
		if ($avs['channelIndex']!=null){
			$thisChannel=$channelObj->getChannelByIndex($avs['channelIndex'],$site);
		}else {
			switch ($upLevel){
				case 0:
					$thisChannel=$channelObj->getChannelByID($channelID);
					break;
				case 1:
					$currentChannel=$channelObj->getChannelByID($channelID);
					$thisChannel=$channelObj->getChannelByID($currentChannel->parentid);
					break;
			}
		}
		//
		$type=strtolower($avs['type']);
		if ($type=='title'){
			$type='name';
		}
		if ($type=='content'){
			$type='des';
		}
		if ($type=='imageurl'){
			$type='thumb';
		}
		if ($type){
			return $thisChannel->$type;
		}else {
			return $thisChannel->name;
		}
	}
}
?>