<?php
class tag_site extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'siteID',//
		'type',//eg. name
		'wordNum',
		'obj'//content object name
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		$siteID=$avs['siteID']==null?$siteID:$avs['siteID'];
		$site=bpBase::loadAppClass('siteObj','site',1);
		$thisSite=$site->getSiteByID($siteID);
		
		//
		$type=strtolower($avs['type']);
		return $thisSite->$type;
	}
}
?>