<?php
class tag_content extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'ID',//content id
		'type',//eg. title
		'wordNum',
		'obj',//content object name
		'formatString'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0,$pagination=array(),$thisContent=null){//<stl:***></stl:***>
		//type
		$type=$avs['type']==null?'title':strtolower($avs['type']);
		if ($type=='imageurl'){
			$type='thumb';
		}elseif ($type=='adddate'){
			$type='time';
		}elseif ($type=='summary'){
			$type='intro';
		}
		$contentID=$avs['ID']?$avs['ID']:$contentID;
		$articleObj=bpBase::loadAppClass('articleObj','article');
		//instance
		if (intval($avs['ID'])||intval($avs['id'])){
			if (!intval($avs['ID'])){
				$contentID=intval($avs['id']);
			}
			$thisContent=$articleObj->getContentByID($contentID);
		}
		if (!$thisContent){
			$thisContent=$articleObj->getContentByID($contentID);
		}
		
		//
		$autoids=explode(',',$thisContent->autoid);
		$firstAutoid=0;
		if ($autoids){
			foreach ($autoids as $autoid){
				if (intval($autoid)){
					$firstAutoid=$autoid;
					break;
				}
			}
		}
		$autoclassification_db=bpBase::loadModel('autoclassification_model');
		$autoObj=bpBase::loadAppClass('autoObj','auto');
		$firstAuto=$autoclassification_db->getCfByID($firstAutoid);
		//
		switch ($type){
			default:
				return $thisContent->$type;
				break;
			case 'keywords':
				return substr($thisContent->keywords,1);
				break;
			case 'content':
				$thisContent->$type=stripslashes($thisContent->$type);
				$stag=bpBase::loadAppClass('stag','template');
				$thisContent->$type=$stag->handleStag($thisContent->$type);
				return $thisContent->$type;
				break;
			case 'sourcetypeValue':
				if ($thisContent->sourcetype){
				$articleConstant=bpBase::loadAppClass('articleConstant','article');
				$sourceTypes=$articleConstant->sourceTypes();
				return $sourceTypes[$thisContent->sourcetype];
				}else{
					return '-';
				}
				break;
			case 'time':
				return date($avs['formatString'],$thisContent->time);
				break;
			case 'autotabs':
				if ($thisContent->autograde){
					//文章类别
					$cat=$articleObj->getArticleCat($thisContent->id);
					if ($cat=='other'||$cat==''){
						$cat='none';
					}
					
					$smallLogo=$autoObj->getLogo($firstAuto->id,$firstAuto->logo,'s',$firstAuto->grade);
					if ($thisContent->autograde==3){
						$parentAuto=$autoclassification_db->getCfByID($firstAuto->parentid);
						if (!function_exists('autoTabs')){
							include_once(ABS_PATH.CAR_DIR.DIRECTORY_SEPARATOR.'include.php');
						}
						$autoTabs=autoTabs($cat,$firstAutoid,$firstAutoid,3,$firstAuto);
						if (AUTO_SKIN!='ahauto'){
							return '<div class="g3nautonav"><div class="bnav_t"><a href="'.CAR_URL_ROOT.'/library_brand.html" class="twpp_morea">车型大全>></a><div class="tit"><h1><a href="'.CAR_URL_ROOT.'/'.$firstAutoid.'" class="twpp_tia">'.$parentAuto->name.' '.$firstAuto->name.'</a></h1>&nbsp; <a href="'.STORE_URL_ROOT.'/stores.php?autoid='.$firstAutoid.'" target="_blank">'.$firstAuto->name.'经销商</a></div></div></div><div class="childChannelNav" style="margin-bottom:8px">'.$autoTabs.'</div>';
						}else {
							return '<div id="newcar_title_01" style="margin-bottom:10px"><span><ul>{$autoTabs}</ul></span><b>'.$parentAuto->name.' '.$firstAuto->name.'</b></div>';
						}
					}elseif ($thisContent->autograde==1) {
						if (!function_exists('brandTabs')){
							include_once(ABS_PATH.CAR_DIR.DIRECTORY_SEPARATOR.'include.php');
						}
						if (AUTO_SKIN!='ahauto'){
							$autoTabs=brandTabs($cat,$firstAuto->id,$firstAuto);
							return '<div class="twpp_bnav" style="margin-bottom:8px"><div class="bnav_t"><a href="'.CAR_URL_ROOT.'/library_brand.html" class="twpp_morea">品牌大全>></a><div class="tit"><img src="'.$smallLogo.'" width="40" height="30" /><h1><a href="'.CAR_URL_ROOT.'/brand-'.$firstAutoid.'.html" class="twpp_tia">'.$firstAuto->name.'</a></h1>    <a href="'.CAR_URL_ROOT.'/price/'.$firstAutoid.'.html" title="'.$firstAuto->name.'车系" target="_blank">车系</a>    <a href="'.CAR_URL_ROOT.'/picture/'.$firstAutoid.'.html" title="'.$firstAuto->name.'图片">图片</a>    <a href="'.STORE_URL_ROOT.'/stores.php?autoid='.$firstAutoid.'"  title="'.$firstAuto->name.'4S店" target="_blank">经销商</a></div></div><div class="bnav_b">'.$autoTabs.'</div></div>';
						}else {
							return '<div class="btop contentt" style="margin-bottom:10px"><img src="'.$smallLogo.'" width="40" height="30" /><h1>'.$firstAuto->name.'</h1></div>';
						}
					}
				}
				break;
			case 'relateautoinfo':
				$articlePage=bpBase::loadAppClass('articlePage','article');
				$str=$articlePage->relateAutosInContentPage($thisContent->id,$thisContent);
				$strs=explode('<span style="display:none" id="relateAutoStores">,',$str);
				return $strs[0];
				break;
			case 'autoid':
				return $firstAutoid;
				break;
			case 'serieid':
				if ($thisContent->autograde){
					if ($thisContent->autograde==3){
						return $firstAutoid;
					}elseif ($thisContent->autograde==1){
						return 0;
					}
				}
				break;
			case 'brandid':
				if ($thisContent->autograde){
					if ($thisContent->autograde==3){
						return $firstAuto->g1id;
					}elseif ($thisContent->autograde==1){
						return $firstAutoid;
					}
				}
				break;
			case 'autoname':
				return $thisContent->autoname;
				break;
		}
	}
}
?>