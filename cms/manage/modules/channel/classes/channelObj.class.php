<?php
class channelObj {
	function __construct(){
		$this->channel_db = bpBase::loadModel('channel_model');
	}
	function getChannelByID($id){
		$id=intval($id);
		$crt=getCache('channel'.$id);
		if ($crt){
			return unserialize($crt);
		}else {
			$channel=$this->channel_db->get_row(array('id'=>$id));
			setZendCache(serialize($channel),'channel'.$id);
			return $channel;
		}
	}
	function getChannelByIndex($index,$site=1){
		$index=str_replace(' ','',$index);
		$index=htmlspecialchars(trim($index),ENT_QUOTES);
		$site=intval($site);
		$crt=getCache('channelOfIndex'.$index.'Site'.$site);
		if ($crt){
			return unserialize($crt);
		}else {
			$channel=$this->channel_db->get_row(array('cindex'=>$index,'site'=>$site));
			setZendCache(serialize($channel),'channelOfIndex'.$index.'Site'.$site);
			return $channel;
		}
	}
	function getChannelsByParentID($parentid,$output='OBJECT'){
		$parentid=intval($parentid);
		$crt=getCache('channelsOf'.$parentid.'o'.$output);
		if ($crt){
			return unserialize($crt);
		}else {
			$channels=$this->channel_db->get_results('*','','parentid='.$parentid,'taxis ASC');
			setZendCache(serialize($channels),'channelsOf'.$parentid.'o'.$output);
			return $channels;
		}
	}
	function crumbArr($id){
		$thisID=intval($id);
		$crt=getCache('channelCrumb'.$thisID);
		if ($crt){
			return unserialize($crt);
		}else {
			$channelArr=array();
			do {
				//获取父栏目
				$channelA=$this->channel_db->get_row(array('id'=>$id));
				$channel=$this->channel_db->get_row(array('id'=>$channelA->parentid));
				$id=intval($channel->id);
				$parentID=intval($channel->parentid);
				$channelArr[]=array('id'=>$id,'parentid'=>$channel->parentid,'name'=>$channel->name,'cindex'=>$channel->cindex,'link'=>$channel->link);
			}while ($parentID!=0);
			$channelArr=array_reverse($channelArr);
			setZendCache(serialize($channelArr),'channelCrumb'.$thisID);
			return $channelArr;
		}
	}
	public function updateLink($id,$link){
		$thisChannel=$this->getChannelByID($id);
		if (!intval($thisChannel->externallink)){
			$id=intval($id);
			$rt=$this->channel_db->update(array('link'=>$link),array('id'=>$id));
			if ($rt){
				$this->removeCrumbCache($id);
				delCache('channelsOf'.$thisChannel->parentid.'oOBJECT');
				delCache('channelsOf'.$thisChannel->parentid.'oARRAY_A');
				delCache('channel'.$id);
				delCache('channelOfIndex'.$thisChannel->cindex.'Site'.$thisChannel->site);
			}
			return $rt;
		}else {
			return 0;
		}
	}
	public function updateLastCreateTime($id){
		$id=intval($id);
		$rt=$this->channel_db->update(array('lastcreate'=>time()),array('id'=>$id));
		return $rt;
	}
	public function removeCrumbCache($id){
		$children=$this->getChannelsByParentID($id);
		delCache('channelCrumb'.$id);
		if ($children){
			foreach ($children as $c){
				delCache('channelCrumb'.$c->id);
			}
		}
	}
	public function channelCreatePageTree($array, $array_index=0,$manageChannels=array(),$showChannels=array()){
		global $array_tree;
		$display='';
		for ($i=0;$i<$array_index;$i++){
			$display .= '│';
		}
		if(gettype($array)=="array"){
			$array_index++;
			foreach ($array as $a){
				if (!$showChannels || in_array($a['id'],$showChannels)){//不是显示所有栏目
					if (end($array)==$a){
						$array_tree .= '<option value="'.$a['id'].'">'.$display.'└'.$a['name'].'</option>';
					}else {
						$array_tree .= '<option value="'.$a['id'].'">'.$display.'├'.$a['name'].'</option>';
					}
				}
				if (is_array($a['children'])){
					$this->channelCreatePageTree($a['children'], $array_index,$manageChannels,$showChannels);
				}

			}
		}
		return $array_tree;
	}
	public function tree($pId,$siteID=1){
		$pId=intval($pId);
		$siteID=intval($siteID);
		$data=$this->channel_db->select(array('site'=>$siteID));
		$tree = '';
		if ($data){
			foreach($data as $k => $v){
				if($v['parentid'] == $pId){
					$v['children'] = $this->tree($v['id'],$siteID);
					$tree[] = $v;
				}
			}
		}
		return $tree;
	}
	/**
	 * 根据模板中的pageContents标签获取每页的数量值，仅生成静态页时有效
	 *
	 * @param unknown_type $channelID
	 * @return unknown
	 */
	public function getChannelTotalPage($channelID){
		$tpl=bpBase::loadAppClass('template','template',1);
		//channel
		$channelID=intval($channelID);
		$thisChannel=$this->getChannelByID($channelID);
		//分析模板
		$template=$tpl->get($thisChannel->channeltemplate);
		if (!file_exists(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.$template->id.'.parsed.tpl.php')){
			$tpl->createChannelPageR($channelid);
		}
		//只有生成静态页的情况下，下面的代码才有效
		$totalPage=1;
		if (file_exists(ABS_PATH.'/templatesCache/'.$template->id.'.tags.tpl.php')){
			@require(ABS_PATH.'/templatesCache/'.$template->id.'.tags.tpl.php');
			$pageSize=20;
			//判断是否有分页标签
			$isPagination=false;
			if ($tagsArr){
				foreach ($tagsArr as $t){
					if ($t['name']=='pageContents'){
						//
						$isPagination=true;
						$pageSize=$t['avs']['pageNum'];
					}
				}
			}
			if ($isPagination){
				$childChannels=$this->getChannelsByParentID($channelID);
				$channelidArr=array($channelID);
				if ($childChannels){
					foreach ($childChannels as $c){
						array_push($channelidArr,$c->id);
					}
				}
				$article_db = bpBase::loadModel('article_model');
				$where = to_sqls($channelidArr, '', 'channel_id');
				$totalCount=$article_db->get_var($where,'COUNT(id)','','');
				$totalPage=$totalCount%$pageSize>0?intval($totalCount/$pageSize)+1:$totalCount/$pageSize;
				$totalPage=$totalPage<1?1:$totalPage;
			}else {
				$totalPage=1;
			}
		}
		return $totalPage;
	}
	public function clearCrumbCache($id){
		delCache('channelCrumb'.$id);
		delCache('channelsOf'.$id.'oOBJECT');
		delCache('channelsOf'.$id.'oARRAY');
		delCache('channel'.$id);
		$thisChannel=$this->getChannelByID($id);
		delCache('channelOfIndex'.$thisChannel->cindex.'Site'.$thisChannel->site);
		delCache('channelsOf'.$thisChannel->parentid.'oOBJECT');
		delCache('channelsOf'.$id.'oOBJECT');
	}
	function allDescentChannels($id){
		$id=intval($id);
		$thisChannel=$this->getChannelByID($id);
		
		$descent=array();
		$children=$this->getChannelsByParentID($id);
		if ($children){
			$hasChild=1;
			foreach ($children as $c){
				array_push($descent,$c);
				$grandsons=$this->getChannelsByParentID($c->id);
				if ($grandsons){
					foreach ($grandsons as $gs){
						array_push($descent,$gs);
						$greatGrandsons=$this->getChannelsByParentID($gs->id);
						if ($greatGrandsons){
							foreach ($greatGrandsons as $ggs){
								array_push($descent,$ggs);
							}
						}
					}
				}
			}
		}
		return $descent;
	}
	function channelTree($array, $array_index=0, $type='content',$manageChannels=array(),$showChannels=array()){
		global $array_tree;
		$leftPadding = ($array_index+1)*16;
		if(gettype($array)=="array"){
			$array_index++;
			
			foreach ($array as $a){
				//判断是否显示链接地址
				if (!$showChannels || in_array($a['id'],$showChannels)){//不是显示所有栏目
					if (is_array($a['children'])){
						$prefix='<img src="image/plus.gif" align="absmiddle" rel="divTog_'.$a['id'].'" class="tog" />';
					}else {
						$prefix='<img src="image/empty.gif" align="absmiddle" />';
					}

					if (!$showChannels || in_array($a['id'],$manageChannels)){
						if ($type=='content'){
							$link='?m=article&c=m_article&a=articles&id='.$a['id'].'&site='.$a['site'];
						}else{
							$link='?m=channel&c=m_channel&a=channels&id='.$a['id'].'&siteid='.$a['site'];
						}
						$array_tree .= '<div style="padding:0 0 0 '.$leftPadding.'px;"><nobr>'.$prefix.'<img src="image/folder.gif" align="absmiddle"></img> <a href="'.$link.'" target="sright">'.$a['name'].'</a></nobr></div>';
					}else {
						$array_tree .= '<div style="padding:0 0 0 '.$leftPadding.'px;"><nobr>'.$prefix.'<img src="image/folder.gif" align="absmiddle"></img> '.$a['name'].'</nobr></div>';
					}
					if (is_array($a['children'])){
						$array_tree .= '<div id="divTog_'.$a['id'].'" style="padding:0;margin:0;display:none;">';
						$this->channelTree($a['children'], $array_index,$type,$manageChannels,$showChannels);
						$array_tree .= '</div>';
					}
				}
			}
		}
		return $array_tree;
	}
}