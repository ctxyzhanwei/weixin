<?php
class tag_channels extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'site',//which site,default this site
		'totalNum',//
		'startNum',//which to start
		'upLevel',
		'numStart',
		'currentItemClass',
		'channelIndex'//which channel,default this channel
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		$channelObj=bpBase::loadAppClass('channelObj','channel',1);
		//
		$siteID=$avs['site']==null?$siteID:$avs['site'];
		//
		$upLevel=$avs['upLevel']==null?0:intval($avs['upLevel']);
		if ($avs['channelIndex']){
			$thisChannel=$channelObj->getChannelByIndex($avs['channelIndex'],$siteID);
			$channels=$channelObj->getChannelsByParentID($thisChannel->id);
		}else {
			switch ($upLevel){
				case 0:
					break;
				case 1:
					$currentChannel=$channelObj->getChannelByIndex($avs['channelIndex'],$siteID);
					$channels=$channelObj->getChannelsByParentID($currentChannel->parentid);
					break;
			}
		}
		//
		$returnStr='';
		if ($channels){
			$middleStr=parent::getMiddleBody($str,'channels',$this->gTag);
			$i=0;
			foreach ($channels as $c){
				$start=intval($avs['startNum'])-1;
				$count=intval($avs['totalNum']);
				if (!$count){
					$count=count($channels);
				}
				
				if ($i==$start||$i>$start){
					if ($i<$count){
						$rs=str_replace(array('[stl.channel.id]','[stl.channel.name]','[stl.channel.link]','[stl.channel.num]','<stl:contents'),array($c->id,$c->name,$c->link,$i+intval($avs['numStart']),'<stl:contents channelIndex="'.$c->cindex.'"'),$middleStr);
						//current class
						if ($channelID==$c->id){
							$rs=str_replace('[stl.channel.currentItemClass]',$avs['currentItemClass'],$rs);
						}else {
							$rs=str_replace('[stl.channel.currentItemClass]','',$rs);
						}
						$returnStr.=$rs;
					}
				}
				
				$i++;
			}
		}
		//处理stl:contents
		if (strExists($returnStr,'<stl:contents')){
			$template=bpBase::loadAppClass('template','template');
			$now=SYS_TIME;
			$returnStr=$template->parseStr($returnStr,$now);
			@unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$now.'.parsed.tpl.php');
			@unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$now.'.tags.tpl.php');
		}
		
		//
		return $returnStr;
	}
}
?>