<?php
bpBase::loadAppClass('manage','manage',0);
class m_channel extends manage {
	public $channel_db;
	public $channelObj;
	function __construct() {
		parent::__construct();
		$this->exitWithoutAccess();
		$this->channel_db=bpBase::loadModel('channel_model');
		$this->channelObj=bpBase::loadAppClass('channelObj','channel');
		if (isset($_GET['siteid'])&&$_GET['siteid']!=$this->siteid){
			exit();
		}
	}
	function isIndexExist(){
		$index=$_GET['cindex'];
		$siteid=$_GET['siteid'];
		$channelid=intval($_GET['channelid']);
		$where=array('cindex'=>$index,'site'=>intval($siteid));
		if (get_magic_quotes_gpc()){
			$index=stripslashes($index);
		}
		$index=htmlspecialchars($index,ENT_QUOTES);
		$where='`cindex`=\''.$index.'\' AND site='.intval($siteid);
		if ($channelid){
			$where.=' AND id!='.intval($channelid);
		}
		$thisChannel=$this->channel_db->get_one($where);
		echo $thisChannel?1:0;
	}
	function rightFrame(){
		$homeChannel=$this->channel_db->get_one(array('parentid'=>0,'site'=>$this->siteid));
		include ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'channel'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'rightFrame.tpl.php';
	}
	function channelTree(){
		$siteID=intval($_GET['siteid']);
		$firstGradeChannes=$this->channel_db->select(array('parentid'=>0,'site'=>$siteID),'*','','taxis ASC');
		$type=isset($_GET['type'])?$_GET['type']:'content';
		$type=$type==''?'content':$type;
		if ($type!='content'&&$type!='channel'){
			$type='content';
		}
		if ($type!='channel'){
			////////////////////////权限有限制的情况下，获取指定的栏目树/////////////////////////////
			/*
			$channels=$this->channel_db->get_results('*','');
			$channelsByID=array();//用栏目id作为数组id来组织栏目数组
			if ($channels){
				foreach ($channels as $c){
					$channelsByID[$c['id']]=$c;
				}
			}
			*/
			//$rights=rights($user->uid);//根据用户id获取用户的权限数组
			//$accessChannels=accessChannels($rights);
			/*******************向上查找哪些父栏目需要在栏目树中显示*********************/
			/*
			$showChannels=array();//向上查找哪些父栏目需要在栏目树中显示
			foreach ($accessChannels as $channelid){
				$pid=$channelid;
				do {
					$pid=$channelsByID[$channelid]['parentid'];
					array_push($showChannels,$pid);
					array_push($showChannels,$channelid);
					$channelid=$channelsByID[$pid]['id'];
				}while ($pid!=0&&!in_array($pid,$showChannels));

			}
			*/
		}
		$str='';
		if ($firstGradeChannes){
			foreach ($firstGradeChannes as $g1c){
				//if (!$showChannels){
					$str.='<div><img src="image/minus.gif" align="absmiddle" rel="divTog_0" class="tog" /><img src="image/folder.gif" align="absmiddle" /> <a href="?m=channel&c=m_channel&a=channels&id='.$g1c['id'].'&siteid='.$siteID.'" target="sright">'.$g1c['name'].'</a></div>
<div id="divTog_0">';
				//}else {
					//echo '<div><img src="image/minus.gif" align="absmiddle" rel="divTog_0" class="tog" /><img src="image/folder.gif" align="absmiddle" /> '.$g1c->name.'</div><div id="divTog_0">';
				//}

				$tree = $this->channelObj->channelTree($this->channelObj->tree($g1c['id'],$siteID),0,$type,$accessChannels,$showChannels);
				$str.=$tree;
				$str.='</div>';
			}
		}
		include ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'channel'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'channelTree.tpl.php';
	}
	public function action_deleteChannel(){
		if (!$_POST['id']){
			showMessage('请选择要删除的栏目',$_SERVER['HTTP_REFERER']);
			exit();
		}else {
			$i=0;
			foreach ($_POST['id'] as $k=>$id){
				//
				$subChannels=$this->channel_db->select(array('parentid'=>$id),'*','');
				if ($subChannels){
					showMessage('请先删除子栏目后再删除该栏目',$_SERVER['HTTP_REFERER']);
					exit();
				}
				$contents=M('article')->select(array('channel_id'=>$id));
				if ($contents){
					showMessage('请先删除栏目内内容后再删除该栏目',$_SERVER['HTTP_REFERER']);
					exit();
				}
				//
				$this->channel_db->delete(array('id'=>$id));
				$i++;
			}
			delCache('navChannels'.$this->siteid);
			showMessage(L('deleteSuccess'),$_SERVER['HTTP_REFERER']);
		}
	}
	public function channelSet(){
		if (IS_POST){
			$tpl=bpBase::loadAppClass('template','template');
			$info=$_POST['info'];
			if (!isset($_POST['id'])){//add
				$info['time']=SYS_TIME;
				if (!$info['shortname']){
					$info['shortname']=$info['name'];
				}
				$info['token']=$this->token;
				$channelid=$this->channel_db->insert($info,1);
				$this->channel_db->update(array('taxis'=>$channelid),array('id'=>$channelid));
				$tpl->createChannelPageR($channelid);
				delCache('navChannels'.$this->siteid);
				//设置其他栏目为非首页显示
				if ($info['homepicturechannel']){
					$this->channel_db->update(array('homepicturechannel'=>0),'id!='.$channelid);
				}
				if ($info['hometextchannel']){
					$this->channel_db->update(array('hometextchannel'=>0),'id!='.$channelid);
				}
				delCache('navChannels'.$this->siteid);
				//
				showMessage(L('addSuccess'),$_POST['referer']);
			}else {//update
				$thisChannel=$this->channel_db->get_one(array('id'=>$_POST['id']));
				if (!isset($info['externallink'])){
					$info['externallink']=0;
				}
				$this->channel_db->update($info,array('id'=>$_POST['id']));
				$tpl->createChannelPageR($thisChannel['id']);
				//设置其他栏目为非首页显示
				if ($info['homepicturechannel']){
					$this->channel_db->update(array('homepicturechannel'=>0),'id!='.$_POST['id']);
				}
				if ($info['hometextchannel']){
					$this->channel_db->update(array('hometextchannel'=>0),'id!='.$_POST['id']);
				}
				delCache('channelOfIndex'.$thisChannel['cindex'].'Site'.$thisChannel['site']);
				delCache('navChannels'.$this->siteid);
				showMessage(L('updateSuccess'),$_POST['referer']);
			}
		}else {
			//
			$template_db=bpBase::loadModel('template_model');
			$channelTemplates=$template_db->select(array('site'=>$this->siteid,'type'=>2),'*','','id ASC');
			$contentTemplates=$template_db->select(array('site'=>$this->siteid,'type'=>3),'*','','id ASC');
			if ($_GET['id']){
				$thisChannel=$this->channel_db->get_one(array('id'=>$_GET['id']));
				//
				$parentChannelID=intval($thisChannel['parentid']);
				$parentChannel=$this->channel_db->get_one(array('id'=>$parentChannelID));
				$siteid=$parentChannel['site'];
			}else {
				$parentChannelID=intval($_GET['parentid']);
				$parentChannel=$this->channel_db->get_one(array('id'=>$parentChannelID));
				$siteid=$parentChannel['site'];
				//
				$thisChannel=array();
				$thisChannel['pagesize']=20;
				$thisChannel['thumbwidth']=0;
				$thisChannel['thumb2width']=0;
				$thisChannel['thumb3width']=0;
				$thisChannel['thumb4width']=0;
				$thisChannel['thumbheight']=0;
				$thisChannel['thumb2height']=0;
				$thisChannel['thumb3height']=0;
				$thisChannel['thumb4height']=0;
			}
			include $this->showManageTpl('channelSet');
		}
	}
	public function channels(){
		if(isset($_POST['taxis'])){
			foreach ($_POST['taxis'] as $id=>$value){
				$this->channel_db->update(array('taxis'=>$value),array('id'=>$id));
			}
			delCache('navChannels'.$this->siteid);
			showMessage('排序成功',$_SERVER['HTTP_REFERER']);
		}else {
			$parentChannelID=intval($_GET['id']);
			$siteid=intval($_GET['siteid']);
			$channels=$this->channel_db->select(array('site'=>$siteid,'parentid'=>$parentChannelID),'*',$limit='', $order = '`taxis` ASC');
			delCache('navChannels'.$this->siteid);
			include $this->showManageTpl('channels');
		}
	}
	//删除错误的栏目
	public function deleteChannels(){
		$channels=$this->channel_db->select('token=\''.$this->token.'\' AND parentid>0');
		if ($channels){
			foreach ($channels as $c){
				$parentChannel=$this->channel_db->get_one(array('id'=>$c['parentid']));
				if (!$parentChannel){
					$this->channel_db->delete(array('id'=>$c['id']));
				}
			}
		}
		echo 'success';
	}
}
?>