<?php
class tag_dynamicAd extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		'index',
		'ID',
		'type'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0){//<stl:***></stl:***>
		//properties
		$id=$avs['ID'];
		$sindex=$avs['index'];
		//
		$geoid=intval($avs['cityid']);
		if (!$site){
			$site=1;
		}
		//
		$adset_db=bpBase::loadModel('adset_model');
		if ($id){
			$thisAdSet=$adset_db->singleADSet($id);
		}elseif ($sindex) {
			$thisAdSet=$adset_db->singleADSetByIndex($sindex,$site);
			$id=$thisAdSet->set_id;
		}
		//
		$str='';
		$ad_db=bpBase::loadModel('ad_model');
		$ads=$ad_db->adsOfSet($id,1,$site,1,$geoid);
		switch ($avs['type']){
			case 'couplet':
				$bianju=26;//距离浏览器边的宽度
				$mtop=50;//上边距
				//左侧广告
				switch ($ads[0]->type){
					case 'flash':
						$str.='<div id="couplet_l" style="position:fixed;top:'.$mtop.'px;_position:absolute;left:'.$bianju.'px"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$ads[0]->width.'" height="'.$ads[0]->height.'"><param name="movie" value="'.$ads[0]->path.'"><param name="quality" value="high"><embed src="'.$ads[0]->path.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$ads[0]->width.'" height="'.$ads[0]->height.'"></embed></object><div style="cursor:pointer;margin:5px 0 0 0;text-align:right;" onclick="$(\'couplet_l\').dispose()">关闭</div></div>';
						break;
					case 'image':
						$str.='<div id="couplet_l" style="position:fixed;top:'.$mtop.'px;_position:absolute;left:'.$bianju.'px"><a href="'.$ads[0]->link.'" target="_blank"><img src="'.$ads[0]->path.'" width="'.$ads[0]->width.'" height="'.$ads[0]->height.'" border="0" /></a><div style="cursor:pointer;margin:5px 0 0 0;text-align:right;" onclick="$(\'couplet_l\').dispose()">关闭</div></div>';
						break;
				}
				//右侧广告
				switch ($ads[1]->type){
					case 'flash':
						$str.='<div id="couplet_r" style="position:fixed;top:'.$mtop.'px;_position:absolute;right:'.$bianju.'px"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$ads[1]->width.'" height="'.$ads[1]->height.'"><param name="movie" value="'.$ads[1]->path.'"><param name="quality" value="high"><embed src="'.$ads[1]->path.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$ads[1]->width.'" height="'.$ads[1]->height.'"></embed></object><div style="cursor:pointer;margin:5px 0 0 0;text-align:right;" onclick="$(\'couplet_r\').dispose()">关闭</div></div>';
						break;
					case 'image':
						$str.='<div id="couplet_r" style="position:fixed;top:'.$mtop.'px;_position:absolute;right:'.$bianju.'px"><a href="'.$ads[1]->link.'" target="_blank"><img src="'.$ads[1]->path.'" width="'.$ads[1]->width.'" height="'.$ads[1]->height.'" border="0" /></a><div style="cursor:pointer;margin:5px 0 0 0;text-align:right;" onclick="$(\'couplet_r\').dispose()">关闭</div></div>';
						break;
				}
				
				
				$str.='';
				break;
		}
		return $str;
	}
}
?>