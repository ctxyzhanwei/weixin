<?php
//文章页各分页导航
class tag_articlePageNav extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0,$pagination=array('pageSize'=>20,'totalCount'=>0,'currentPage'=>1,'urlPrefix'=>'','urlSuffix'=>'')){//<stl:***></stl:***>
		$articleObj=bpBase::loadAppClass('articleObj','article');
		$thisContent=$articleObj->getContentByID($contentID);
		if ($thisContent->pagecount>1){
			$currentPage=intval($pagination['currentPage']);
			if (!$thisContent->title){
				$sep='';
				for ($i=0;$i<$thisContent->pagecount;$i++){
					$thisContent->titles.=$sep.'';
					$sep='|';
				}
			}
			
			$titles=explode('|',$thisContent->titles);
			$str='';
			$ah_str_l='';
			$ah_str_r='';
			if ($titles){
				$i=0;
				foreach ($titles as $t){
					$nextI=$i+1;
					if ($i==0){
						$link=$pagination['urlPrefix'].$pagination['urlSuffix'];
					}else {
						$link=$pagination['urlPrefix'].'-'.$nextI.$pagination['urlSuffix'];
					}
					if ($pagination['currentPage']!=$nextI){
						$style='';
					}else {
						$style=' style="color:red"';
					}
					if (!isah()){
						$str.='<li><a title="'.$t.'" href="'.$link.'"'.$style.'>第'.$nextI.'页：'.$t.'</a></li>';
					}else {
						$s='<a title="'.$t.'" href="'.$link.'"'.$style.'>第'.$nextI.'页：'.$t.'</a><br>';
						if ($i%2==0){
							$ah_str_l.=$s;
						}else {
							$ah_str_r.=$s;
						}
						
					}
					$i++;
				}
			}
		}
		if ($titles){
		if (!isah()){
			return '<div class="contentTitleNav"><h2>“'.$thisContent->title.'”导航</h2><div id="titles"><ul>'.$str.'<div class="clear"></div></ul><div class="clear" style="width:100%;height:1px"></div></div></div>';
		}else {
			return '<dl class="article_nav"><dt>文章导航条</dt><dd>'.$ah_str_l.'</dd><dd class="last">'.$ah_str_r.'</dd></dl>';
		}
		}
	}
}
?>