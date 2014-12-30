<?php
class tag_include extends tag {
	var $attributes;
	function __construct(){
		$this->attributes=array('file');
	}
	function parse($siteid,$str=''){//<stl:include></stl:include>
		$siteid=intval($siteid);
		$siteClass=bpBase::loadAppClass('siteObj','site',1);
		$thisSite=$siteClass->getSiteByID($siteid);
		$filePath=ABS_PATH.str_replace('@','',parent::getAttributeValue($str,$this->attributes[0]));
		$filePath=str_replace('{siteIndex}',$thisSite->siteindex,$filePath);
		if (file_exists($filePath)){
			return file_get_contents($filePath);
		}else {
			return '';
		}
	}
}
?>