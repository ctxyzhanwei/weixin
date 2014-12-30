<?php
bpBase::loadAppClass('manage','manage',0);
class createHtml extends manage {
	public static $tpl;
	function __construct() {
		parent::__construct();
		if ($this->tpl=='') $this->tpl=bpBase::loadAppClass('template','template',1);
	}
	function createIndexPage(){
		$siteid=intval($_GET['siteid']);
		$this->tpl->createIndexPage($siteid);
		if ($siteid<100){
			$siteObj=bpBase::loadAppClass('siteObj','site');
			$thisSite=$siteObj->getSiteByID($siteid);
		}else {
			$special_db=bpBase::loadModel('special_model');
			$thisSite=$special_db->get_row(array('id'=>$siteid));
		}
		
		//生成静态首页

		include $this->showManageTpl('createIndexPage');
	}
	function createChannelPageSelect(){
		$channel_db = bpBase::loadModel('channel_model');
		$channel=bpBase::loadAppClass('channelObj','channel',1);
		if (isset($_POST['doSubmit'])){//选定栏目
			if ($_GET['type']=='channel'){
				$channelIDStr='';
				if ($_POST['channels']){
					$comma='';
					foreach ($_POST['channels'] as $c){
						//$channelTotalPage=$channel->getChannelTotalPage($c);
						$channelTotalPage=1;
						for ($t=1;$t<$channelTotalPage+1;$t++){
							$channelIDStr.=$comma.$c.'.'.$t;//,channelid.pageNum
							$comma=',';
						}
						//清除缓存
						$content=bpBase::loadAppClass('articleObj','article',1);
						$content->clearContentsCache($c);
						$channel->clearCrumbCache($c);
					}
				}
				file_put_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'channels.txt',$channelIDStr);
				echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=channel\';</script>';
			}elseif ($_GET['type']=='content'){
				$article_db=bpBase::loadModel('article_model');
				$contentIDStr='';
				if ($_POST['channels']){
					$where=to_sqls($_POST['channels'],'','channel_id');
					$contents=$article_db->get_results('id','',$where,'time DESC');
					$comma='';
					if ($contents){
						foreach ($contents as $cc){
							$contentIDStr.=$comma.$cc->id;
							$comma=',';
						}
					}
				}
				file_put_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'contents.txt',$contentIDStr);
				echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=content\';</script>';
			}
		}else {
			/*
			$channels=$channel_db->select('');
			$channelsByID=array();//用栏目id作为数组id来组织栏目数组
			if ($channels){
				foreach ($channels as $c){
					$channelsByID[$c['id']]=$c;
				}
			}
			$rights=$this->rights($user->uid);//根据用户id获取用户的权限数组
			$accessChannels=$this->accessChannels($rights);
			//*******************向上查找哪些父栏目需要在栏目树中显示********************
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
			/////////////////////////////////////////////////
			$selectOptionStr = $channel->channelCreatePageTree($channel->tree(0,$_GET['siteid']),0,$accessChannels,$showChannels);
			include $this->showManageTpl('createChannelPageSelect');
		}
	}
	function createChannelPage(){
		$i=isset($_GET['i'])?intval($_GET['i']):0;
		$progressBarTotalWidth=400;
		if ($_GET['type']=='channel'){
			$channelIDStr=file_get_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'channels.txt');
			$idsArr=explode(',',$channelIDStr);
			$nextI=$i+1;
			$count=count($idsArr);
			$idsArr2=explode('.',$idsArr[$i]);
			$createRt=$this->tpl->createChannelPageR($idsArr2[0],$idsArr2[1]);
			if ($nextI!=$count){
				$progressBarWidth=$nextI*$progressBarTotalWidth/$count;
				showMessage('正在生成栏目页，进度：'.$nextI.'/'.$count.'','?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=channel&i='.$nextI,1);
				//$tip='正在生成'.$nextI.'/'.$count.'，请勿关闭';
			}else {
				//$progressBarWidth=$progressBarTotalWidth;
				$tip='生成完成，共生成'.$count.'个栏目页面';
				unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'channels.txt');
				showMessage($tip);
			}
		}if ($_GET['type']=='content'){
			$content=bpBase::loadAppClass('articleObj','article',1);
			$contentIDStr=file_get_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'contents.txt');
			$idsArr=explode(',',$contentIDStr);
			$pageCount=2;//每次生成多少个
			$nextI=$i+$pageCount;
			$count=count($idsArr);
			for ($t=$i;$t<$nextI;$t++){
				if ($t<$count){
					$this->tpl->createContentPageR($idsArr[$t]);
				}
			}
			if ($nextI<$count){
				showMessage('正在生成内容页，进度：'.$nextI.'/'.$count.'','?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=content&i='.$nextI,1);
				//$progressBarWidth=$nextI*$progressBarTotalWidth/$count;
				//$tip='正在生成'.$nextI.'/'.$count.'，请勿关闭';
			}else {
				$progressBarWidth=$progressBarTotalWidth;
				$tip='生成内容页完成，共生成'.$count.'个页面';
				unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'contents.txt');
				showMessage($tip);
			}
		}
		//include $this->showManageTpl('createChannelPage');
	}
	function createSinglePageSelect(){
		define('STATISTIC_CODE','');
		if (isset($_POST['doSubmit'])){//选定栏目
			$IDStr='';
			if ($_POST['singlePages']){
				$comma='';
				foreach ($_POST['singlePages'] as $c){
					$IDStr.=$comma.$c;
					$comma=',';
				}
			}
			file_put_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'singlePages.txt',$IDStr);
			echo '<script>window.location.href=\'?m=template&c=createHtml&a=createSinglePage&siteid='.$_GET['siteid'].'\';</script>';
		}else {
			$template_db=bpBase::loadModel('template_model');
			$templates=$template_db->select(array('site'=>$_GET['siteid'],'type'=>4),'*');
			$optionStr='';
			if ($templates){
				foreach ($templates as $t){
					$optionStr.='<option value="'.$t['id'].'">'.$t['name'].'</option>';
				}
			}
			include $this->showManageTpl('createSinglePageSelect');
		}
	}
	function createSinglePage(){
		$channelIDStr=file_get_contents(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'singlePages.txt');
		$idsArr=explode(',',$channelIDStr);
		$i=isset($_GET['i'])?intval($_GET['i']):0;
		$nextI=$i+1;
		$count=count($idsArr);
		$createRt=$this->tpl->createSinglePage($idsArr[$i]);
		$progressBarTotalWidth=400;
		if ($nextI!=$count){
			$progressBarWidth=$nextI*$progressBarTotalWidth/$count;
			$tip='正在生成'.$nextI.'/'.$count.'，请勿关闭';
		}else {
			$progressBarWidth=$progressBarTotalWidth;
			$tip='生成完成，共生成'.$count.'个单页';
			unlink(ABS_PATH.'templatesCache'.DIRECTORY_SEPARATOR.'singlePages.txt');
		}
		include $this->showManageTpl('createSinglePage');
	}
}
?>