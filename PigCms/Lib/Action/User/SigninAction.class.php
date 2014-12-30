<?php
class  SigninAction extends UserAction {

	public $open_sign	= 1; //是否开启签到
	public $df_integral = 5; //初始赠送积分
	public $sign_conf;
	public $sign_db;

	/*初始化*/
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Signin');
		$this->sign_conf = M('sign_conf');
		$this->sign_db   = M('sign_in');

	}

	/*签到列表*/
	public function index(){
		if($this->open_sign == 0){
			//未开启活动提示
		}

        $set_id 	= M('sign_set')->where(array('token'=>session('token')))->getField('id');

       $where 		= array('token'=>$this->token);
        //$where 		= array();
        $user_name	= $this->_post('user_name','htmlspecialchars,trim');
       	$sort		= $this->_post('sort','trim');
        $startdate	= strtotime($this->_post('startdate','trim'));
        $enddate	= strtotime($this->_post('enddate','trim'));

		if($startdate && $enddate){
			$where['time'] = array(array('gt',$startdate),array('lt',$enddate),'and');
		}

		if($user_name){
			$where['user_name'] = array('like','%'.$user_name.'%');
		}

		if(empty($sort)){
			$order 	= 'time desc';
		}else{
			$order 	= 'time '.$sort;
		}

        $count      = $this->sign_db->where($where)->count();
        $Page       = new Page($count,12);
        $list 		= $this->sign_db->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('search',array('startdate'=>$startdate,'enddate'=>$enddate,'sort'=>$sort));
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->assign('listinfo',1);
		$this->display();
	}

	/*签到奖励*/
	public function integral_conf(){

		$where = array('token'=>$this->token);
		$list = $this->sign_conf->where($where)->order(array('use'=>'desc','conf_id'=>'desc'))->select();
		$this->assign('list',$list);
		$this->assign('listinfo',2);
		$this->display();

	}

	/*设置连续签到奖励奖励*/

	public function add_integral(){

		$set = $this->sign_conf->where(array('token'=>$this->token,'conf_id'=>$this->_get('id','intval')))->find();

		if(IS_POST){
			//if($this->sign_conf->create() === false){

			//	$this->error($this->sign_conf->getError());
			//}else{
			$data = array();
			$data['integral'] 	= $this->_post('integral','intval');
			$data['stair']	 	= $this->_post('stair','intval');
			$data['use'] 		= $this->_post('use');
			$data['token'] 		= $this->token;

			if($data['integral']==0 || $data['stair']==0 ){
				$this->error('签到奖励和签到次数必须为大于0的整数');
				exit();
			}
			if($set){
				$this->sign_conf->where(array('token'=>$this->token,'conf_id'=>$this->_post('conf_id','intval')))->save($data);

				$this->success('修改成功',U("Signin/integral_conf",array('token'=>session('token'))));
			}else{
				$this->sign_conf->add($data);
				$this->success('设置成功',U("Signin/integral_conf",array('token'=>session('token'))));

			}
			//}
		}

		$this->assign('set',$set);
		$this->display();
		
	}


	/*删除签到奖励*/

	public function del_integral(){
		$conf_id 	= filter_var($this->_get('id'),FILTER_VALIDATE_INT);
		$where 		= array('conf_id'=>$conf_id,'token'=>session('token'));

		$del = $this->sign_conf->where($where)->delete();
		if($del){
			$this->success('操作成功',U("Signin/sign_conf",array('token'=>session('token'))));
		}else{
			$this->error('操作失败');
		}
	}



	/*签到配置*/

	public function set(){

		$set_db		= M('sign_set'); //签到设置
		$keyword_db	= M('keyword'); //关键词
		$where 	= array('token'=>$this->token);
		$set_info	= $set_db->where($where)->find();

		if(IS_POST){

			$data 				= array();
			$data['keywords'] 	= $this->_post('keywords','trim');
			$data['title'] 		= $this->_post('title','trim');
			$data['content'] 	= $this->_post('content','htmlspecialchars');
			$data['reply_img'] 	= $this->_post('reply_img','trim');
			$data['top_pic'] 	= $this->_post('top_pic','trim');
			$data['token'] 		= $this->token;



			if($set_info){
				
					$set_db->where($where)->save($data);
					
					$keyword['pid']		= $this->_post('id','intval');
                    $keyword['module']	= 'Sign';
                    $keyword['token']	= $this->token;
                    $keyword['keyword']	= $data['keywords'];
                    $keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('id','intval')))->save($keyword);
					
				$this->success('修改成功');

			}else{

				$id = $set_db->add($data);
				$keyword['pid']		= $id;
                $keyword['module']	= 'Sign';
                $keyword['token']	= $this->token;
                $keyword['keyword']	= $data['keywords'];
                $keyword_db->add($keyword);
				
				$this->success('设置成功');		
			}
		}else{
			if (!$set_info){
				$set_info['top_pic']=C('site_url').'/tpl/static/sign/top.jpg';
				$set_info['reply_img']=C('site_url').'/tpl/static/sign/r.jpg';
			}
			$this->assign('set',$set_info);
			$this->assign('listinfo',3);
			$this->display();
		}
	}
}

?>