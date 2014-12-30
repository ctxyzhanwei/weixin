<?php
class tag_pageItems extends tag {
	var $attributes;
	var $gTag;
	function __construct($globalTag='stl'){
		$this->attributes=array(
		 'firstPageText',
		 'previousPageText',
		 'nextPageText',
		 'lastPageText',
		 'currentPageText',
		 'type',
		 'pageNum',
		 'inactiveLinkClass',
		 'activeLinkClass'
		);
		$this->gTag=$globalTag;
	}
	function getValue($str='',$avs,$siteID=0,$channelID=0,$contentID=0,$pagination=array('pageSize'=>20,'totalCount'=>0,'currentPage'=>1,'urlPrefix'=>'','urlSuffix'=>'')){//<stl:***></stl:***>
		$middleStr=parent::getMiddleBody($str,'pageItems',$this->gTag);
		//
		if (!$avs['type']){
			$type='channel';
		}else {
			$type=$avs['type'];
		}
		$pageSize=intval($pagination['pageSize']);
		$currentPage=intval($pagination['currentPage']);
		$totalCount=intval($pagination['totalCount']);
		$totalPage=$totalCount%$pageSize>0?intval($totalCount/$pageSize)+1:$totalCount/$pageSize;
		$returnStr='';
		if ($totalPage>1){
			
			$returnStr=$middleStr;
			$nextPage=$currentPage+1;
			$previousPage=$currentPage-1;
			if ($currentPage==1){
				$firstPageLink='';
				$previousPageLink='';
				$nextPageLink='<a href="'.$pagination['urlPrefix'].'-'.$nextPage.$pagination['urlSuffix'].'">'.$avs['nextPageText'].'</a>';
				$lastPageLink='<a href="'.$pagination['urlPrefix'].'-'.$totalPage.$pagination['urlSuffix'].'">'.$avs['lastPageText'].'</a>';
			}elseif ($currentPage==$totalPage){
				$firstPageLink='<a href="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'">'.$avs['firstPageText'].'</a>';
				$previousPageLink='<a href="'.$pagination['urlPrefix'].'-'.$previousPage.$pagination['urlSuffix'].'">'.$avs['previousPageText'].'</a>';
				$nextPageLink='';
				$lastPageLink='';
			}else {
				$firstPageLink='<a href="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'">'.$avs['firstPageText'].'</a>';
				$previousPageLink='<a href="'.$pagination['urlPrefix'].'-'.$previousPage.$pagination['urlSuffix'].'">'.$avs['previousPageText'].'</a>';
				$nextPageLink='<a href="'.$pagination['urlPrefix'].'-'.$nextPage.$pagination['urlSuffix'].'">'.$avs['nextPageText'].'</a>';
				$lastPageLink='<a href="'.$pagination['urlPrefix'].'-'.$totalPage.$pagination['urlSuffix'].'">'.$avs['lastPageText'].'</a>';
			}
			if ($currentPage==2){
				$previousPageLink='<a href="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'">'.$avs['previousPageText'].'</a>';
			}
			//
			$pageSelect='<script language="JavaScript">function PageSelect_1_jumpMenu(targ,selObj,restore){eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");if (restore) selObj.selectedIndex=0;}</script><select name="PageSelect_1" id="PageSelect_1" onchange="PageSelect_1_jumpMenu(\'self\',this,0)">';

			$pageNavigation='';
			for ($i=1;$i<$totalPage+1;$i++){
				//
				if ($i==$currentPage){
					$linkClass=$avs['inactiveLinkClass'];
				}else {
					$linkClass=$avs['activeLinkClass'];
				}
				if ($currentPage!=$i){
					$selected='';
				}else {
					$selected=' selected';
				}
				if ($i!=1){
					$pageNavigation.='<a href="'.$pagination['urlPrefix'].'-'.$i.$pagination['urlSuffix'].'" class="'.$linkClass.'">'.$i.'</a>';
					$pageSelect.='<option value="'.$pagination['urlPrefix'].'-'.$i.$pagination['urlSuffix'].'"'.$selected.'>'.$i.'</option>';
				}else {
					$pageNavigation.='<a href="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'" class="'.$linkClass.'">'.$i.'</a>';
					$pageSelect.='<option value="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'"'.$selected.'>'.$i.'</option>';
				}
				//
				
			}
			$pageSelect.='</select>';
			//
			if ($currentPage!=$totalPage){
				$nextI=$currentPage+1;
				$pageNavigation.='<a href="'.$pagination['urlPrefix'].'-'.$nextI.$pagination['urlSuffix'].'" class="'.$linkClass.'">下一页</a>';
			}
			if ($currentPage!=1){
				if ($currentPage!=2){
					$previousI=$currentPage-1;
					$pageNavigation='<a href="'.$pagination['urlPrefix'].'-'.$previousI.$pagination['urlSuffix'].'" class="'.$avs['activeLinkClass'].'">上一页</a>'.$pageNavigation;
				}else {
					$pageNavigation='<a href="'.$pagination['urlPrefix'].$pagination['urlSuffix'].'" class="'.$avs['activeLinkClass'].'">上一页</a>'.$pageNavigation;
				}
			}
			//
			$returnStr=str_replace(array('[stl.pagination.firstPage]','[stl.pagination.previousPage]','[stl.pagination.nextPage]','[stl.pagination.lastPage]','[stl.pagination.currentPage]','[stl.pagination.totalPage]','[stl.pagination.pageSelect]','[stl.pagination.pageNavigation]'),array($firstPageLink,$previousPageLink,$nextPageLink,$lastPageLink,$currentPage,$totalPage,$pageSelect,$pageNavigation),$middleStr);
		}
		return $returnStr;
	}
}
?>