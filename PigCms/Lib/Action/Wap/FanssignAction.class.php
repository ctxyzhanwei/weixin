<?php
class FanssignAction extends WapAction{
	public $wecha_id; //粉丝识别码
	public $token;	  //用户身份证
	public $fansInfo; //粉丝信息
	public $thisUser; //用户信息
	public $sign_db;  //签到表
	public $df_integral =5;//默认签到积分
	
	public function _initialize() {
		parent::_initialize();
	}

	public function __construct(){
		parent::_initialize();
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}

		$this->wecha_id	= $this->_get('wecha_id');
		$this->token 	= $this->_get('token');
		$this->fansInfo = $this->fans;
		$this->thisUser = M('Userinfo')->where(array('token'=>$this->_get('token'),'wecha_id'=>$this->wecha_id))->find();
		$this->sign_db 	= M('sign_in');

		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
	}

	/*签到首页*/
	public function index(){
		if ($this->wecha_id&&!$this->fans){
			$this->error('请先完善个人资料再签到',U('Userinfo/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'redirect'=>MODULE_NAME.'/index|id:'.intval($id))));
		}

		/*粉丝个人信息完整验证
		*/

		$where 		= array('token'=>$this->token,'wecha_id'=>$this->wecha_id);  //本地测试暂时关闭识别码
		$count		= $this->sign_db->where($where)->sum('integral');  //总积分
		$sign_num   = $this->sign_db->where($where)->order('time desc')->getField('continue'); //连续签到次数
		$set_id 	= $this->_get('id','intval');
		$month 		= $this->_get('month','intval');
		//查询指定月份签到记录
		if(empty($month)){
			$month 	= date('m');
		}
		//echo strtotime('-1 day');
		$month_time = $this->_mFristAndLast($month); //指定月份起始结束时间戳
		$where['time']	= array(array('gt',$month_time['firstday']),array('lt',$month_time['lastday']),'AND');
		$list 	 	= $this->sign_db->where($where)->order('time desc')->limit(6)->select();

		$this->top_pic = M('sign_set')->where(array('token'=>$this->token,'id'=>$set_id))->getField('top_pic');

		//连续签到赠送积分 连续签到积分=默认积分+连续签到奖励积分 未开
		$integral 	= $this->df_integral + $this->_reward($sign_num,0);

		$this->assign('empty','<tr><td colspan="2">您本月还没有签到</td></tr>');
		$this->assign('set_id',$set_id);
		if ($this->top_pic){
			$this->assign('sign_pic',$this->top_pic);
		}else {
			$this->assign('sign_pic','/tpl/static/sign/top.jpg');
		}
		
		//检查今天是否签到
		$this->assign('tody_sign',$this->_todySign());
		$this->assign('integral',$integral);
		$this->assign('count',$count);
		$this->assign('sign_num',$sign_num);
		$this->assign('list',$list);
		$this->display();
    }

    /*签到*/
    public function addSign(){

    	if($this->_todySign()){
    		echo'{"success":1,"msg":"您今天已经签到了"}';
    		exit();	
    	}

    	$sign_num  = $this->sign_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->order('time desc')->getField('continue');
    
    	//连续签到奖励积分
    	$data	 			= array();
    	$data['token'] 		= $this->token;
    	$data['wecha_id']	= $this->wecha_id;
    	$data['user_name']	= $this->fans['wechaname']?$this->fans['wechaname']:'';
    	$data['integral']	= $this->df_integral + $this->_reward($sign_num);
    	$data['time']		= time();
    	$data['continue']	= $this->_continue($sign_num);
    	$data['phone']		= $this->fans['tel']?$this->fans['tel']:'';

    
    	if($this->sign_db->add($data)){
    		/*用户积分表跟新新
    		*/
    		echo'{"success":1,"msg":"恭喜您签到成功"}';
    	}else{

    		echo'{"success":1,"msg":"暂时无法签到"}';
    	}
    }

    /*验证当天是否签到*/
    public function _todySign(){
    	$is_sign 	= 0;
    	$time 		= strtotime(date('Y-m-d')); //凌晨时间
    	$last_time 	= $this->sign_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->order('time desc')->getField('time');

    	//签到时间大于今天凌晨的时间则今天已经签到
    	if($time<$last_time){
    		$is_sign = 1;
    	}
    	return $is_sign;
    }

    /*连续签到次数对应的奖励*/
    public function _reward($sign_num,$is_open){
		if($is_open){
			$sign_conf 		= M('sign_conf');
			$integral 		= '';
			$integral  = $sign_conf->where(array('stair'=>array('elt',$sign_num),'use'=>1))->getField('integral');

			if(empty($integral)){
				return 0;
			}else{
				return $integral;
			}
		}else{
			return 0;
		}
    } 

    /*连续签到次数*/
    public function _continue($sign_num){
    	//昨天时间戳
    	$startYesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    	$endYesterday	= mktime(0,0,0,date('m'),date('d'),date('Y'))-1;

    	$where['token']		= $this->token;
    	$where['wecha_id']	= $this->wecha_id;
    	$where['time']		= array(array('gt',$startYesterday),array('lt',$endYesterday),'AND');
    	$time 	= $this->sign_db->where($where)->getField('time');

    	if($time){
    		return $sign_num+1;
    	}else{
    		return 0;
    	}
    }
    
    /*获取指定月份起始结束时间戳*/
    function _mFristAndLast($m = "" ,$y = "") {
		if ($y == "")
			$y = date ( "Y" );
		if ($m == "")
			$m = date ( "m" );
		$m = sprintf ( "%02d", intval ( $m ) );
		$y = str_pad ( intval ( $y ), 4, "0", STR_PAD_RIGHT );
		$m > 12 || $m < 1 ? $m = 1 : $m = $m;
		$firstday = strtotime ( $y . $m . "01000000" );
		$firstdaystr = date ( "Y-m-01", $firstday );
		$lastday = strtotime ( date ( 'Y-m-d 23:59:59', strtotime ( "$firstdaystr +1 month -1 day" ) ) );
		return array ("firstday" => $firstday, "lastday" => $lastday );
	}

}
?>