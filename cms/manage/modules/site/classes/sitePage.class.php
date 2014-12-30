<?php
bpBase::loadAppClass('front','front',0);
class sitePage extends front{
	public $token;
	public $siteid;
	public $site_db;
	public $site;
	public $channel_db;
	public $article_db;
	public $navChannels;
	public $ys;
	public $smartyDir;
	public $company;
	public $wechat_id;
	function __construct(){
		parent::__construct();
		$this->site_db=M('site');
		$this->channel_db=M('channel');
		$this->article_db=M('article');
		//wechat_id
		$this->wechat_id=$_GET['wecha_id'];
		
		//
		if (isset($_GET['token'])){
			$this->token=$_GET['token'];
			$this->site=$this->site_db->getSiteByToken($this->token);
			$this->siteid=$this->site['id'];
		}
		if (isset($_GET['channelid'])){
			$this->channel=$this->channel_db->get_one(array('id'=>intval($_GET['channelid'])));
			$this->token=$this->channel['token'];
			if ($this->token){
				$this->site=$this->site_db->getSiteByToken($this->token);
				$this->siteid=$this->site['id'];
			}
			$this->assign('channel',$this->channel);
		}
		if (isset($_GET['contentid'])){
			$this->article=$this->article_db->getContentByID($_GET['contentid']);
			$thisContent=array();
			$thisContent['title']=$this->article->title;
			$thisContent['content']=convertToMobile($this->article->content);
			$this->assign('content',$thisContent);
			//
			$this->channel=$this->channel_db->get_one(array('id'=>$this->article->channel_id));
			$this->token=$this->channel['token'];
			if ($this->token){
				$this->site=$this->site_db->getSiteByToken($this->token);
				$this->siteid=$this->site['id'];
			}
			$this->assign('channel',$this->channel);
		}
		if (!$this->site){
			exit('no site');
		}
		//
		$this->navChannels=$this->channel_db->navChannels($this->siteid);
		$this->ys=intval($this->siteid%10);
		if (!isset($_SESSION['previewSkin'])){
			$this->smartyDir=$this->ys.'/'.$this->token.'/';
			$cssDir='smarty/templates/'.$this->ys.'/'.$this->token;
		}else {//预览状态
			$this->smartyDir='tpls/'.$_SESSION['previewSkin'].'/';
			$cssDir='smarty/templates/tpls/'.$_SESSION['previewSkin'];
			$this->site['template']=$_SESSION['previewSkin'];
		}
		$this->assign('cssdir',$cssDir);
		$this->assign('ys',$this->ys);
		//头部和底部
		$this->assign('header',$this->smartyDir.'header.html');
		$this->assign('footer',$this->smartyDir.'footer.html');
		//
		$this->site['plugmenucolor']='#f60';
		$this->assign('site',$this->site);
		$this->navChannels=$this->convertLinks($this->navChannels);
		$this->assign('navChannels',$this->navChannels);
		$this->assign('homeUrl','index.php?token='.$this->token);
		$this->assign('homeurl','index.php?token='.$this->token);
		$this->assign('token',$this->token);
		//rand
		$randNum=rand(1,9999);
		$this->assign('rand',$randNum);
		//company
		$this->company=M('company')->getCompany($this->token);
		$this->assign('company',$this->company);
		//plug menu
		$showPlugMenu=0;
		$plugMenus=$this->_getPlugMenu();
		if (count($plugMenus)){
			$showPlugMenu=1;
		}
		$this->assign('showPlugMenu',$showPlugMenu);
		$this->assign('plugmenus',$plugMenus);
	}
	function index(){
		$this->assign('homepage',1);
		//meta
		$this->assign('metaTitle',$this->site['name']);
		$this->assign('metaKeywords',$this->site['name']);
		$this->assign('metaDescription',$this->site['intro']);
		//循环读取各栏目内的最新内容
		$channels=$this->navChannels;
		foreach ($channels as $c){
			if ($c['homepicturechannel']||$c['hometextchannel']){
				$channelContentName='channel_'.$c['cindex'].'_contents';
				$$channelContentName=$this->convertLinks($this->article_db->getContentsByChannel($c['id']));
				if ($c['homepicturechannel']){
					$pictureChannel=$c;
					$this->assign('pictureChannel',$c);
					$this->assign('pictureContents',$$channelContentName);
				}
				if ($c['hometextchannel']){
					$this->assign('textChannel',$c);
					$this->assign('textContents',$$channelContentName);
				}
			}
			$this->assign('channel_'.$c['cindex'].'_contents',$$channelContentName);
		}
		//幻灯片
		$focusChannel=$this->channel_db->getChannelByIndex('focus',$this->siteid);
		$focusContents=$this->article_db->getContentsByChannel($focusChannel->id);
		$this->assign('channel_focus_contents',$this->convertLinks($focusContents));
		$focusCount=count($focusContents);
		$this->assign('focusCount',$focusCount>4?4:$focusCount);
		
		//焦点图最大高度
		/*
		$maxPictureHeight=0;
		if ($channel_focus_contents){
			foreach ($channel_focus_contents as $c){
				if ($c['thumb']){
					$imgInfo=getimagesize($c['thumb']);
					if ($imgInfo[1]>$maxPictureHeight){
						$maxPictureHeight=$imgInfo[1];
					}
				}
			}
		}
		$this->assign('maxPictureHeight',$maxPictureHeight);
		*/
		
		//图片类信息要读取子栏目的
		$pictureChannelIDs=array($pictureChannel['id']);
		$subChannels=$this->channel_db->getChannelsByParentID($pictureChannel['id']);
		$this->assign('pictureSubChannels',$subChannels);
		if ($subChannels){
			foreach ($subChannels as $sc){
				array_push($pictureChannelIDs,$sc->id);
			}
			$pictureContents=$this->convertLinks($this->article_db->select(to_sqls($pictureChannelIDs,'','channel_id'),'*','','time DESC'));
			$this->assign('pictureContents',$pictureContents);
		}
		
		//display
		$this->display($this->smartyDir.'index.html',0);
	}
	function search(){
		//meta
		$this->assign('metaTitle','搜索_'.$this->site['name']);
		//
		$keyword=$_POST['SeaStr'];
		if (!trim($keyword)){
			header('Location:index.php?token='.$this->token);
		}
		$keyword=htmlspecialchars($keyword,ENT_QUOTES);
		$contents=$this->article_db->select('ex!=1 AND site=\''.$this->site['id'].'\' AND title LIKE \'%'.$keyword.'%\'','title,subtitle,thumb,time,link');
		$contents=$this->convertLinks($contents);
		$this->assign('contents',$contents);
		$this->display($this->smartyDir.'channel_text.html',0);
	}
	function channel(){
		$channelid=$this->channel['id'];
		//meta
		if (!$this->channel['metatitle']){
			$this->assign('metaTitle',$this->channel['name'].'_'.$this->site['name']);
		}else {
			$this->assign('metaTitle',$this->channel['metatitle']);
		}
		$this->assign('metaKeywords',$this->channel['metakeyword']);
		$this->assign('metaDescription',$this->channel['metades']);
		//子分类
		$page=intval($_GET['page']);
		$page=$page>0?$page:1;
		$pageSize=intval($this->channel['pagesize']);
		$channelIDs=array($channelid);
		$subChannels=$this->channel_db->getChannelsByParentID($channelid);
		if ($subChannels){
			$subChannels=objectsToArrByKey($subChannels);
			$subChannels=$this->convertLinks($subChannels);
			$this->assign('subChannels',$subChannels);
			if ($subChannels){
				foreach ($subChannels as $sc){
					array_push($channelIDs,$sc['id']);
				}
			}
			$contents=$this->article_db->listinfo(to_sqls($channelIDs,'','channel_id'), $order = 'taxis DESC', $page, $pageSize);
		}else {
			//
			$contents=$this->article_db->listinfo(array('channel_id'=>$channelid), $order = 'taxis DESC', $page, $pageSize);
		}
		//如果只有一条信息就链接到该信息
		if (count($contents)==1){
			header('Location:?m=site&c=home&a=content&contentid='.$contents[0]['id']);
		}
		//
		$contents=$this->convertLinks($contents);
		$this->assign('contents',$contents);
		//
		$total=$this->article_db->number;
		if ($pageSize){
			if ($total%$pageSize==0){
				$totalPage=$total/$pageSize;
			}else {
				$totalPage=intval($total/$pageSize)+1;
			}
		}
		$totalPage=$totalPage>0?$totalPage:1;
		$this->assign('totalPage',$totalPage);//总页数
		$currentPage=$page;
		$this->assign('currentPage',$currentPage);//当前页码
		if ($totalPage==1||$currentPage==1){
			$this->assign('previousPageLink','javascript:void(0)');//上页链接
		}else {
			$previousPageNum=$currentPage-1;
			$this->assign('previousPageLink',$this->channel['link'].'&page='.$previousPageNum);//上页链接
		}
		if ($totalPage==1||$currentPage==$totalPage){
			$this->assign('nextPageLink','javascript:void(0)');//下页链接
		}else {
			$nextPageNum=$currentPage+1;
			$this->assign('nextPageLink',$this->channel['link'].'&page='.$nextPageNum);//下页链接
		}
		//template
		$template_db=M('template');
		$channelTemplate=$template_db->get_one(array('id'=>$this->channel['channeltemplate']));
		$templatePaths=explode('/',$channelTemplate['path']);
		$templateFileName=$templatePaths[count($templatePaths)-1];
		$this->display($this->smartyDir.$templateFileName,0);
	}
	function content(){
		//meta
		$this->assign('metaTitle',$this->article->title.'_'.$this->site['name']);
		$this->assign('metaKeywords',$this->article->keywords);
		$this->assign('metaDescription',$this->article->intro);
		//
		$nextContent=$this->article_db->nextArticle($this->article->id,$this->article->channel_id);
		if ($nextContent->externallink){
			$nextContent->link=$this->getLink($nextContent->link);
		}
		$this->assign('nextContent',$nextContent);
		$previousContent=$this->article_db->previousArticle($this->article->id,$this->article->channel_id);
		if ($previousContent->externallink){
			$previousContent->link=$this->getLink($previousContent->link);
		}
		$this->assign('previousContent',$previousContent);
		//
		$this->display($this->smartyDir.'content.html',0);
	}
	function _getPlugMenu(){
		$plugmenu_db=M('site_plugmenu');
		$plugmenus=$plugmenu_db->select(array('token'=>$this->token,'display'=>1),'*','0,4','taxis ASC');
		if ($plugmenus){
			$i=0;
			foreach ($plugmenus as $pm){
				switch ($pm['name']){
					case 'tel':
						if (!$pm['url']){
							$pm['url']='http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone='.$this->company['tel'];
						}else {
							$pm['url']='http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone={pigcms:$thisCompany.tel}'.$pm['url'];
						}
						break;
					case 'memberinfo':
						if (!$pm['url']){
							$pm['url']='/index.php?g=Wap&m=Userinfo&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
						}
						break;
					case 'nav':
						if (!$pm['url']){
							$pm['url']='/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id='.$this->wechat_id;
						}
						break;
					case 'message':
						break;
					case 'share':
						break;
					case 'home':
						if (!$pm['url']){
							$pm['url']='/index.php?g=Wap&m=Index&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
						}
						break;
					case 'album':
						if (!$pm['url']){
							$pm['url']='/index.php?g=Wap&m=Photo&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
						}
						break;
					case 'email':
						$pm['url']='email:'.$pm['url'];
						break;
					case 'shopping':
						if (!$pm['url']){
							$pm['url']='/index.php?g=Wap&m=Product&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
						}
						break;
					case 'membercard':
						$card=M('member_card_create')->get_one(array('token'=>$this->token,'wecha_id'=>$this->wechat_id));
						if (!$pm['url']){
							if($card==false){
								$pm['url']='/index.php?g=Wap&m=Card&a=get_card&token='.$this->token.'&wecha_id='.$this->wechat_id;
	
							}else{
								$pm['url']='/index.php?g=Wap&m=Card&a=vip&token='.$this->token.'&wecha_id='.$this->wechat_id;
	
							}
						}
						break;
					case 'activity':
						$pm['url']=$this->getLink($pm['url']);
						break;
					case 'weibo':
						break;
					case 'tencentweibo':
						break;
					case 'qqzone':
						break;
					case 'wechat':
						$pm['url']='weixin://addfriend/'.$this->weixinUser['wxid'];
						break;
					case 'music':
						break;
					case 'video':
						break;
					case 'recommend':
						$pm['url']=$this->getLink($pm['url']);
						break;
					case 'other':
						$pm['url']=$this->getLink($pm['url']);
						break;
				}
				$plugmenus[$i]=$pm;
				$i++;
			}
			
		}else {//默认的
			$plugmenus=array();
			/*
			$plugmenus=array(
			array('name'=>'home','url'=>'/index.php?g=Wap&m=Index&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id),
			array('name'=>'nav','url'=>'/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id='.$this->wechat_id),
			array('name'=>'tel','url'=>'tel:'.$this->company['tel']),
			array('name'=>'share','url'=>''),
			);
			*/
		}
		return $plugmenus;
	}
	function share(){
		$str='<!-- Baidu Button BEGIN -->
        <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare" data="{\'url\':'.$_SERVER['HTTP_REFERER'].'}">
            <a class="bds_qzone"></a>
            <a class="bds_tsina"></a>
            <a class="bds_baidu"></a>
            <a class="bds_renren"></a>
            <a class="bds_tqq"></a>
            <a class="bds_kaixin001"></a>
            <a class="bds_tqf"></a>
            <a class="bds_hi"></a>
            <a class="bds_douban"></a>
            <a class="bds_tsohu"></a>
            <a class="bds_msn"></a>
            <a class="bds_qq"></a>
            <a class="bds_thx"></a>
            <a class="bds_taobao"></a>
            <a class="bds_tieba"></a>
            <a class="bds_buzz"></a>
            <a class="bds_sohu"></a>
            <a class="bds_t163"></a>
            <a class="bds_qy"></a>
            <a class="bds_meilishuo"></a>
            <a class="bds_mogujie"></a>
            <a class="bds_diandian"></a>
            <a class="bds_huaban"></a>
            <a class="bds_leho"></a>
            <a class="bds_share189"></a>
            <a class="bds_duitang"></a>
            <a class="bds_hx"></a>
            <a class="bds_tfh"></a>
            <a class="bds_fx"></a>
            <a class="bds_tuita"></a>
            <a class="bds_ff"></a>
            <a class="bds_wealink"></a>
            <a class="bds_youdao"></a>
            <a class="bds_xg"></a>
            <a class="bds_ty"></a>
            <a class="bds_fbook"></a>
            <a class="bds_twi"></a>
            <a class="bds_ms"></a>
            <a class="bds_deli"></a>
            <a class="bds_s139"></a>
            <a class="bds_s51"></a>
            <a class="bds_zx"></a>
            <a class="bds_linkedin"></a>
        </div>
        <script type="text/javascript" id="bdshare_js" data="type=tools" ></script>
        <script type="text/javascript" id="bdshell_js"></script>
        <script type="text/javascript">
        /**
         * 在这里定义bds_config
         */
        var bds_config = {
            \'bdDes\':\'\',
            \'bdText\':\''.$this->company['name'].'\',
            \'bdPopTitle\':\'分享到\',
            \'bdComment\':\'\',	
            \'bdPic\':\'\',
            \'searchPic\':0,
            \'wbUid\':\'\',
        }
        document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
        </script>
        <!-- Baidu Button END -->';
		$content=array();
		$content['title']='分享';
		$content['content']=$str;
		$this->assign('content',$content);
		$this->assign('isshare',1);
		$this->assign('sharestr',$str);
		$this->assign('metaTitle','分享_'.$this->site['name']);
		$this->display($this->smartyDir.'content.html',0);
	}
	function map(){
		$str='
		<div style="clear:both"></div>
		<style type="text/css">
body, html,#allmap {width: 100%;height: 600px;z-index:8000;overflow: hidden;hidden;margin:0;}
@media all and (min-width:640px){
body{margin:0 auto;position:relative;}
}
</style>
		<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
		<div id="allmap"></div>
<script type="text/javascript">
window.onload = function (){
var map = new BMap.Map("allmap");
map.centerAndZoom(new BMap.Point('.$this->company['longitude'].','.$this->company['latitude'].'), 15);
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
var marker1 = new BMap.Marker(new BMap.Point('.$this->company['longitude'].','.$this->company['latitude'].'));  // 创建标注
map.addOverlay(marker1);              // 将标注添加到地图中
marker1.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

//创建信息窗口
var infoWindow1 = new BMap.InfoWindow("'.$this->company['name'].'<br />'.$this->company['address'].'<br />联系电话：'.$this->company['tel'].'");
marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
}
</script>';
		$this->assign('ismap',1);
		$this->assign('mapstr',$str);
		$this->assign('metaTitle','地图_'.$this->site['name']);
		$this->display($this->smartyDir.'content.html',0);
	}
	/**
	 * 获取链接
	 *
	 * @param unknown_type $url
	 * @return unknown
	 */
	public function getLink($url){
		$urlArr=explode(' ',$url);
		$urlInfoCount=count($urlArr);
		if ($urlInfoCount>1){
			$itemid=intval($urlArr[1]);
		}
		//会员卡 刮刮卡 团购 商城 大转盘 优惠券 订餐 商家订单
		if (strExists($url,'刮刮卡')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Guajiang&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link.='&id='.$itemid;
			}
		}elseif (strExists($url,'大转盘')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Lottery&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link.='&id='.$itemid;
			}
		}elseif (strExists($url,'优惠券')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Coupon&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link.='&id='.$itemid;
			}
		}elseif (strExists($url,'商家订单')){
			if ($itemid){
				$link=$link=PIGCMS_URL.'/index.php?g=Wap&m=Host&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id.'&hid='.$itemid;
			}else {
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Host&a=Detail&token='.$this->token.'&wecha_id='.$this->wechat_id;
			}
		}elseif (strExists($url,'万能表单')){
			if ($itemid){
				$link=$link=PIGCMS_URL.'/index.php?g=Wap&m=Selfform&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}else {
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Host&a=Detail&token='.$this->token.'&wecha_id='.$this->wechat_id;
			}
		}elseif (strExists($url,'相册')){
			if ($itemid){
				$link=$link=PIGCMS_URL.'/index.php?g=Wap&m=Photo&a=plist&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}else {
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Photo&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
			}
		}elseif (strExists($url,'会员卡')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Card&a=vip&token='.$this->token.'&wecha_id='.$this->wechat_id;
		}elseif (strExists($url,'商城')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Product&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
		}elseif (strExists($url,'订餐')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Product&a=dining&dining=1&token='.$this->token.'&wecha_id='.$this->wechat_id;
		}elseif (strExists($url,'团购')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Groupon&a=grouponIndex&token='.$this->token.'&wecha_id='.$this->wechat_id;
		}elseif (strExists($url,'网站分类')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Index&a=lists&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Index&a=lists&token='.$this->token.'&wecha_id='.$this->wechat_id.'&classid='.$itemid;
			}
		}elseif (strExists($url,'图文回复')){
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Index&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}
		}elseif (strExists($url,'LBS信息')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id='.$this->wechat_id.'&companyid='.$itemid;
			}
		}elseif (strExists($url,'DIY宣传页')){
			$link=PIGCMS_URL.'/index.php/show/'.$this->token;
		}elseif (strExists($url,'婚庆喜帖')){
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Wedding&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}
		}elseif (strExists($url,'投票')){
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Vote&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}
		}elseif (strExists($url,'全景')){
			$link=PIGCMS_URL.'/index.php?g=Wap&m=Panorama&a=index&token='.$this->token.'&wecha_id='.$this->wechat_id;
			if ($itemid){
				$link=PIGCMS_URL.'/index.php?g=Wap&m=Panorama&a=item&token='.$this->token.'&wecha_id='.$this->wechat_id.'&id='.$itemid;
			}
		}else {
			$link=str_replace(array('{wechat_id}','{siteUrl}'),array($this->wechat_id,PIGCMS_URL),$url);
		}
		return $link;
	}
	public function convertLinks($arr){
		$i=0;
		foreach ($arr as $a){
			if ($a['externallink']){
				$arr[$i]['link']=$this->getLink($a['link']);
			}
			$i++;
		}
		return $arr;
	}
}