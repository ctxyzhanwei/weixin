<?php
class Member_cardAction extends UserAction{
	public $member_card_set_db;
	public $thisCard;
	public function _initialize() {
		parent::_initialize();
		$this->assign('token',$this->token);
		//
		$this->canUseFunction('huiyuanka');
		//权限
		if ($this->token!=$_GET['token']){
			//$this->error('非法操作');
		}
		$this->wxuser_db=M("Wxuser");
		//获取所在组的开卡数量
		$thisWxUser=$this->wxuser_db->where(array('token'=>$this->token))->find();
		$thisUser=$this->user;
		$thisGroup=$this->userGroup;
		$this->wxuser_db->where(array('token'=>$this->token))->save(array('allcardnum'=>$thisGroup['create_card_num']));
		//总数
		//if (!$thisUser['card_num']){
			$allcards=M('Member_card_create')->where(array('token'=>$this->token))->select();
			
			$cardTotal=count($allcards);
		
			M('Users')->where(array('id'=>$thisUser['id']))->save(array('card_num'=>$cardTotal));
			M('Wxuser')->where(array('token'=>$this->token))->save(array('yetcardnum'=>$cardTotal));
		//}else {
			//$cardTotal=$thisUser['card_num'];
		//}
		//
		$can_cr_num = $thisWxUser['allcardnum'] - $cardTotal;
		if($can_cr_num > 0){
			$data['cardisok'] = 1;
		}else{
			$data['cardisok'] = 0;
		}
		$this->wxuser_db->where(array('uid'=>session('uid'),'token'=>session('token')))->save($data);
		//
		$this->member_card_set_db=M('Member_card_set');
		//
		$id=intval($_GET['id']);
		if ($id){
			$this->thisCard=$this->member_card_set_db->where(array('id'=>$id))->find();
			if ($this->thisCard&&$this->thisCard['token']!=$this->token){
				$this->error('非法操作');
			}
			$this->assign('thisCard',$this->thisCard);
		}
		
		//transfer start
		$data=M('Member_card_create');
		$cardByToken=$this->member_card_set_db->where(array('token'=>$this->token))->order('id ASC')->find();
		if ($cardByToken){
			$data->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_exchange')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_coupon')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_vip')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_integral')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		}
		//transfer end
		$type 	= $this->_get('type','intval');
		$this->assign('type',$type?$type:1);
	}
	public function index(){
		$cards=$this->member_card_set_db->where(array('token'=>$this->token))->order('id ASC')->select();
		if ($cards){
			$card_create_data=M('Member_card_create');
			$i=0;
			foreach ($cards as $card){
				$cards[$i]['usercount']=$card_create_data->where('cardid='.intval($card['id']).' AND token="'.$this->token.'" AND wecha_id!=""')->count();
				$cards[$i]['cardcount']=$card_create_data->where('cardid='.intval($card['id']).' AND token="'.$this->token.'"')->count();
				$i++;
			}
		}
		$this->assign('cards',$cards);
		$this->display();
	}
	public function replyInfoSet(){
		$where_member=array('token'=>$this->token,'infotype'=>'membercard');
		$reply_info_db=M('Reply_info');
		$memberConfig=$reply_info_db->where($where_member)->find();
		$where_unmember=array('token'=>$this->token,'infotype'=>'membercard_nouse');
		$unmemberConfig=$reply_info_db->where($where_unmember)->find();
		if (IS_POST){
			$memberArr=array();
			$nomemberArr=array();
			$memberArr['title']=$this->_post('title');
                        $memberArr['money_chg_send_sms']=$this->_post('money_chg_send_sms');
                        $memberArr['integral_chg_send_sms']=$this->_post('integral_chg_send_sms');
			$memberArr['info']=$this->_post('info');
			$memberArr['picurl']=$this->_post('picurl');
			$memberArr['token']=$this->token;
			$memberArr['apiurl']=$this->_post('apiurl');
			$memberArr['infotype']='membercard';
			
			$nomemberArr['title']=$this->_post('title1');
                        $nomemberArr['money_chg_send_sms']=$this->_post('money_chg_send_sms');
                        $nomemberArr['integral_chg_send_sms']=$this->_post('integral_chg_send_sms');                        
			$nomemberArr['info']=$this->_post('info1');
			$nomemberArr['picurl']=$this->_post('picurl1');
			$nomemberArr['token']=$this->token;
			$nomemberArr['apiurl']=$this->_post('apiurl');
			$nomemberArr['infotype']='membercard_nouse';
			//
			$where=array('token'=>$this->token);
			//
			if ($memberConfig){
				$reply_info_db->where($where_member)->save($memberArr);
			}else {
				$reply_info_db->add($memberArr);
			}
			//
			if ($unmemberConfig){
				$reply_info_db->where($where_unmember)->save($nomemberArr);
			}else {
				$reply_info_db->add($nomemberArr);
			}
			$this->success('操作成功');
		}else {
			if (!$memberConfig){
				$memberConfig['picurl']=rtrim(C('site_url'),'/').'/tpl/static/images/member.jpg';
				$memberConfig['title']='会员卡,省钱，打折,促销，优先知道,有奖励哦';
				$memberConfig['info']='尊贵vip，是您消费身份的体现,会员卡,省钱，打折,促销，优先知道,有奖励哦';
                                $memberConfig['money_chg_send_sms'] = $memberConfig['integral_chg_send_sms'] = 0;
			}
			if (!$unmemberConfig){
				$unmemberConfig['picurl']=rtrim(C('site_url'),'/').'/tpl/static/images/vip.jpg';
				$unmemberConfig['title']='申请成为会员';
				$unmemberConfig['info']='申请成为会员，享受更多优惠';
                                $unmemberConfig['money_chg_send_sms'] = $unmemberConfig['integral_chg_send_sms'] = 0;
			}
			$this->assign('set',$memberConfig);
			$this->assign('set2',$unmemberConfig);
			$this->display();
		}
	}
	//会员卡配置
	public function design(){
		$data=$this->thisCard;
		if(IS_POST){
			$_POST['token']=$this->token;			
			if($data==false){				
				$this->all_insert('Member_card_set');
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_set');
			}
		}else{
			if($data==false){
				$data=array (
				'token' => $this->token,
				'cardname' => C('site_name').'会员卡',
				'logo' => '/tpl/User/default/common/images/cart_info/logo-card.png',
				'bg' => './tpl/User/default/common/images/card/card_bg15.png',
				//'diybg' => '/tpl/User/default/common/images/card/card_bg17.png',
				'msg' => '微信会员卡，方便携带收藏，永不挂失',
				'numbercolor' => '#000000',
				'vipnamecolor' => '#121212',
				'Lastmsg' => '/tpl/User/default/common/images/cart_info/news.jpg',
				'vip' => '/tpl/User/default/common/images/cart_info/vippower.jpg',
				'qiandao' => '/tpl/User/default/common/images/cart_info/qiandao.jpg',
				'shopping' => '/tpl/User/default/common/images/cart_info/shopping.jpg',
				'memberinfo' => '/tpl/User/default/common/images/cart_info/user.jpg',
				'membermsg' => '/tpl/User/default/common/images/cart_info/vippower.jpg',
				'contact' => '/tpl/User/default/common/images/cart_info/addr.jpg',
				'recharge' => '/tpl/User/default/common/images/cart_info/recharge.jpg',
				'payrecord' => '/tpl/User/default/common/images/cart_info/payrecord.jpg',
				);
			}
			
			$this->assign('card',$data);
			$this->display();
		}
	}
	//生成会员卡列表
	public function create(){
		$data=M('Member_card_create');
		if (IS_POST){//删除操作
			for ($i=0;$i<50;$i++){
				$thisInfo=$data->where(array('id'=>$_POST['id_'.$i]))->find();
				if ($thisInfo['wecha_id']){
					M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$thisInfo['wecha_id']))->delete();
				}
				$data->where(array('id'=>$_POST['id_'.$i]))->delete();
				//删除的时候得删除userinfo里的信息
				
			}
			$this->success('删除成功',U('Member_card/create',array('token'=>$this->token,'id'=>$this->thisCard['id'])));
		}else {
			$where=array('token'=>$this->token,'cardid'=>$this->thisCard['id']);
			$count      = $data->where($where)->count();
			$Page       = new Page($count,15);
			$show       = $Page->show();
			$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
			//计算领取张数
			$where['wecha_id']=array('neq','');
			$usecount =M('member_card_create')->where($where)->count();

			$this->assign("usecount",$usecount);
			$this->assign("count",$count);
			$this->assign("ucount",$count - $usecount);
			$this->assign('page',$show);
			$this->assign('data_vip',$list);
			$this->display();
		}
	}
	//创建会员卡
	public function create_add(){ 
		$card=M("Wxuser")->where(array('uid'=>session('uid'),'token'=>$_SESSION['token']))->find();
		if($card['cardisok'] == 0){ //不可以创建
		 	$this->error('您的开卡数量已经用完.请充值或者续费升级.');
		 	return;
		} 
		$markcard = M("Wxuser")->where(array('uid'=>session('uid'),'token'=>$this->token))->find();
		$can_cr_num = $markcard['allcardnum'] - $markcard['yetcardnum'];
		if(IS_POST){
			$end=(int)$_POST['end'];			
			$stat=(int)$_POST['stat'];
		 
			if($end==false||$stat==false){$this->error('卡号起始值或结束值都不能为空');}

			if($end > 65535 || $stat <= 0){
				$this->error('卡号起始值不能为0或结束值不能超过65535');
				return;
			}
			
			$num=$end - $stat;
			if($num <=0 ){
				$this->error('开始卡号不能大于结束卡号');
				return;
			}
			
			//echo $num.'-'.$group_cread_num['create_card_num'];exit;
			if(($num>$can_cr_num)){
			 $this->error('你还有'.$can_cr_num.'张卡的创建名额，请不要超过这个数字');exit;
			}	

			//------------------------------------
			// tp_wxuser 
			//------------------------------------
			$j=0;
			for($i=1;$i<=$num;$i++){
				 $data['number']=$_POST['title'].($stat+=1);
				 $data['token']=session('token');
				 $data['cardid']=$this->thisCard['id'];
				 $check=M('member_card_create')->where(array('cardid'=>$this->thisCard['id'],'number'=>$data['number']))->find();
				 if (!$check){
				 	$rt=M('member_card_create')->data($data)->add();
				 	if ($rt){
				 		$j++;
				 	}
				 }
			}		

  			$back = M('Wxuser')->where(array('uid'=>session('uid'),'token'=>session('token')))->setInc('yetcardnum',$j);
  			M('Users')->where(array('id'=>$this->user['id']))->setInc('card_num',$j);
  			M('Wxuser')->where(array('uid'=>session('uid'),'token'=>session('token')))->setInc('totalcardnum',$j);
			
			if($back!=false){
				$this->success('恭喜您共开了'.$j.'张会员卡',U('Member_card/create',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
			}else{
				$this->error('没开成任何会员卡');
			}
		}else{
			
			$this->assign('count',$markcard['allcardnum']);
			$this->assign('cancrnum',$can_cr_num);
			$this->display();
		}
	}
	/**
	 *开卡赠送
	 *
	 */
	public function gifts(){
		$cardid = $this->_get('id','intval');
		$where	= array('token'=>$this->token,'cardid'=>$cardid);
		$count  = M('Member_card_gifts')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Member_card_gifts')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key=>$value){
			if($value['type'] == '2'){
				if($value['item_attr'] == '3'){
					$item_name 	= M('member_card_integral')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$value['item_value']))->getField('title');
				}else{
					$item_name 	= M('Member_card_coupon')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$value['item_value']))->getField('title');
				}
				$list[$key]['item_name'] = $item_name;
			}	
		}
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add_gifts(){
		$cardid 	= $this->_get('id','intval');
		$gid 		= $this->_get('gid','intval');
		if(IS_POST){
			if(D('Member_card_gifts')->create()){
					$_POST['cardid']= $cardid;
					$_POST['token'] = $this->token;
					$_POST['start'] = strtotime($this->_post('start','trim'));
					$_POST['end'] 	= strtotime($this->_post('end','trim'));
					if(D('Member_card_gifts')->add($_POST)){
						$this->success('添加成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$cardid)));
					}
			}else{
				$this->error(D('Member_card_gifts')->getError());
			}
		}else{
			$this->display();
		}

	}
	
	public function edit_gifts(){
		$cardid 	= $this->_get('id','intval');
		$gid 		= $this->_get('gid','intval');
		$info 		= D('Member_card_gifts')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$gid))->find();

		$item_name 	= M('Member_card_coupon')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$info['item_value']))->getField('title');
		$info['item_name'] 	= $item_name;

		if(IS_POST){
			if(D('Member_card_gifts')->create()){
				$_POST['start'] = strtotime($this->_post('start','trim'));
				$_POST['end'] 	= strtotime($this->_post('end','trim'));
				$where 	= array('token'=>$this->token,'cardid'=>$cardid,'id'=>$this->_post('gid','intval'));		
				D('Member_card_gifts')->where($where)->save($_POST);
				$this->success('修改成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$cardid)));
			}else{
				$this->error(D('Member_card_gifts')->getError());
			}
		}else{
			$this->assign('set',$info);
			$this->display();
		}
	}

	public function del_gifts(){
		$id 	= $this->_get('id','intval');
		$gid 	= $this->_get('gid','intval');
		$where  = array('id'=>$gid,'token'=>$this->token,'cardid'=>$id);
		if(M('Member_card_gifts')->where($where)->delete()){
			$this->success('删除成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$id)));
		}
	}
	
	public function get_value(){	
		$cardid 	= $this->_get('cardid','intval');
		$item_attr 	= $this->_get('item_attr','intval');
		$now 		= time();
		$result 	= array();
		if($item_attr == 1){
			$result['err'] 	= 0;
			$result['info'] = M('Member_card_coupon')->where(array('token'=>$this->token,'cardid'=>$cardid,'attr'=>'1','type'=>1,'statdate'=>array('lt',$now),'enddate'=>array('gt',$now)))->field('id,title')->select();	
		}else if($item_attr == 2){
			$result['err'] 	= 0;
			$result['info'] = M('Member_card_coupon')->where(array('token'=>$this->token,'cardid'=>$cardid,'attr'=>'1','type'=>0,'statdate'=>array('lt',$now),'enddate'=>array('gt',$now)))->field('id,title')->select();	
		}else{
			$result['err'] 	= 1;
			$result['info'] = '';
		}		
		echo json_encode($result);
	}
	/**
	*积分设置 设置会员卡积分策略及会员卡级别
	*
	*/
	public function exchange(){
		$data=M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->find();
		if(IS_POST){
			$_POST['token']=$this->token;
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['create_time'] = time();		
			if($data==false){				
				$this->all_insert('Member_card_exchange','/exchange?token='.$this->token.'&id='.$this->thisCard['id']);
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_exchange','/exchange?token='.$this->token.'&id='.$this->thisCard['id']);
			}
		}else{
			$this->assign('exchange',$data);
			$this->display();
		}	
	}
	public function notice(){
		$member_card_notice_db=M('Member_card_notice');
		$where=array('cardid'=>$this->thisCard['id']);
		$count      = $member_card_notice_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list = $member_card_notice_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('time desc')->select();
		
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	function noticeSet(){
		$member_card_notice_db=M('Member_card_notice');
		if (IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['time']=time();
			$enddates=explode('-',$_POST['enddate']);
			$_POST['endtime']=mktime(23,59,59,$enddates[1],$enddates[2],$enddates[0]);
			if (!isset($_GET['noticeid'])){
				$member_card_notice_db->add($_POST);
			}else {
				$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->save($_POST);
			}
			$this->success('设置成功',U('Member_card/notice',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else {
			if (isset($_GET['noticeid'])){
				$thisNotice=$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->find();
			}else {
				$thisNotice['endtime']=time()+10*24*3600;
			}
			$this->assign('thisNotice',$thisNotice);
			$this->display('noticeAdd');
		}
	}
	function noticeDelete(){
		$member_card_notice_db=M('Member_card_notice');
		$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->delete();
		$this->success('操作成功',U('Member_card/notice',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
	}
	public function privilege(){
		$member_card_vip=M('Member_card_vip');
		
		$data=M('Member_card_vip')->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function privilege_add(){
		$member_card_vip=M('Member_card_vip');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['token']=$this->thisCard['token'];
			$_POST['create_time']=time();
			$enddates=explode('-',$_POST['enddate']);
			$_POST['enddate']=0;
			if (!$_POST['type']){
				$_POST['enddate']=mktime(23,59,59,$enddates[1],$enddates[2],$enddates[0]);
			}
			//
			$startdates=explode('-',$_POST['statdate']);
			$_POST['statdate']=0;
			if (!$_POST['type']){
				$_POST['statdate']=mktime(0,0,0,$startdates[1],$startdates[2],$startdates[0]);
			}
			if (!isset($_GET['itemid'])){
				$member_card_vip->add($_POST);
			}else {
				$member_card_vip->where(array('id'=>intval($_GET['itemid'])))->save($_POST);
			}
			$this->success('操作成功',U('Member_card/privilege',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else{
			if (isset($_GET['itemid'])){
				$thisItem=$member_card_vip->where(array('id'=>intval($_GET['itemid'])))->find();
			}else {
				//$thisNotice['endtime']=time()+10*24*3600;
			}
			$this->assign('vip',$thisItem);
			$this->display('privilege_add');
		}
	}
	public function privilege_del(){
		$this->_isUseRecordExist(1,$_GET['itemid']);
		$member_card_vip=M('Member_card_vip');
		$data=$member_card_vip->where(array('token'=>$this->token,'id'=>$this->_get('itemid')))->delete();
		if($data==false){
			$this->error('服务器繁忙请稍后再试');
		}else{
			$this->success('操作成功',U('Member_card/privilege',array('id'=>$this->thisCard['id'],'token'=>$this->token)));
		}
	}
	//会员优惠卷
	public function coupon(){
		$member_card_coupon_db=M('Member_card_coupon');
		$data=$member_card_coupon_db->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function coupon_edit(){
		$member_card_coupon_db=M('Member_card_coupon');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			if (!isset($_GET['itemid'])){
				$this->all_insert('Member_card_coupon','/coupon?id='.$this->thisCard['id']);
			}else {
				$this->all_save('Member_card_coupon','/coupon?id='.$this->thisCard['id']);
			}
		}else{
			$now=time();
			if (isset($_GET['itemid'])){
				$data=$member_card_coupon_db->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->find();
			}else {
				$data['statdate']=$now;
				$data['enddate']=$now+10*24*3600;;
			}
			$this->assign('vip',$data);
			$this->display('coupon_edit');
		}
		
	}	
	public function coupon_del(){
		$this->_isUseRecordExist(3,$_GET['itemid']);
			$data=M('Member_card_coupon')->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
			if($data==false){
				$this->error('没删除成功');
			}else{
				$this->success('操作成功',U('Member_card/coupon',array('id'=>$this->thisCard['id'])));
			
			}
	}
	//会员礼卷
	public function integral(){
		$member_card_inergral_db=M('Member_card_integral');
		$data=$member_card_inergral_db->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function integral_edit(){
		$member_card_inergral_db=M('Member_card_integral');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			if (isset($_GET['itemid'])){
				$this->all_save('Member_card_integral','/integral?id='.$this->thisCard['id']);
			}else {
				$this->all_insert('Member_card_integral','/integral?id='.$this->thisCard['id']);
			}
		}else{
			$now=time();
			if (isset($_GET['itemid'])){
				$data=$member_card_inergral_db->where(array('token'=>$this->token,'id'=>$this->_get('itemid')))->find();
			}else {
				$data['statdate']=$now;
				$data['enddate']=$now+10*24*3600;;
			}
			$this->assign('vip',$data);
			$this->display();
			
		}
		
	}	

	public function integral_del(){
		$this->_isUseRecordExist(2,$_GET['itemid']);
			$data=M('Member_card_integral')->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
			if($data==false){
				$this->error('服务器繁忙请稍后再试');
			}else{
				$this->success('操作成功',U('Member_card/integral',array('id'=>$this->thisCard['id'])));
			
			}
	}
	public function staff(){
		$company_staff_db=M('Company_staff');
		$data=$company_staff_db->where(array('token'=>$this->token))->order('id desc')->select();
		$company_db=M('Company');
		$companys=$company_db->where(array('token'=>$this->token))->order('id ASC')->select();
		//
		$companysByID=array();
		if ($companys){
			foreach ($companys as $c){
				$companysByID[$c['id']]=$c;
			}
		}
		//
		if ($data){
			$i=0;
			foreach ($data as $d){
				$data[$i]['companyName']=$companysByID[$d['companyid']]['name'];
				$i++;
			}
		}
		$this->assign('staffs',$data);
		$this->display();	
	}
	public function staffSet(){
		$company_staff_db=M('Company_staff');
		if(IS_POST){
			if (!trim($_POST['name'])||!trim($_POST['username'])||!intval($_POST['companyid'])){
				$this->error('姓名、用户名和店铺都不能为空');
				exit();
			}
			$_POST['token']=$this->token;
			$_POST['time']=time();
			$_POST['password']=md5($_POST['password']);
			if (!isset($_GET['itemid'])){
				$company_staff_db->add($_POST);
			}else {
				if (!trim($_POST['password'])){
					unset($_POST['password']);
				}
				$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->save($_POST);
			}
			$this->success('操作成功',U('Member_card/staff',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else{
			$company_db=M('Company');
			$companys=$company_db->where(array('token'=>$this->token))->order('id ASC')->select();
			$this->assign('companys',$companys);
			if (isset($_GET['itemid'])){
				$thisItem=$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->find();
			}else {
				$thisItem['companyid']=0;
				//$thisNotice['endtime']=time()+10*24*3600;
			}
			$this->assign('item',$thisItem);
			$this->display('staffSet');
		}
	}
	public function ruleSet(){
		$card_rule_db=M('Member_card_rule');
		if(IS_POST){
			$_POST['token']=$this->token;//percent_rule fixed_rule
                        $_POST['fixed_rule'] = str_replace('，', ',', $_POST['fixed_rule']);
			if (intval($_POST['rule_id'])<=0){
				$card_rule_db->add($_POST);
			}else {
				$card_rule_db->where(array('id'=>intval($_POST['rule_id'])))->save($_POST);
			}
			$this->success('操作成功',U('Member_card/ruleSet',array('token'=>session('token'))));
		}else{
			$rule_data=$card_rule_db->where(array('token'=>$this->token))->order('id ASC')->find();
			$this->assign('item',$rule_data);
			$this->display('ruleSet');
		}
	}
	public function staffDelete(){
		$company_staff_db=M('Company_staff');
		$thisItem=$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->find();
		if ($thisItem['token']==$this->token){
		$data=$company_staff_db->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
		$this->success('操作成功',U('Member_card/staff',array('id'=>$this->thisCard['id'],'token'=>$this->token)));
		}
	}

	public function useRecords(){
		$types	= array('vip'=>4,'coupon'=>1,'integral'=>2);
		$type	= $_GET['type'];
		if (!$types[$type]){
			exit('no type');
		}
		switch ($type){
			case 'vip':
				$a='privilege';
				break;
			case 'integral':
				$a=$type;
				break;
			case 'coupon':
				$a=$type;
				break;
		}
		$this->assign('a',$a);
		$db			= M('Member_card_'.$type);
		$wheres 	= array('id'=>intval($_GET['itemid']));
		
		$thisItem	= $db->where($wheres)->find();	
		$this->assign('thisItem',$thisItem);
		$record_db 	= M('Member_card_use_record');
		$where		= array('itemid'=>$thisItem['id'],'cat'=>$types[$type]);
		$count      = $record_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list = $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$wecha_ids=array();
		$staffids=array();
		if ($list){
			foreach ($list as $l){
				if (!in_array($l['wecha_id'],$wecha_ids)){
					array_push($wecha_ids,$l['wecha_id']);
				}
				if (!in_array($l['staffid'],$staffids)){
					array_push($staffids,$l['staffid']);
				}
			}
			//
			$userinfo_where['wecha_id']=array('in',$wecha_ids);
			$users=M('Userinfo')->where($userinfo_where)->select();
			$usersArr=array();
			if ($users){
				foreach ($users as $u){
					$usersArr[$u['wecha_id']]=$u;
				}
			}
			$cards=M('Member_card_create')->where($userinfo_where)->select();
			$cardsArr=array();
			if ($cards){
				foreach ($cards as $u){
					$cardsArr[$u['wecha_id']]=$u;
				}
			}
			$staffWhere=array('in',$staffids);
			$staffs=M('Company_staff')->where($staffWhere)->select();
			$staffsArr=array();
			if ($staffs){
				foreach ($staffs as $s){
					$staffsArr[$s['id']]=$s;
				}
			}
		}
		//
		if ($list){
			$i=0;
			foreach ($list as $l){
				$list[$i]['userName']=$usersArr[$l['wecha_id']]['truename'];
				$list[$i]['userTel']=$usersArr[$l['wecha_id']]['tel'];
				$list[$i]['cardNum']=$cardsArr[$l['wecha_id']]['number'];
				$list[$i]['operName']=$staffsArr[$l['staffid']]['name'];
				$i++;
			}
		}
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('types',$types[$type]);
		$this->display();
	}

	public function useRecord_del(){
		$record_db=M('Member_card_use_record');
		$thisRecord=$record_db->where(array('id'=>intval($_GET['itemid'])))->find();

		if ($thisRecord['token']!=$this->token){
			exit('error');
		}

		if ($thisRecord['cat']){
			switch ($thisRecord['cat']){
				case 1:
					$type='vip';
					break;
				case 2:
					$type='integral';
					break;
				case 3:
					$type='coupon';
					break;
			}
			
			if ($type){
				$db=M('Member_card_'.$type);
				$thisItem=$db->where(array('id'=>$thisRecord['itemid']))->find();
			}
		}
		$record_db->where(array('id'=>intval($_GET['itemid'])))->delete();

		$userinfo_db=M('Userinfo');
    	$thisUser = $userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$thisRecord['wecha_id']))->find();
		$userArr=array();
		$userArr['total_score']=$thisUser['total_score']-$thisRecord['score'];
		$userArr['expensetotal']=$thisUser['expensetotal']-$thisRecord['expense'];
		$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$thisRecord['wecha_id']))->save($userArr);

		if ($thisRecord['itemid']){
			$useCount=$thisItem['usetime'];
			$useCount=intval($useCount)-$thisRecord['usecount'];
			$db->where(array('id'=>$thisRecord['itemid']))->save(array('usetime'=>$useCount));
		}
		$this->success('操作成功');
    }

	
	
	function _isUseRecordExist($cat,$itemid){
		$record_db=M('Member_card_use_record');
		$thisRecord=$record_db->where(array('itemid'=>intval($itemid),'cat'=>intval($cat)))->find();
		if ($thisRecord){
			$this->error('请先删除该信息下的所有使用记录，然后再删除本信息');
		}
	}
	public function members(){
		$card_create_db=M('Member_card_create');
		$where=array();
		$where['cardid']= intval($_GET['id']);
		$itemid 		= intval($_GET['itemid']);
		if($itemid){
			$where['id']	= $itemid;
		}		
		$where['token']	= $this->token;
		$where['wecha_id']=array('neq','');
		if (IS_POST){
			if (isset($_POST['searchkey'])&&trim($_POST['searchkey'])){
				$where['number']=array('like','%'.trim($_POST['searchkey']).'%');
			}
		}
		$count		= $card_create_db->where($where)->count();
		$Page       = new Page($count,35);
		$show       = $Page->show();
		$list 		= $card_create_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$members	= $list;
		
		$wecha_ids=array();
		if ($members){
			foreach ($members as $member){
				array_push($wecha_ids,$member['wecha_id']);
			}
			$userinfo_db	= M('Userinfo');
			$userinfo_where['wecha_id']	= array('in',$wecha_ids);
			$users=$userinfo_db->where($userinfo_where)->select();
			$usersArr=array();
			if ($users){
				foreach ($users as $u){
					$usersArr[$u['wecha_id']]=$u;
				}
			}
			//
			$i=0;
			foreach ($members as $member){
				$thisUser=$usersArr[$member['wecha_id']];
				$members[$i]['uid']=$thisUser['id'];
				$members[$i]['truename']=$thisUser['truename'];
				$members[$i]['wechaname']=$thisUser['wechaname'];
				$members[$i]['qq']=$thisUser['qq'];
				$members[$i]['sex']=$thisUser['sex'];
				$members[$i]['age']=$thisUser['age'];
				$members[$i]['bornyear']=$thisUser['bornyear'];
				$members[$i]['bornmonth']=$thisUser['bornmonth'];
				$members[$i]['bornday']=$thisUser['bornday'];
				$members[$i]['tel']=$thisUser['tel'];
				$members[$i]['getcardtime']=$thisUser['getcardtime'];
				$members[$i]['expensetotal']=$thisUser['expensetotal'];
				$members[$i]['total_score']=$thisUser['total_score'];
				$members[$i]['balance']=$thisUser['balance'];
				$i++;
			}
			
			$this->assign('members',$members);
			//
			$this->assign('page',$show);
		}
		$this->display();
	}
	public function member(){
		$card_create_db=M('Member_card_create');
		$where='cardid='.intval($_GET['id']).' AND token=\''.$this->token.'\' AND wecha_id!=\'\'';
		$thisMember=$card_create_db->where(array('id'=>intval($_GET['itemid'])))->find();
		//
		$thisUser=M('Userinfo')->where(array('token'=>$thisMember['token'],'wecha_id'=>$thisMember['wecha_id']))->find();
		$this->assign('thisUser',$thisUser);
		$members[0]=$thisMember;
		$members[0]['truename']=$thisUser['truename'];
		$members[0]['wechaname']=$thisUser['wechaname'];
		$members[0]['qq']=$thisUser['qq'];
		$members[0]['tel']=$thisUser['tel'];
		$members[0]['getcardtime']=$thisUser['getcardtime'];
		$members[0]['expensetotal']=$thisUser['expensetotal'];
		$members[0]['total_score']=$thisUser['total_score'];
		$members[0]['balance']=$thisUser['balance'];
		$members[0]['uid']=$thisUser['id'];
		$this->assign('members',$members);
		//
		$record_db=M('Member_card_use_record');
		$pay_record=M('Member_card_pay_record');
		$where=array('wecha_id'=>$thisUser['wecha_id'],'token'=>$this->token);
		$count      = $record_db->where($where)->count();
		$count2      = $pay_record->where($where)->count();
		$Page       = new Page($count,15);
		$Page2       = new Page($count2,15);
		
		$list = $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$rmb = $pay_record->where($where)->limit($Page2->firstRow.','.$Page2->listRows)->order('createtime DESC')->select();
		$show       = $Page->show();
		$show2       = $Page2->show();
		
		$this->assign('records',$list);
		$this->assign('rmb',$rmb);
		$this->assign('page',$show);
		$this->assign('page2',$show2);
		$this->display();
	}
	
	public function recharge(){
		$db = M('Userinfo');
		$uid = (int)$_GET['uid'];
		$uinfo = $db->where(array('token'=>$this->token,'id'=>$uid))->field('wechaname,wecha_id,truename,id')->find();
		if(IS_POST){
		
			if($db->create() === false) $this->error($db->getError());
			$id = (int)$_POST['uid'];
			if($db->where(array('token'=>$this->token,'id'=>$id))->setInc('balance',$_POST['price'])){
				$orderid = date('YmdHis',time()).mt_rand(1000,9999);
				M('Member_card_pay_record')->add(array('orderid' => $orderid , 'ordername' => '后台手动充值' , 'createtime' => time() , 'token' => $this->token , 'wecha_id' => $uinfo['wecha_id'] , 'price' => $_POST['price'] , 'type' => 1 , 'paid' => 1 , 'module' => 'Card' , 'paytime' => time() , 'paytype' => 'recharge'));
				$this->success('充值成功');
			}else{
				$this->error('充值失败，请稍后再试~');
			}

		}else{

			$this->assign('uinfo',$uinfo);
			
		}
		$this->display();
	}
	
	public function signrecords(){
		$card_create_db=M('Member_card_create');
		$where='cardid='.intval($_GET['id']).' AND token=\''.$this->token.'\' AND wecha_id!=\'\'';
		$thisMember=$card_create_db->where(array('id'=>intval($_GET['itemid'])))->find();
		//
		$thisUser=M('Userinfo')->where(array('token'=>$thisMember['token'],'wecha_id'=>$thisMember['wecha_id']))->find();
		$this->assign('thisUser',$thisUser);
		$members[0]=$thisMember;
		$members[0]['truename']=$thisUser['truename'];
		$members[0]['wechaname']=$thisUser['wechaname'];
		$members[0]['qq']=$thisUser['qq'];
		$members[0]['tel']=$thisUser['tel'];
		$members[0]['getcardtime']=$thisUser['getcardtime'];
		$members[0]['expensetotal']=$thisUser['expensetotal'];
		$members[0]['total_score']=$thisUser['total_score'];
		$this->assign('members',$members);
		//
		$record_db=M('Member_card_sign');
		$where=array('wecha_id'=>$thisUser['wecha_id'],'token'=>$this->token);
		$count      = $record_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list = $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('sign_time desc')->select();
		$this->assign('records',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function memberExpense(){
		//score
		if (IS_POST){
			$arr=array();
			$arr['itemid']=intval($this->_post('itemid'));
			$arr['wecha_id']=$this->_post('wecha_id');
			$arr['expense']=intval($this->_post('money'));
			$arr['time']=time();
			$arr['token']=$this->token;
			$arr['cat']=intval($this->_post('cat'));
			$arr['staffid']=intval($this->_post('staffid'));
			$arr['usecount']=0;
			//
			$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($this->thisCard['id'])))->find();
			$arr['score']=intval($set_exchange['reward'])*$arr['expense'];
			//
			M('Member_card_use_record')->add($arr);
			$userArr=array();
			$thisUser=M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$arr['wecha_id']))->find();
			$userArr['total_score']=$thisUser['total_score']+$arr['score'];
			$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
			M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$arr['wecha_id']))->save($userArr);
		}
		$this->success('操作成功');
	}
	public function member_del(){
		$card_create_db=M('Member_card_create');
		$thisMember=$card_create_db->where(array('id'=>intval($_GET['itemid'])))->find();
		//
		$thisUser=M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$thisMember['wecha_id']))->find();
		//
		$where=array('wecha_id'=>$thisUser['wecha_id'],'token'=>$this->token);
		M('Member_card_sign')->where($where)->delete();
		M('Member_card_use_record')->where($where)->delete();
		M('Userinfo')->where($where)->delete();
		$card_create_db->where(array('id'=>intval($_GET['itemid'])))->save(array('wecha_id'=>''));
		$this->success('操作成功');
	}
	public function delete(){
		$tokenWhere=array('token'=>$this->token,'cardid'=>$_GET['id']);
		if (M('Member_card_create')->where($tokenWhere)->find()){
			$this->error('请先删除创建的卡号');
		}
		M('Member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->delete();
		$this->success('操作成功');
	}
	

	
	public function getuserinfo(){
		$wecha_id = $this->_get("id");

		$uinfo = M('Userinfo')->where(array('wecha_id'=>$wecha_id ,'token'=>$_SESSION['token']))->order('id DESC')->find();
		$this->assign('list',$uinfo);
	
		$this->display();	
	}

	
	
	
	
	//会员详情
	public function info(){
		$data=M('Member_card_info')->where(array('token'=>$_SESSION['token']))->find();
		if(IS_POST){
			//dump($_POST);EXIT;
			$_POST['token']=$_SESSION['token'];			
			if($data==false){				
				$this->all_insert('Member_card_info','/info');
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_info','/info');
			}
		}else{
			$this->assign('info',$data);
			$contact=M('Member_card_contact')->where(array('token'=>$_SESSION['token']))->order('sort desc')->select();
			$this->assign('contact',$contact);
			$this->display();
		}	
	}
	public function contact(){
		if(IS_POST){			
			$this->all_insert('Member_card_contact','/info');
		}else{
			$this->error('非法操作');	
		}
	}
	public function contact_edit(){
		if(IS_POST){			
			$this->all_save('Member_card_contact','/info');
		}else{
			$this->error('非法操作');			
		}		
	}
	
//导出积分，线下消费记录
	public function exportCardUseRecord(){
		$wecha_id = $this->_get('wecha_id');
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=cardUseRecord.xls");
		
		$arr = array(
			array('en'=>'itemid','cn'=>'消费产品ID'),
			array('en'=>'number','cn'=>'会员卡号'),
			array('en'=>'staffid','cn'=>'店员ID'),
			array('en'=>'cat','cn'=>'类型'),
			array('en'=>'expense','cn'=>'消费金额'),
			array('en'=>'score','cn'=>'积分数'),
			array('en'=>'usecount','cn'=>'使用次数'),
			array('en'=>'time','cn'=>'时间'),
			array('en'=>'notes','cn'=>'备注')
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		//data
		$db = M('Member_card_use_record');
		if($this->_get('type') != 'all'){
			$where = array('token'=>$this->token,'wecha_id'=>$wecha_id);
		}else{
			$where = array('token'=>$this->token);
		}
		
		$sns = $db->where($where)->order('time DESC')->select();

		if($sns){
			foreach ($sns as $sn){
				$number 	= M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('number');
				$sn['number']	= $number;				
				$j = 0;
				foreach ($arr as $field){			
					$fieldValue = $sn[$field['en']];
					switch($field['en']){		
						case 'time':
							if ($fieldValue){
								$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
								
							}else {
								$fieldValue='';
							}
							break;
						case 'cat':
							switch($fieldValue){
								case 2:
									$fieldValue = iconv('utf-8','gbk','兑换');
									break;
								case 3:
									$fieldValue = iconv('utf-8','gbk','赠送');
									break;
								case 98:
									$fieldValue = iconv('utf-8','gbk','分享');
									break;
								default:
									$fieldValue = iconv('utf-8','gbk','消费');
									
							}
						default:
							break;
					}
					
					if ($j<$fieldCount-1){
						echo $fieldValue."\t";
					}else {
						echo $fieldValue."\n";
					}
					$j++;
				}
				$i++;
			}
		
		}
		exit();
	
	}
	
//导出人民币在线消费记录

	public function exportrmb(){
		$wecha_id = $this->_get('wecha_id');
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=cardPayRecord.xls");
		
		$arr = array(
			array('en'=>'orderid','cn'=>'订单号'),
			array('en'=>'ordername','cn'=>'订单名称'),
			array('en'=>'transactionid','cn'=>'第三方订单号'),
			array('en'=>'paytype','cn'=>'支付类型'),
			array('en'=>'createtime','cn'=>'订单创建时间'),
			array('en'=>'price','cn'=>'金额'),
			array('en'=>'paytime','cn'=>'支付时间'),
			array('en'=>'paid','cn'=>'支付状态'),
			array('en'=>'number','cn'=>'会员卡号'),
			array('en'=>'module','cn'=>'来源模块'),
			array('en'=>'type','cn'=>'类型')
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		//data
		$db = M('Member_card_pay_record');
		if($this->_get('type') != 'all'){
			$where = array('token'=>$this->token,'wecha_id'=>$wecha_id);
		}else{
			$where = array('token'=>$this->token);
		}
		
		$sns = $db->where($where)->order('id DESC')->select();
				
		if($sns){
			foreach ($sns as $sn){
				$number 	= M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('number');
				$sn['number']	= $number;
				$j = 0;
				foreach ($arr as $field){
					$fieldValue = $sn[$field['en']];
					switch($field['en']){
						case 'orderid':
							$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
							break;
							
						case 'transactionid':
							if($fieldValue != ''){
								$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
							}
							
							break;
							
						case 'createtime':
							if ($fieldValue){
								$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
								
							}else {
								$fieldValue='';
							}
							break;
						case 'paytime':
							if ($fieldValue){
								$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
								
							}else {
								$fieldValue='';
							}
							break;
							
						case 'type':
							switch($fieldValue){
								case 1:
									$fieldValue = iconv('utf-8','gbk','充值');
									break;
								case 0:
									$fieldValue = iconv('utf-8','gbk','消费');
									break;	
							}
							break;
							
						case 'paid':
							if($fieldValue == 1){
								$fieldValue = iconv('utf-8','gbk','交易成功');
							}else{
								$fieldValue = iconv('utf-8','gbk','未付款');
							}
							break;
						case 'ordername':
							$fieldValue = iconv('utf-8','gbk',$fieldValue);
							break;
			
						default:
							break;
					}
					
					if ($j<$fieldCount-1){
						echo $fieldValue."\t";
					}else {
						echo $fieldValue."\n";
					}
					$j++;
				}
				$i++;
			}
		
		}
		exit();
		
		
		
	}
	
	
//导出会员卡
	
	public function exportCard(){
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=card.xls");
	
		$id = $this->_get('id','intval');
		$token = $this->token;

		$arr = array(
			array('en'=>'number','cn'=>'卡号'),
			array('en'=>'truename','cn'=>'姓名'),
			array('en'=>'tel','cn'=>'手机号'),
			array('en'=>'total_score','cn'=>'积分'),
			array('en'=>'sex','cn'=>'性别 ( 男； 女； 其他）'),
			array('en'=>'bornyear','cn'=>'出生年'),
			array('en'=>'bornmonth','cn'=>'出生月'),
			array('en'=>'bornday','cn'=>'出生日'),
			array('en'=>'portrait','cn'=>'头像地址'),
			array('en'=>'qq','cn'=>'QQ号'),
			array('en'=>'getcardtime','cn'=>'领卡时间'),
			array('en'=>'expensetotal','cn'=>'消费总额'),
			array('en'=>'balance','cn'=>'余额 单位：元'),
			array('en'=>'wecha_id','cn'=>'微信OpenID')
		);

		//$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
				
			}
			$s++;
		}
		$create = M('Member_card_create');
		$userinfo = M('Userinfo');
		
		$createInfo = $create->field('number,token,wecha_id')->where("token = '$token' AND wecha_id != '' AND cardid = $id")->select();
		
		if($createInfo){
			foreach($createInfo as $k=>$v){
				
				$where['token'] = $v['token'];
				$where['wecha_id'] = $v['wecha_id'];
				
				$info = $userinfo->where($where)->field('truename,wechaname,tel,total_score,sex,bornyear,bornmonth,bornday,portrait,qq,getcardtime,expensetotal,balance')->select();
				$i=0;
				foreach($info as $key=>$val){
					$val = array_merge($val,$v);
					$j=0;


					foreach ($arr as $field){
						$fieldValue = $val[$field['en']];
						
						switch($field['en']){
						
							case 'truename':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;
							case 'wechaname':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;
							case 'sex':
								if($fieldValue == 1){
									$fieldValue = iconv('utf-8','gbk','男');
								}elseif($fieldValue == 2){
									$fieldValue = iconv('utf-8','gbk','女');
								}else{
									$fieldValue = iconv('utf-8','gbk','其他');
								}
								break;
							case 'getcardtime':
								$fieldValue = date('Y-m-d',$fieldValue);
								break;

						}
						
						
						if ($j<$fieldCount-1){
							echo $fieldValue."\t";
						}else {
							echo $fieldValue."\n";
						}
						$j++;
						
					}
					//$i++;
				}
			
			}
		
		}
	
	}
	
//删除记录
	public function payRecord_del(){
		$id = $this->_get('pid');
		$token = $this->token;
		$pay_record = M('Member_card_pay_record');
		if($pay_record->where(array('token'=>$token,'id'=>(int)$id))->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}

	}
	
	public function focus(){
		$focusDb = M('Member_card_focus');
		$list = $focusDb->where(array('token'=>$this->token))->select();
		
		$this->assign('info',$list);
		$this->display();
	}
	
	public function focusEdit(){
	
		$where['id']=$this->_get('fid','intval');
		$where['token']=$this->token;
		
		$data = M('Member_card_focus')->where($where)->find();
		
		$this->assign('info',$data);
		$this->display();
	}
	
	public function upsave(){
		$where['id'] = (int)$_POST['fid'];
		$where['token'] = $this->token;
		if(M('Member_card_focus')->where($where)->save($_POST)){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	public function focusDel(){
		$where['token'] = $this->token;
		$where['id'] = (int)$_GET['fid'];
		if(M('Member_card_focus')->where($where)->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
		
	}
	
	public function focusAdd(){
		if(IS_POST){
			$_POST['token']=$this->token;						
			$this->all_insert('Member_card_focus','/focus');
			}else{
				$this->display();
		}
	}
	
	public function custom(){
		$conf = M('Member_card_custom')->where(array('token'=>$this->token))->find();
		$conf = array_map('intval',$conf);
		
		$this->assign('conf',$conf);
		$this->display();
	}
	
	public function customSave(){


		$db = M('Member_card_custom');
		if($db->create() === false){
			$this->error('请稍后再试~');
		}else{
			unset($_POST['__hash__']);
			unset($_POST['token']);
			$_POST = array_map('intval',$_POST);
			$_POST['token'] = $this->token;
			if($db->where(array('token'=>$this->token))->find() == NULL){
				if($db->add($_POST)){
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}
			}else{
				if($db->where(array('token'=>$this->token))->save($_POST)){
					$this->success('保存成功');
				}else{
					
					$this->error('保存失败');
				}
			}
		
		}
	}
	

	public function center(){
		$where 		= array('token'=>$this->token,'wecha_id'=>array('neq',''));
		$cardid 	= $this->_post('cardid','intval');
		$number 	= $this->_post('number','trim');
		if(!empty($cardid)){
			$where['cardid']	= $cardid;
		}
		if(!empty($number)){
			$where['number']	= $number;			
		}
		$count		= M('Member_card_create')->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$allcard 	= M('Member_card_create')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach($allcard as $key=>$value){
			$user_info 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$value['wecha_id']))->find();
			$allcard[$key]['score'] 	= $user_info['total_score'];
			$allcard[$key]['expense'] 	= $user_info['expensetotal'];
			$allcard[$key]['balance'] 	= $user_info['balance'];
			$allcard[$key]['createtime']= $user_info['getcardtime'];
			$allcard[$key]['username']	= $user_info['wechaname'];
			$allcard[$key]['tel']		= $user_info['tel'];
			$allcard[$key]['card_name'] = M('Member_card_set')->where(array('token'=>$this->token,'id'=>$value['cardid']))->getField('cardname');		
		}

		$cards 	= M('Member_card_set')->where(array('token'=>$this->token))->select();
		$this->assign('cards',$cards);
		$this->assign('allCard',$allcard);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
}
?>