<?php
class tag_pageContents extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'channelIndex',//which channel,default this channel
		'pageNum',//
		'scope',//
		'order',//taxis
		'site',//
		'seperatorCount',//seperator contents
		'dateFormat',
		'isImage'//if content only has thumb
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0,$pagination=array()){//<stl:***></stl:***>
		$scope=$avs['scope']==null?'self':'children';
		if ($avs['isImage']==null){
			$avs['isImage']='null';
		}else{
			$avs['isImage']=$avs['isImage']=='true'?1:0;
		}
		switch ($avs['order']){
			default:
				$order='taxis';
				break;
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
		
		$num=$avs['pageNum'];
		if (!intval($num)){
			$num=20;
		}
		//
		$content=bpBase::loadAppClass('articleObj','article',1);
		$sepratorCount=intval($avs['seperatorCount']);
		$totalCount=$pagination['totalCount'];
		$pageSize=intval($num);
		$currentPage=$pagination['currentPage'];
		$start=($currentPage-1)*$pageSize;
		$contents=$content->getContentsByChannelID($channelID,$pageSize,$order,$start,$scope,$isImage);
		$returnStr='';
		if ($contents){
			$middleStr=parent::getMiddleBody($str,'pageContents',$this->gTag);
			$tags=array('[stl.fullTitle]','[stl.content.author]','[stl.content.source]','[stl.content.thumb]','[stl.content.content]');
			$i=0;
			foreach ($contents as $c){
				$replaces=array($c->title,$c->author,$c->source,$c->thumb,$c->content);
				$valueStr=str_replace($tags,$replaces,$middleStr);
				if ($avs['titleLen']){
					$valueStr=str_replace('[stl.content.title]',mb_substr($c->title,0,$avs['titleLen'],DB_CHARSET),$valueStr);
					$valueStr=str_replace('[stl.content.subtitle]',mb_substr($c->subtitle,0,$avs['titleLen'],DB_CHARSET),$valueStr);
				}else {
					$valueStr=str_replace(array('[stl.content.title]','[stl.content.subtitle]'),array($c->title,$c->subtitle),$valueStr);
				}
				if ($avs['introLen']){
					$valueStr=str_replace('[stl.content.intro]',mb_substr($c->intro,0,$avs['introLen'],DB_CHARSET),$valueStr);
				}else {
					$valueStr=str_replace('[stl.content.intro]',$c->intro,$valueStr);
				}
				if ($avs['absPath']){
					$site=bpBase::loadAppClass('siteObj','site',1);
					$thisSite=$site->getSiteByID($siteID);
					$valueStr=str_replace('[stl.content.link]',$thisSite->url.$c->link,$valueStr);
				}else {
					$valueStr=str_replace('[stl.content.link]',$c->link,$valueStr);
				}
				//time
				$valueStr=str_replace('[stl.content.time]',date($avs['dateFormat'],$c->time),$valueStr);
				$returnStr.=$valueStr;
				//seperator
				$i++;
				if($sepratorCount&&$i%$sepratorCount==0&&$i!=$pageSize){
					$returnStr.='<div class="pageContentsSeperator"></div>';
				}
			}
		}
		return $returnStr;
	}
}
?>