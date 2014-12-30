<?php
class CardAction extends WapAction{
	public $wecha_id;
	public $thisUser;
	public $isamap;
	public function __construct(){
		parent::_initialize();
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		//$this->wecha_id=$this->_get('wecha_id');
		$this->assign('wecha_id',$this->wecha_id);
		//
		$this->token=$this->_get('token');
		$this->thisUser = M('Userinfo')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->wecha_id))->find();
		if (!$this->wecha_id){
			$this->error('您没有权限使用会员卡，如需使用请关注微信“'.$this->wxuser['wxname'].'”并回复会员卡',U('Index/index',array('token'=>$this->token)));
		}
		
		
		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}
	public function index(){
		//transfer start
		$data=M('Member_card_create');
		$cardByToken=M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->find();
		$data->where('token=\''.$this->token.'\' AND cardid<2')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_exchange')->where('token=\''.$this->token.'\' AND cardid<2')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_coupon')->where('token=\''.$this->token.'\' AND cardid<2')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_vip')->where('token=\''.$this->token.'\' AND cardid<2')->save(array('cardid'=>$cardByToken['id']));
		M('Member_card_integral')->where('token=\''.$this->token.'\' AND cardid<2')->save(array('cardid'=>$cardByToken['id']));
		//transfer end
		$member_create_db=M('Member_card_create');
		//
		$cards=$member_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
		$cardsByID=array();
		if ($cards){
			foreach ($cards as $c){
				$cardsByID[$c['cardid']]=$c;
			}
		}
		$cardsCount=count($cards);
		$this->assign('cards',$cards);
		$this->assign('memberCard',$cards[0]);
		if ($cardsCount&&isset($_GET['mycard'])){
			echo '<script>location.href="/index.php?g=Wap&m=Card&a=card&wecha_id='.$this->wecha_id.'&token='.$this->token.'&cardid='.$cards[0]['cardid'].'";</script>';
		}
		$this->assign('cardsCount',$cardsCount);
		//
		$userinfo_db=M('Userinfo');
		$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
		$userScore=0;
		if ($userInfos){
			$userScore=intval($userInfos[0]['total_score']);
		}
		$this->assign('userScore',$userScore);
		//
		$member_card_set_db=M('Member_card_set');
		$allCards=$member_card_set_db->where(array('token'=>$this->token))->order('miniscore ASC')->select();
		if ($allCards){
			$i=0;
			foreach ($allCards as $c){
				$allCards[$i]['applied']=$cardsByID[$c['id']]?1:0;
				if (isset($_GET['mycard'])&&!$allCards[$i]['applied']){
					unset($allCards[$i]);
				}
				$i++;
			}
		}
		$allCardsCount=count($allCards);
		$this->assign('allCards',$allCards);
		$this->assign('allCardsCount',$allCardsCount);
		//
		$thisCompany=M('Company')->where(array('token'=>$this->token,'isbranch'=>0,'display'=>1))->find();
		$this->assign('thisCompany',$thisCompany);
		//
		$infoType='memberCardHome';
		if (isset($_GET['mycard'])){
			$infoType='myCard';
		}
		
		$focus = M('Member_card_focus')->where(array('token'=>$this->_get('token')))->select();
		
		if($focus == NULL){
			$focus = array(
				array(
					"info" => "广告位描述",
					"img" => "/tpl/static/attachment/focus/tour/4.jpg",
					"url" => ""
				),
				array(
					"info" => "广告位描述",
					"img" => "/tpl/static/attachment/focus/tour/3.jpg",
					"url" => ""
				)
			);
		}

		$focus = $this->convertLinks($focus);
		$this->assign('flash',$focus);
		$this->assign('infoType',$infoType);
		//
		$this->display();
    }
    public function getLink($url){
		$url=$url?$url:'javascript:void(0)';
		
		$link=str_replace(array('{wechat_id}','{siteUrl}','&amp;'),array($this->wecha_id,$this->siteUrl,'&'),$url);
			if (!!(strpos($url,'tel')===false)&&$url!='javascript:void(0)'&&!strpos($url,'wecha_id=')){
				if (strpos($url,'?')){
					$link=$link.'&wecha_id='.$this->wecha_id;
				}else {
					$link=$link.'?wecha_id='.$this->wecha_id;
				}
			}
		return $link;
	}
	public function convertLinks($arr){
		$i=0;
		foreach ($arr as $a){
			if ($a['url']){
				$arr[$i]['url']=$this->getLink($a['url']);
			}
			$i++;
		}
		return $arr;
	}
    public function companyMap(){
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
    	$this->apikey=C('baidu_map_api');
		$this->assign('apikey',$this->apikey);
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		
		
		if (!$this->isamap){
			$this->display();
		}else {			
			$link=$this->amap->getPointMapLink($thisCompany['longitude'],$thisCompany['latitude'],$thisCompany['name']);
			header('Location:'.$link);
		}
		
		
		
    }
    public function companyDetail(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token))->order('id ASC')->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
		$company_model=M('Company');
		$where=array('token'=>$this->token,'display'=>1);
		$companies=$company_model->where($where)->order('taxis ASC')->select();
		$this->assign('companies',$companies);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		$this->display();
    }
    public function companyIntro(){
    	//
    	$member_card_create_db=M('Member_card_create');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
    	//
		$company_model=M('Company');
		$where=array('token'=>$this->token);
		if (isset($_GET['companyid'])){
			$where['id']=intval($_GET['companyid']);
		}
		$thisCompany=$company_model->where($where)->find();
		$this->assign('thisCompany',$thisCompany);
		$infoType='companyDetail';
		$this->assign('infoType',$infoType);
		$this->display();
    }
    function card(){
    	$this->assign('infoType','card');
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	$this->assign('card',$thisCard);
    	$error=0;
    	if ($thisCard){
    		$userinfo_db=M('Userinfo');
    		$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    		$userScore=0;
    		if ($userInfos){
    			$userScore=intval($userInfos[0]['total_score']);
    			$userInfo=$userInfos[0];
    		}
    		$this->assign('userScore',$userScore);
    		//
    		$member_card_create_db=M('Member_card_create');
    		$thisMember=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>intval($_GET['cardid'])))->find();
    		$hasApplied=count($thisMember);
    		//
    		if (!$hasApplied){
    			//
    			$card=M('Member_card_create')->field('id,number')->where("token='".$this->token."' and cardid=".$thisCard['id']." and wecha_id = ''")->find();
    			if(!$card){
    				$error=-1;
    			}else {
    				//
    				if (intval($thisCard['miniscore'])==0||$userScore>intval($thisCard['miniscore'])){
    					$error=-4;
    					header('Location:/index.php?g=Wap&m=Userinfo&a=index&token='.$this->token.'&wecha_id='.$this->wecha_id.'&cardid='.$thisCard['id']);
    				}else {
    					$error=-3;
    				}
    			}
    			//
    		}else{
    			$this->assign('thisMember',$thisMember);
    			//
    			$now=time();
    			//
    			$noticeCount=M('Member_card_notice')->where('cardid='.$thisCard['id'].' AND endtime>'.$now)->count();
    			$this->assign('noticeCount',$noticeCount);
    			//
    			$member_card_vip_db=M('Member_card_vip');
    			$previlegeCount=$member_card_vip_db->where('cardid='.$thisCard['id'].' AND ((type=0 AND statdate<'.$now.' AND enddate>'.$now.') OR type=1)')->count();
    			$this->assign('previlegeCount',$previlegeCount);
    			//
    			$iwhere 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'3','is_use'=>'0','cardid'=>$thisCard['id']);
    			$integralCount 	= M('Member_card_coupon_record')->where($iwhere)->count();

    			$cwhere1 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'1','is_use'=>'0','cardid'=>$thisCard['id']);
    			$couponCount1 	= M('Member_card_coupon_record')->where($cwhere1)->count();

    			$cwhere2 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'2','is_use'=>'0','cardid'=>$thisCard['id']);
    			$couponCount2 	= M('Member_card_coupon_record')->where($cwhere2)->count();
    			$recordcount 	= $integralCount+$couponCount1+$couponCount2;
    			$now 		= time();
    			$where1 	= array('token'=>$this->token,'cardid'=>$thisCard['id'],'attr'=>'0','statdate'=>array('lt',$now),'enddate'=>array('gt',$now));
    			$coupon 	= M('Member_card_coupon')->where($where1)->count();
    			
    			$where1 	= array('token'=>$this->token,'cardid'=>$thisCard['id'],'statdate'=>array('lt',$now),'enddate'=>array('gt',$now));
    			$integral 	= M('Member_card_integral')->where($where1)->count();

    			$couponCount 	= $coupon+$integral;
    			$this->assign('couponCount',$couponCount);
    			
    			$owhere 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'is_open'=>'1','cardid'=>$thisCard['id'],'start'=>array('lt',time()),'end'=>array('gt',time()));
    			
    			$openCount 		= M('Member_card_gifts')->where($owhere)->count();
 
    			
    			$this->assign('openCount',$openCount);
    			
    			$todaySigned=$this->_todaySigned();
    			$this->assign('todaySigned',$todaySigned);
    			//
    			$this->assign('userInfo',$userInfo);
    		}
    	}else {
    		$error=-2;
    	}
    	$this->assign('error',$error);
    	$this->display();
    }
    
    function gifts(){
    	$cardid = $this->_get('cardid','intval');
    	$now 	= time();
    	$where	= array('token'=>$this->token,'cardid'=>$cardid,'is_open'=>'1','start'=>array('lt',$now),'end'=>array('gt',$now));
    	
    	$list 	= M('Member_card_gifts')->where($where)->select();

		$this->assign('list',$list);
    	$this->display();
    }
    
    function cards(){
    	$this->assign('infoType','card');
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	$this->assign('card',$thisCard);
    	$error=0;
    	if ($thisCard){
    		$userinfo_db=M('Userinfo');
    		$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    		$userScore=0;
    		if ($userInfos){
    			$userScore=intval($userInfos[0]['total_score']);
    			$userInfo=$userInfos[0];
    		}
    		$this->assign('userScore',$userScore);
    		//
    		$member_card_create_db=M('Member_card_create');
    		$thisMember=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>intval($_GET['cardid'])))->find();
    		$hasApplied=count($thisMember);
    		//
    		if (!$hasApplied){
    			//
    			$card=M('Member_card_create')->field('id,number')->where("token='".$this->token."' and cardid=".$thisCard['id']." and wecha_id = ''")->find();
    			if(!$card){
    				$error=-1;
    			}else {
    				//
    				if (intval($thisCard['miniscore'])==0||$userScore>intval($thisCard['miniscore'])){
    					$error=-4;
    					header('Location:/index.php?g=Wap&m=Userinfo&a=index&token='.$this->token.'&wecha_id='.$this->wecha_id.'&cardid='.$thisCard['id']);
    				}else {
    					$error=-3;
    				}
    			}
    			//
    		}else{
    			$this->assign('thisMember',$thisMember);
    			//
    			$now=time();
    			//
    			$noticeCount=M('Member_card_notice')->where('cardid='.$thisCard['id'].' AND endtime>'.$now)->count();
    			$this->assign('noticeCount',$noticeCount);
    			//
    			$member_card_vip_db=M('Member_card_vip');
    			$previlegeCount=$member_card_vip_db->where('cardid='.$thisCard['id'].' AND ((type=0 AND statdate<'.$now.' AND enddate>'.$now.') OR type=1)')->count();
    			$this->assign('previlegeCount',$previlegeCount);
    			//
    			$iwhere 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'3','is_use'=>'0');
    			$integralCount 	= M('member_card_coupon_record')->where($iwhere)->count();
    			$this->assign('integralCount',$integralCount);
    			//
    			$cwhere1 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'1','is_use'=>'0');
    			$couponCount1 	= M('member_card_coupon_record')->where($cwhere1)->count();
    			$this->assign('couponCount1',$couponCount1);
    			//
    			$cwhere2 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>'2','is_use'=>'0');
    			$couponCount2 	= M('member_card_coupon_record')->where($cwhere2)->count();
    			$this->assign('couponCount2',$couponCount2);
    			//
    			$todaySigned=$this->_todaySigned();
    			$this->assign('todaySigned',$todaySigned);
    			//
    			$this->assign('userInfo',$userInfo);
    		}
    	}else {
    		$error=-2;
    	}
    	$this->assign('error',$error);
    	$this->display();
    }
    
    public function cardIntro(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	$data=M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>$_GET['cardid']))->find();
    	$this->assign('data',$data);
    	//
    	$company_model=M('Company');
		$where=array('token'=>$this->token);
		$thisCompany=$company_model->where($where)->order('isbranch ASC')->find();
		$thisCompany['intro']=str_replace(array('&lt;','&gt;','&quot;','&amp;nbsp;'),array('<','>','"',' '),$thisCompany['intro']);
		$this->assign('thisCompany',$thisCompany);
    	//
    	$this->display();
    }
    public function signscore(){
    	$userinfo_db=M('Userinfo');
    	$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    	$userScore=0;
    	if ($userInfos){
    		$userScore=intval($userInfos[0]['total_score']);
    		$userInfo=$userInfos[0];
    	}
    	$this->assign('userInfo',$userInfo);
    	$this->assign('userScore',$userScore);
    	//
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();

    	$this->assign('thisCard',$thisCard);
    	//
    	$todaySigned=$this->_todaySigned();
    	$this->assign('todaySigned',$todaySigned);
    	//
    	
    	$cardsign_db   = M('Member_card_sign');
    	$now=time();
    	$day=date('d',$now);
    	$year=date('Y',$now);
    	$month=date('m',$now);
    	if (isset($_GET['month'])){
    		$month=intval($_GET['month']);
    	}
    	$firstday = date('Y-m-01', strtotime($now));
    	$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    	$firstSecondOfMonth=mktime(0,0,0,$month,1,$year);
    	$lastSecondOfMonth=mktime(23,59,59,$month,$lastday,$year);
    	$signRecords=$cardsign_db->where('token=\''.$this->token.'\' AND wecha_id=\''.$this->wecha_id.'\' AND sign_time>'.$firstSecondOfMonth.' AND sign_time<'.$lastSecondOfMonth)->order('sign_time DESC')->select();
    	$this->assign('signRecords',$signRecords);
    	//
    	$this->display();
    }
    public function addSign(){
    	$signed=$this->_todaySigned();
    	if ($signed){
    		echo'{"success":1,"msg":"您今天已经签到了"}';
    		exit();
    	}
    	$cardsign_db   = M('Member_card_sign');
    	$where    = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'score_type'=>1);
    	$sign = $cardsign_db->where($where)->order('sign_time desc')->find();
    	//
    	if($sign == null){
    		$cardsign_db->add($where);
    		$sign = $cardsign_db->where($where)->order('id desc')->find();
    	}
    	$get_card=M('member_card_create')->where(array('wecha_id'=>$this->wecha_id,'cardid'=>intval($_GET['cardid'])))->find();
    	//
    	if(empty($get_card)){
    		Header("Location: ".C('site_url').'/'.U('Wap/Card/card',array('token'=>$this->token,'wecha_id'=>$this->wecha_id)));
    		exit('领卡后才可以签到.');
    	}
    	//
    	$set_exchange = M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>intval($_GET['cardid'])))->find();
        $this->assign('set_exchange',$set_exchange);
        //
        $userinfo = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
    	//
    	$data['sign_time']  = time();
    	$data['is_sign']    = 1;
    	$data['score_type'] = 1;
    	$data['token']      = $this->token;
    	$data['wecha_id']   = $this->wecha_id;
    	$data['expense']    = intval($set_exchange['everyday']);
    	$rt=$cardsign_db->where($where)->add($data);
    	//
    	if ($rt){
    		$da['total_score'] = $userinfo['total_score'] +  $data['expense'];
    		$da['sign_score']  = $userinfo['sign_score'] + $data['expense'];
    		$da['continuous']  =  1;
    		//
    		M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($da);
    		echo'{"success":1,"msg":"签到成功，成功获取了'.$set_exchange['everyday'].'个积分"}';
    	}else {
    		echo'{"success":1,"msg":"暂时无法签到"}';
    	}
    }
    function _todaySigned(){
    	$signined=0;
    	$now=time();
    	$member_card_sign_db   = M('Member_card_sign');
    	$where    = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'score_type'=>1);
    	$sign = $member_card_sign_db->where($where)->order('sign_time desc')->find();
    	$today = date('Y-m-d',$now);
    	$itoday = date('Y-m-d',intval($sign['sign_time']));
    	if($sign&&$itoday == $today){
    		$signined = 1;
    	}
    	return $signined;
    }
    public function _thisCard(){
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	return $thisCard;
    }
    public function notice(){
    	$this->assign('infoType','notice');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$member_card_notice_db=M('Member_card_notice');
    	$now=time();
    	//
    	$notices=$member_card_notice_db->where('cardid='.$thisCard['id'].' AND endtime>'.$now)->select();
    	if ($notices){
    		$i=0;
    		foreach ($notices as $n){
    			$notices[$i]['content']=html_entity_decode($n['content']);
    			$i++;
    		}
    	}
    	$this->assign('notices',$notices);
    	$this->assign('firstItemID',$notices[0]['id']);
    	$this->display();
    }
    public function previlege(){
    	$this->assign('infoType','privelege');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	//
    	$now=time();
    	//
    	$member_card_vip_db=M('Member_card_vip');
    	$list=$member_card_vip_db->where('cardid='.$thisCard['id'].' AND ((type=0 AND statdate<'.$now.' AND enddate>'.$now.') OR type=1)')->order('create_time desc')->select();
    	if ($list){
    		$i=0;
    		foreach ($list as $n){
    			$list[$i]['info']=html_entity_decode($n['info']);
    			$i++;
    		}
    	}
    	$this->assign('firstItemID',$list[0]['id']);
    	$this->assign('list',$list);
    	//
    	$this->display();
    }
    public function integral(){
    	$this->assign('infoType','integral');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	$is_use 	= $this->_get('is_use','intval')?$this->_get('is_use','intval'):'0';
    	$now=time();
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$thisCard['id'],'coupon_type'=>'3','is_use'=>"$is_use");
    	 
		$data 	= M('Member_card_coupon_record')->where($where)->field('id,cardid,coupon_id,coupon_type,add_time,is_use')->select();
		
		foreach($data as $key=>$value){
			$cwhere 		= array('token'=>$this->token,'cardid'=>$value['cardid'],'id'=>$value['coupon_id']);
			$cinfo			= M('Member_card_integral')->where($cwhere)->field('info,pic,statdate,enddate,title,integral')->find();
			$cinfo['info'] 	= html_entity_decode($cinfo['info']);
			if($cinfo['enddate']>$now && $cinfo['statdate']<$now){
				$data[$key] = array_merge($value,$cinfo);
			}else{
				unset($data[$key]);
			}
		}

    	$this->assign('firstItemID',$data[0]['id']);
    	$this->assign('list',$data);
    	$this->assign('is_use',$is_use);
    	$this->assign('type',$type);
    	$this->display();
    }
    
    public function my_coupon(){
    	$this->assign('infoType','coupon');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	
    	$type 	= $this->_get('type','intval')?$this->_get('type','intval'):1;
    	$now	= time();
    	$data 	= array();
    	if($type  == 3){
    		$where 	= array('token'=>$this->token,'card_id'=>$thisCard['id'],'statdate'=>array('lt',$now),'enddate'=>array('gt',$now));
    		$data	= M('Member_card_integral')->where($where)->order('create_time desc')->select();
    		foreach ($data as $k=>$n){
    			$data[$k]['info']	= html_entity_decode($n['info']);
    			$data[$k]['count'] 	= 1;
    			//$cwhere = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$thisCard['id'],'coupon_type'=>$type);
    			//$count 	= M('Member_card_coupon_record')->where($cwhere)->count();
    		}
    	}else{
    		$where 	= array('token'=>$this->token,'cardid'=>$thisCard['id'],'attr'=>'0','statdate'=>array('lt',$now),'enddate'=>array('gt',$now));
    		if($type == 1){
    			$where['type'] = 1;
    		}else if($type == 2){
    			$where['type'] = 0;
    		}	
    		$data	= M('Member_card_coupon')->where($where)->order('create_time desc')->select();
    		foreach ($data as $k=>$n){
    			$data[$k]['info']	 	= html_entity_decode($n['info']);
    			$cwhere = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$thisCard['id'],'coupon_type'=>$type,'coupon_id'=>$n['id']);
    			$count 	= M('Member_card_coupon_record')->where($cwhere)->count();
    			$data[$k]['get_count'] 	= $count;
    			$data[$k]['count'] 	= $n['people']-$count;
    		}
    		
    	}

    	$this->assign('firstItemID',$data[0]['id']);
    	$this->assign('list',$data);
    	$this->assign('type',$type);
    	$this->display();
    }
    
    public function action_myCoupon(){
    	$data['use_time'] 		= '';
    	$data['add_time'] 		= time();
    	$data['coupon_id'] 		= $this->_post('coupon_id','intval');
    	$data['cardid'] 		= $this->_post('cardid','intval');
    	$data['token'] 			= $this->token;
    	$data['wecha_id'] 		= $this->wecha_id;
    	$data['coupon_type'] 	= $this->_post('type','intval');
    	$result = array();
    	$now 	= time();
    	if($data['coupon_type'] == 3){  		
    		$integral 	= M('Member_card_integral')->where(array('token'=>$this->token,'cardid'=>$data['cardid'],'id'=>$data['coupon_id']))->find();
    		if($this->thisUser['total_score']<$integral['integral']){
    			$result['err'] 	= -1;
    			$result['info'] = '你的积分不足'.$integral['integral'];
    			echo json_encode($result);
    			exit;
    		}	
    		
    	}else{
    		$where 	= array('token'=>$this->token,'cardid'=>$data['cardid'],'id'=>$data['coupon_id']);
    		if($data['coupon_type'] == 1){
    			$where['type'] = 1;
    		}else if($data['coupon_type'] == 2){
    			$where['type'] = 0;
    		}
    		
    		$coupon	= M('Member_card_coupon')->where($where)->order('create_time desc')->find();
    		$cwhere = array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$data['cardid'],'coupon_type'=>$data['coupon_type'],'coupon_id'=>$data['coupon_id']);
    		$count 	= M('Member_card_coupon_record')->where($cwhere)->count();


    		if($coupon['people']-$count <= 0){
    			$result['err'] 	= -1;
    			$result['info'] = '已经领光了';
    			echo json_encode($result);
    			exit;
    		}
    	}
    	$rid 	= M('Member_card_coupon_record')->add($data);
    	if($rid){
    		if($data['coupon_type'] == 3){
    			$arr			= array();
    			$arr['itemid']	= $rid; //暂取记录id
    			$arr['wecha_id']= $this->wecha_id;
    			$arr['expense']	= 0;
    			$arr['time']	= $now;
    			$arr['token']	= $this->token;
    			$arr['cat']		= 2;
    			$arr['score']	= 0-intval($integral['integral']);
    			M('Member_card_use_record')->add($arr);
    			M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->setDec('total_score',$integral['integral']);
    		}
    		$result['err'] 	= 0;
    		$result['info'] = '领取成功';
    		echo json_encode($result);
    	}

    }
    
    //会员中心-优惠劵
    public function coupon(){
    	$this->assign('infoType','coupon');
    	$thisCard=$this->_thisCard();
    	$this->assign('thisCard',$thisCard);
    	$type 		= $this->_get('type','intval');
    	$is_use 	= $this->_get('is_use','intval')?$this->_get('is_use','intval'):'0';
    	$now=time();
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$thisCard['id'],'coupon_type'=>$type,'is_use'=>"$is_use");
    	 
		$data 	= M('Member_card_coupon_record')->where($where)->field('id,cardid,coupon_id,coupon_type,add_time,is_use')->select();
		
		foreach($data as $key=>$value){
			$cwhere 		= array('token'=>$this->token,'cardid'=>$value['cardid'],'id'=>$value['coupon_id']);
			$cinfo			= M('Member_card_coupon')->where($cwhere)->field('info,pic,statdate,enddate,title,price')->find();
			$cinfo['info'] 	= html_entity_decode($cinfo['info']);
			if($cinfo['enddate']>$now && $cinfo['statdate']<$now){
				$data[$key] = array_merge($value,$cinfo);
			}else{
				unset($data[$key]);
			}
		}
		
    	$this->assign('firstItemID',$data[0]['id']);
    	$this->assign('list',$data);
    	$this->assign('is_use',$is_use);
    	$this->assign('type',$type);
    	$this->display();
    }
    
    public function action_usePrivelege(){
    	if (IS_POST){	
			$paytype = intval($_POST['paytype']);
    		$itemid=intval($_POST['itemid']);
    		$db=M('Member_card_vip');
    		$thisItem=$db->where(array('id'=>$itemid))->find();
    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定特权"}';
    			exit();
    		}
    		//
    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}
    		//
			$userinfo_db=M('Userinfo');
    		$thisUser = $this->thisUser;
			
		if($paytype == 0){

				$staff_db=M('Company_staff');
				$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
	    		
	    		if(empty($thisStaff)){
	    			echo'{"success":-7,"msg":"用户名不存在"}';
	    			exit();
	    		}
	    			
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"商家密码错误"}';
    				exit();
    			}else {

						$now=time();
						//score
						$arr=array();
						$arr['itemid']	= $this->_post('itemid');
						$arr['token']	= $this->token;
						$arr['wecha_id']= $this->wecha_id;
						$arr['expense']	= $this->_post('money');
						$arr['time']	= $now;
						$arr['cat']		= 4;
						$arr['staffid']	= $thisStaff['id'];
						$arr['notes']	= $this->_post('notes','trim');
						$arr['score']	= intval($set_exchange['reward'])*$arr['expense'];
	
						M('Member_card_use_record')->add($arr);
						$userinfo_db=M('Userinfo');
						$thisUser = $this->thisUser;
						$userArr=array();
						$userArr['total_score']=$thisUser['total_score']+$arr['score'];
						$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
						$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
		
						$useCount=intval($thisItem['usetime'])+1;
						$db->where(array('id'=>$itemid))->save(array('usetime'=>$useCount));
						echo'{"success":1,"msg":"数据提交成功"}';
				}
    			
    		}else{   			   			
						$arr['itemid']	= $this->_post('itemid');
						$arr['wecha_id']= $this->wecha_id;
						$arr['expense']	= $_POST['money'];
						$arr['time']	= time();
						$arr['token']	= $this->token;
						$arr['cat']		= 4;
						$arr['staffid']	= 0;
						$arr['usecount']= 1;
						$set_exchange 	= M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
						$arr['score']	= intval($set_exchange['reward'])*$arr['expense'];
						if($arr['expense'] <= 0){
							$this->error('请输入有效的金额');
						}
						
					$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);
						
					$record['orderid'] 	= $single_orderid;
					$record['ordername']= '支付除特权外多余款项';
					$record['paytype'] 	= 'CardPay';
					$record['createtime'] = time();
					$record['paid'] 	= 0;
					$record['price'] 	= $arr['expense'];
					$record['token'] 	= $this->token;
					$record['wecha_id'] = $this->wecha_id;
					$record['type'] 	= 0;
					$result = M('Member_card_pay_record')->add($record);
					if(!$result){
						$this->error('提交记录失败');
					}
					$this->redirect(U('CardPay/pay',array('from'=>'Card','token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id'),'price'=>$arr['expense'],'single_orderid'=>$single_orderid,'orderName'=>'支付除特权外多余款项','redirect'=>'Card/payReturn|itemid:'.$arr['itemid'].',usecount:'.$arr['usecount'].',score:'.$arr['score'].',type:privelege,act=cards,cardid:'.$thisCard['id'])));
			}
    		//
    	}else {
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
    }
    function action_useIntergral(){
    	$now=time();
    	if (IS_POST){  		
    		$rwhere 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>$this->_post('coupon_type','intval'),'id'=>$this->_post('record_id','intval'),'is_use'=>'0');
    		$r_record 	= M('Member_card_coupon_record')->where($rwhere)->find();
    		if (!$r_record){
    			echo'{"success":-8,"msg":"没有找到卷类"}';
    			exit();
    		}
    		$itemid		= $r_record['coupon_id'];
    		$db 		= M('Member_card_integral');
    		
    		$thisItem	= $db->where('id='.$itemid.' AND statdate<'.$now.' AND enddate>'.$now)->find();
    		
    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定信息"}';
    			exit();
    		}
    		

    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}

    		$userinfo_db=M('Userinfo');
    		$thisUser = $this->thisUser;
   		
				$staff_db=M('Company_staff');
				$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
	    		
	    		if(empty($thisStaff)){
	    			echo'{"success":-7,"msg":"用户名不存在"}';
	    			exit();
	    		}
	    			
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"商家密码错误"}';
    				exit();
    			}else {			
    				$arr['notes']	= $this->_post('notes','trim');
    				$arr['staffid']	= $thisStaff['id'];
    				$arr['itemid']	= $itemid;
    				//更新记录
    				M('Member_card_use_record')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'itemid'=>$r_record['id']))->save($arr);
    				$db->where(array('id'=>$itemid))->setInc('usetime',1);
    				//优惠劵使用记录
    				M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1'));
    				echo'{"success":1,"msg":"兑换成功"}';
    			}
    	}else {
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
    }
    function action_useCoupon(){
    	$now=time();
    	
    	if (IS_POST){
    		$rwhere 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>$this->_post('coupon_type','intval'),'id'=>$this->_post('record_id','intval'),'is_use'=>'0');
    		$r_record 	= M('Member_card_coupon_record')->where($rwhere)->find();
    		if (!$r_record){
    			echo'{"success":-8,"msg":"没有找到卷类"}';
    			exit();
    		}
    		$itemid		= $r_record['coupon_id'];
    
			$paytype 	= intval($_POST['paytype']);
    		$db			= M('Member_card_coupon');
    		$thisItem	= $db->where('id='.$itemid.' AND statdate<'.$now.' AND enddate>'.$now)->find();
    		

    		if (!$thisItem){
    			echo'{"success":-2,"msg":"不存在指定信息"}';
    			exit();
    		}
    		
    		$member_card_set_db=M('Member_card_set');
    		$thisCard=$member_card_set_db->where(array('id'=>intval($thisItem['cardid'])))->find();
    		if (!$thisCard){
    			echo'{"success":-3,"msg":"会员卡不存在"}';
    			exit();
    		}
  
    		$userinfo_db= M('Userinfo');
    		$thisUser 	= $this->thisUser;
    	  		
    		$useTime	= 1;

		if($paytype == 0){		
			
			$staff_db=M('Company_staff');
			$thisStaff=$staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();
    		
	    		if(empty($thisStaff)){
	    			echo'{"success":-7,"msg":"用户名不存在"}';
	    			exit();
	    		}	
    			if (md5($this->_post('password'))!=$thisStaff['password']){
    				echo'{"success":-4,"msg":"商家密码错误"}';
    				exit();
    			}else {
						$arr=array();
						$arr['itemid']  	= $itemid;
						$arr['wecha_id']	= $this->wecha_id;
						$arr['expense']		= $this->_post('money');
						$arr['time']		= $now;
						$arr['token']		= $thisItem['token'];
						$arr['cat']			= 1;
						$arr['staffid']		= $thisStaff['id'];
						$arr['usecount']	= $useTime;
						$arr['notes']		= $this->_post('notes','trim');
						$set_exchange 		= M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
						$arr['score'] 		= intval($set_exchange['reward'])*$arr['expense'];

						M('Member_card_use_record')->add($arr);						
						$userArr=array();
						$userArr['total_score']=$thisUser['total_score']+$arr['score'];
						$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
						$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
						
						$db->where(array('id'=>$itemid))->setInc('usetime',1);
						//优惠劵使用记录
						M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1'));
						
						echo'{"success":1,"msg":"线下支付成功"}';	
						exit;
				}
   
		}else{		
					$arr['itemid']  	= $itemid;
					$arr['wecha_id']	= $this->wecha_id;
					$arr['expense']		= $_POST['money'];
					$arr['time']		= $now;
					$arr['token']		= $thisItem['token'];
					$arr['cat']			= 1;
					$arr['staffid']		= 0;
					$arr['usecount']	= $useTime;
					$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
					$arr['score']=intval($set_exchange['reward'])*$arr['expense'];
						
					if($arr['expense'] <= 0){
						$this->error('请输入有效的金额');
					}
						
					$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);
				
					$record['orderid'] = $single_orderid;
					$record['ordername'] = '支付除优惠劵外多余款项';
					$record['paytype'] = 'CardPay';
					$record['createtime'] = time();
					$record['paid'] = 0;
					$record['price'] = $arr['expense'];
					$record['token'] = $this->token;
					$record['wecha_id'] = $this->wecha_id;
					$record['type'] = 0;
					$result = M('Member_card_pay_record')->add($record);
					$db->where(array('id'=>$itemid))->setInc('usetime',1);
					if(!$result){
						echo'{"success":-6,"msg":"提交失败"}';
					}
					
					//优惠劵使用记录
					M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1'));
					$this->redirect(U('CardPay/pay',array('from'=>'Card','token'=>$this->token,'wecha_id'=>$this->wecha_id,'price'=>$arr['expense'],'single_orderid'=>$single_orderid,'orderName'=>'支付除优惠劵外多余款项','redirect'=>'Card/payReturn|itemid:'.$itemid.',usecount:'.$arr['usecount'].',score:'.$arr['score'].',type:coupon,act:cards,cardid:'.$thisCard['id'])));
			}
   	 	}else {		
    		echo'{"success":-1,"msg":"不是post数据"}';
    	}
	}
    public function expense(){
    	$userinfo_db=M('Userinfo');
    	$userInfos=$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->select();
    	$userScore=0;
    	if ($userInfos){
    		$userScore=intval($userInfos[0]['total_score']);
    		$userInfo=$userInfos[0];
    	}
    	$this->assign('userInfo',$userInfo);
    	$this->assign('userScore',$userScore);
    	//
    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	$this->assign('thisCard',$thisCard);
    	//
    	
    	$db   = M('Member_card_use_record');
    	$now=time();
    	$day=date('d',$now);
    	$year=date('Y',$now);
    	$month=date('m',$now);
    	if (isset($_GET['month'])){
    		$month=intval($_GET['month']);
    	}
			$nowY = date('Y');
			$start = strtotime($nowY."-".$month."-01");
			$last = strtotime(date('Y-m-d',$start)." +1 month -1 day");
    	$records=$db->where('token=\''.$this->token.'\' AND wecha_id=\''.$this->wecha_id.'\' AND time>'.$start.' AND time<'.$last)->order('time DESC')->select();
    	$this->assign('records',$records);
    	//
    	$this->display();
    }
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
	public function request(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//卡号
			$card=M('member_card_create')->where(array('token'=>$token))->order('id asc')->find();
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);			
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function get_card(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
		if($card){
			header('Location:'.rtrim(C('site_url'),'/').U('Wap/Card/vip',array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		
		
		
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		
		$get_card=M('member_card_create')->where(array('wecha_id'=>$wecha_id))->find();

		if($get_card!=false){
			Header("Location: ".C('site_url').'/'.U('Wap/Card/vip',array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))); 
		}
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//联系方式
		
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$contact=M('company')->where(array('token'=>$token,'branch'=>0))->find();
			$this->assign('contact',$contact);
			$this->assign('info',$info);
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function info(){
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			$info['description']=nl2br($info['description']);
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			//我的卡号
			$mycard=M('member_card_create')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->find();
			$this->assign('mycard',$mycard);
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);
		}else{
			$this->error('无此信息');
		}
		$this->display();	
    }

	public function vip(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
		if($card==false){
			header('Location:'.rtrim(C('site_url'),'/').U('Wap/Card/get_card',array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		//
	   $agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}

		 
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			$info=M('member_card_info')->where(array('token'=>$token))->find();
			//卡号
			$card=M('member_card_create')->where(array('token'=>$token,'wecha_id'=>$this->_get('wecha_id')))->find();
			//var_dump($card);exit;
			//dump(array('token'=>$token,'wecha_id'=>$this->get('wecha_id')));
			//联系方式
			$contact=M('company')->where(array('token'=>$token,'branch'=>0))->find();
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('info',$info);			
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//dump($data);
			$this->assign('card',$data);
			//特权服务
			$vip=M('member_card_vip')->where(array('token'=>$token))->order('id desc')->find();
			//dump($vip);
			$this->assign('vip',$vip);
			//优惠卷
			$coupon=M('member_card_coupon')->where(array('token'=>$token))->find();
			$this->assign('coupon',$coupon);
			//兑换
			$integral=M('member_card_integral')->where(array('token'=>$token))->find();
			$this->assign('integral',$integral);
		}else{
			$this->error('无此信息');
		}
	
		$this->display();
	
	}
	public function addr(){
	$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"MicroMessenger")) {
			echo '此功能只能在微信浏览器中使用';exit;
		}
	
		$token=$this->_get('token');
		if($token!=false){
			//会员卡信息
			$data=M('member_card_set')->where(array('token'=>$token))->find();
			//商家信息
			//$addr=M('member_card_contact')->where(array('token'=>$token))->select();
			//if (!$addr){
			$addr=M('Company')->where(array('token'=>$token))->order('isbranch ASC')->select();
			if ($addr){
				$i=0;
				foreach ($addr as $a){
					$addr[$i]['info']=$a['address'];
					$addr[$i]['tel']=$a['tel'];
					$i++;
				}
			}
			//}
			//联系方式
			$contact=M('member_card_contact')->where(array('token'=>$token))->order('sort desc')->find();
			//我的卡号
			$mycard=M('member_card_create')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->_get('wecha_id')))->find();
			$this->assign('mycard',$mycard);
			$this->assign('card',$data);
			$this->assign('card_info',$card);
			$this->assign('contact',$contact);
			$this->assign('addr',$addr);
		}else{
			$this->error('无此信息');
		}
		$this->display();
	
	}
	//充值页面
	public function topay(){
		$config = M('Alipay_config')->where(array('token'=>$this->token))->find();
		
		$info['cardid'] = $this->_get('cardid','intval');
		$info['token'] = $this->_get('token');
		$info['wecha_id'] = $this->_get('wecha_id');
		$member_card_set_db=M('Member_card_set');
		$member_card_create_db=M('Member_card_create');
		$thisCard=$member_card_set_db->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
		$card = $member_card_create_db->field('number')->where(array('token'=>$info['token'],'wecha_id'=>$info['wecha_id']))->find();
		$company_model=M('Company');
		
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$this->assign('cardsCount',$cardsCount);
		$token = $this->token;
		$thisCompany=$company_model->where("token = '$token'")->find();
		
		
		$this->assign('thisCompany',$thisCompany);
		$this->assign('info',$info);
		$this->assign('card',$card);
		$this->assign('thisCard',$thisCard);
		$this->display();
	}
	
	public function consume(){
		$now 	= time();
		$config = M('Alipay_config')->where(array('token'=>$this->token))->find();
		$cardid = $this->_get('cardid','intval');
		$now=time();
		
		$thisCard=M('Member_card_set')->where(array('token'=>$this->token,'id'=>$cardid))->find();
		
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'cardid'=>$cardid,'coupon_type'=>array('lt','3'),'is_use'=>'0');
		$data 	= M('Member_card_coupon_record')->where($where)->field('id,cardid,coupon_id,coupon_type,add_time,is_use')->select();
		
		foreach($data as $key=>$value){
			$cwhere 		= array('token'=>$this->token,'cardid'=>$value['cardid'],'id'=>$value['coupon_id']);
			$cinfo			= M('Member_card_coupon')->where($cwhere)->field('info,pic,statdate,enddate,title,price')->find();
			$cinfo['info'] 	= html_entity_decode($cinfo['info']);
			if($cinfo['enddate']>$now && $cinfo['statdate']<$now){
				$data[$key] = array_merge($value,$cinfo);
			}else{
				unset($data[$key]);
			}
		}

		$useTime=1;
		
		if(IS_POST){	
			$rwhere 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'coupon_type'=>array('lt','3'),'id'=>$this->_post('consume_id','intval'),'is_use'=>'0');
			$r_record 	= M('Member_card_coupon_record')->where($rwhere)->find();
			if (!$r_record){
				$r_record['coupon_id'] = 0;
			}

			$itemid		= $r_record['coupon_id'];
			$price 			= $this->_post('price','floatval');
			$consume_id 	= $this->_post('consume_id','intval');
			$pay_type 		= $this->_post('pay_type','intval');
			$card_pwd 		= $this->_post('card_pwd','trim');
			$com_pwd 		= $this->_post('com_pwd','trim');
			
			if(empty($price) || empty($pay_type)){
				$this->error('请填写完整的信息');
				exit;
			}

			if($pay_type == 1){
				$paypass 	= $this->fans['paypass'];		
					$arr['itemid']		= $itemid;
					$arr['wecha_id']	= $this->wecha_id;
					$arr['expense']		= $price;
					$arr['time']		= $now;
					$arr['token']		= $this->token;
					$arr['cat']			= 1;
					$arr['staffid']		= 0;
					$arr['usecount']	= 1;
						
					$set_exchange = M('Member_card_exchange')->where(array('cardid'=>$cardid))->find();
					$arr['score']=intval($set_exchange['reward'])*$arr['expense'];
						
					$single_orderid = date('YmdHis',time()).mt_rand(1000,9999);
					
					$record['orderid'] 		= $single_orderid;
					$record['ordername'] 	= $itemid==0?'会员卡现金支付':'现金支付除优惠劵外的款项';
					$record['paytype'] 		= 'CardPay';
					$record['createtime'] 	= time();
					$record['paid'] 		= 0;
					$record['price'] 		= $arr['expense'];
					$record['token'] 		= $this->token;
					$record['wecha_id'] 	= $this->wecha_id;
					$record['type'] 		= 0;
					
					M('Member_card_coupon')->where(array('id'=>$itemid))->setInc('usetime',1);
					$result = M('Member_card_pay_record')->add($record);	
					M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1'));
					$this->redirect(U('CardPay/pay',array('from'=>'Card','token'=>$this->token,'wecha_id'=>$this->wecha_id,'price'=>$arr['expense'],'single_orderid'=>$single_orderid,'orderName'=>'支付除优惠劵外多余款项','redirect'=>'Card/payReturn|itemid:'.$itemid.',usecount:'.$arr['usecount'].',score:'.$arr['score'].',type:coupon,cardid:'.$cardid)));
					exit;
			}else{				

				$staff_db	= M('Company_staff');
				$thisStaff	= $staff_db->where(array('username'=>$this->_post('username'),'token'=>$thisCard['token']))->find();

	    		if(empty($thisStaff)){
	    			$this->error('商家名称不存在');
					exit;
	    		}	
	    		
    			if (md5($this->_post('password')) == $thisStaff['password']){
					
    				$arr=array();
					$arr['itemid']		= $itemid;
					$arr['wecha_id']	= $this->wecha_id;
					$arr['expense']		= $price;
					$arr['time']		= $now;
					$arr['token']		= $this->token;
					$arr['cat']			= 0;
					$arr['notes']		= $this->_post('notes','trim');
					$arr['staffid']		= $thisStaff['id'];
					$arr['usecount']	= 1;
				
					$set_exchange = M('Member_card_exchange')->where(array('cardid'=>$cardid))->find();
					$arr['score'] = intval($set_exchange['reward'])*$arr['expense'];
					
					$userArr=array();
					$userArr['total_score']		= $this->fans['total_score']+$arr['score'];
					$userArr['expensetotal']	= $this->fans['expensetotal']+$arr['expense'];

					M('Member_card_use_record')->add($arr);
					M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($userArr);
					M('Member_card_coupon')->where(array('id'=>$itemid))->setInc('usetime',1);					
					M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1'));
					$this->success('支付成功');
					exit;
				}else{
					$this->error('商家密码错误!');
					exit;
				}
			}
			
			
			
		}else{
			$card = M('Member_card_create')->field('number')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
			$this->assign('card',$card);
			$this->assign('consume_info',$data);
			$this->assign('thisCard',$thisCard);
			$this->display();		
		}	

	}
	//充值处理
	public function payAction(){
		$price 		= $_POST['price'];
		$orderid 	= $this->_get('orderid');
		$record 	= M('Member_card_pay_record');
		if($orderid == '' && $price <= 0){
			$this->error('请填写正确的充值金额');
		}

		$token = $this->_get('token');
		$wecha_id = $this->_get('wecha_id');
		if($orderid != ''){
			$res = $record->where("token = '$token' AND wecha_id = '$wecha_id' AND orderid = $orderid AND paid = 0")->find();
		
			if($res){
				$this->success('提交成功，正在跳转支付页面..',U('Alipay/pay',array('from'=>'Card','orderName'=>$res['ordername'],'single_orderid'=>$res['orderid'],'token'=>$res['token'],'wecha_id'=>$res['wecha_id'],'price'=>$res['price'])));
			}else{
				$this->error('无此订单');
			}
		}
	
		
		$_POST['wecha_id'] = $wecha_id;
		$_POST['token'] = $token;
		$_POST['createtime'] = time();
		$_POST['orderid'] = date('YmdHis',time()).mt_rand(1000,9999);
		$_POST['ordername'] = $_POST['number'].' 充值';
		
		if($record->create($_POST)){
			if($record->add($_POST)){
				$this->success('提交成功，正在跳转支付页面..',U('Alipay/pay',array('from'=>'Card','orderName'=>$_POST['ordername'],'single_orderid'=>$_POST['orderid'],'token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],'price'=>$price)));
			}
		}else{
			$this->error('系统错误');
		}
		
	}
	//支付返回
	public function payReturn(){
		$act = $_GET['act'];
		$cardid = $_GET['cardid'];
		$orderid = $_GET['orderid'];
		$token = $_GET['token'];
		$wecha_id = $_GET['wecha_id'];
		$record = M('member_card_pay_record');
		$order = $record->where("orderid = '$orderid' AND token = '$token' AND wecha_id = '$wecha_id'")->find();
		
		if($order){
			if($order['paid'] == 1){
				$record->where("orderid = '$orderid'")->setField('paytime',time());
				if($order['type'] == 1){
					M('Userinfo')->where("wecha_id = '$wecha_id' AND token = '$token'")->setInc('balance',$order['price']);
				}else{
					$lastid = M('Member_card_use_record')->where(array('token'=>$this->token,'wecha_id'=>$wecha_id))->order('id DESC')->getField('id');
					if($this->_get('type') == 'coupon'){
						M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>(int)$this->_get('itemid')))->setInc('usetime',(int)$this->_get('usecount'));
						M('Member_card_use_record')->where(array('token'=>$this->token,'id'=>$lastid))->setField(array('usecount'=>(int)$this->_get('usecount'),'cat'=>1));
					}elseif($this->_get('type') == 'privelege'){
						M('Member_card_vip')->where(array('token'=>$this->token,'id'=>(int)$this->_get('itemid')))->setInc('usetime');
						M('Member_card_use_record')->where(array('token'=>$this->token,'id'=>$lastid))->setField('cat',4);
					}
					
				}
				if(empty($act)){
					$this->success('支付成功',U('Card/card',array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$cardid)));
				}else{
					$this->success('支付成功',U('Card/'.$act,array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$cardid)));
				}
				
			}else{
				exit('支付失败');
			}
		
		}else{
			exit('订单不存在');
		}
	
	}
	
	//充值消费记录
	public function payRecord(){

		
		$token = $this->token;
		$wecha_id = $this->wecha_id;

		$record = M('Member_card_pay_record');

    	$member_card_set_db=M('Member_card_set');
    	$thisCard=$member_card_set_db->where(array('token'=>$token,'id'=>intval($_GET['cardid'])))->find();

		$m = $this->_get('month','intval');
		
		if($m != ''){
			$nowY = date('Y');
			$start = strtotime($nowY."-".$m."-01");
			$last = strtotime(date('Y-m-d',$start)." +1 month -1 day");
			$list = $record->where("token = '$token' AND wecha_id = '$wecha_id' AND createtime < $last AND createtime > $start")->order('createtime DESC')->select();
		}else{
			$list = $record->where("token = '$token' AND wecha_id = '$wecha_id'")->order('createtime DESC')->select();
		}

		
		$balance = M('Userinfo')->field('balance')->where("token = '$token' AND wecha_id = '$wecha_id'")->find();
		

		
    	$member_card_create_db=M('Member_card_create');
		$company_model=M('Company');
		$cardsCount=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
		$thisCompany=$company_model->where("token = '$token'")->find();
		
		$this->assign('thisCompany',$thisCompany);
		
		$this->assign('cardsCount',$cardsCount);
		
		$this->assign('balance',$balance['balance']);
		$this->assign('thisCard',$thisCard);
		$this->assign('list',$list);
		$this->assign('cardid',$this->_get('cardid','intval'));
		$this->display();
	}
}
?>