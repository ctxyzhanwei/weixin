<?php
class tag_articleRanks extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'cat',
		'totalNum',//how many contents fetched
		'startNum',//which to start
		'dateFormat',
		'numStart',
		'orderBy'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$csiteID=0,$thisChannelID=0,$contentID=0){//<stl:***></stl:***>
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$site=bpBase::loadAppClass('siteObj','site');
		$content_db=bpBase::loadModel('article_model');
		$totalNum=$avs['totalNum']?$avs['totalNum']:10;
		$cat=$avs['cat']?$avs['cat']:'news';
		$cats=array('video','news','guide','comment','market');
		if (!in_array($cat,$cats)){
			$cat='news';
		}
		$startNum=$avs['startNum']?intval($avs['startNum']):1;
		$startI=$startNum-1;
		$totalNum=$startI+$totalNum;
		$orderBy=$avs['orderBy']?$avs['orderBy']:'viewcount';
		$contents=$articleObj->viewRanksByCat($cat,$totalNum,$orderBy);
		
		
		$returnStr='';
		if ($contents){
			$i=0;
			$middleStr=parent::getMiddleBody($str,'articleRanks',$this->gTag);
			$tags=array('[stl.content.link]','[stl.content.title]','[stl.content.thumb]');
			$count=0;
			foreach ($contents as $c){
				if ($i>$startI-1&&$count<$totalNum){
					$replaces=array($c->link,$c->title,$c->thumb);
					$valueStr=str_replace($tags,$replaces,$middleStr);
					//time
					$valueStr=str_replace('[stl.content.time]',date($avs['dateFormat'],$c->time),$valueStr);
					//other thumb
					$valueStr=str_replace('[stl.content.thumb2]',str_replace('.jpg','_small.jpg',$c->thumb),$valueStr);
					$valueStr=str_replace('[stl.content.thumb3]',str_replace('.jpg','_middle.jpg',$c->thumb),$valueStr);
					$valueStr=str_replace('[stl.content.thumb4]',str_replace('.jpg','_big.jpg',$c->thumb),$valueStr);
					//num
					$valueStr=str_replace('[stl.content.num]',$count+$startNum,$valueStr);
					//
					$returnStr.=$valueStr;
					$count++;
				}
				$i++;
			}
		}
		return $returnStr;
	}
}
?>