<?php
class CustomAction extends WapAction{
	public $token;
	public $wecha_id;
	public $thisForm;
	private $field_db;
	private $info_db;
	private $limit_db;
	public  $isamap;
	public  $amap;

	public function __construct(){
		parent::_initialize();
		$this->field_db		= M('custom_field');
		$this->info_db		= M('custom_info');
		$this->limit_db		= M('custom_limit');
		$this->token		= $this->_get('token');
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		//$this->wecha_id		= $this->_get('wecha_id');
		if (!$this->wecha_id){
			$this->wecha_id	= 'null';
		}

		$this->thisForm 	= M('custom_set')->where(array('token'=>$this->token,'set_id'=>$this->_get('id','intval')))->find();

		$this->assign('token',$this->token);
		$this->assign('thisForm',$this->thisForm );
		$this->assign('wecha_id',$this->wecha_id);

		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}

	public function index(){
		$set_id 	= $this->_get('id','intval');
		$formData 	= $this->_createForms($this->token,$set_id);

		if(IS_POST){
			$limit_info = $this->limit_db->where(array('limit_id'=>$this->thisForm['limit_id']))->find();

			if($limit_info['enddate']){
				if($limit_info['enddate']<time()){
					$this->error('抱歉，时间已过期，无法提交');
				}
			}

			if($limit_info['today_total'] >0){
				$time 		= strtotime(date('Y-m-d')); //凌晨时间
				$total 		= $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'add_time'=>array('gt',$time)))->count();
				if($total >= $limit_info['today_total']){
					$this->error('抱歉，今日只能提交'.$limit_info['today_total'].'次');
				}
			}		

			if($limit_info['sub_total'] >0){
				$total 		= $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->count();
				if($total >= $limit_info['sub_total']){
					$this->error('抱歉，提交总数已经超过'.$limit_info['sub_total'].'次');
				}
			}
	
			$data['token']		= $this->token;
			$data['wecha_id']	= $this->wecha_id;
			$data['set_id']		= $set_id;
			$data['add_time']	= time();		
			$data['user_name']	= empty($this->fans['wechaname'])?'匿名':$this->fans['wechaname'];
			$data['phone']		= empty($this->fans['tel'])?'匿名':$this->fans['tel'];
			$data['sub_info']	= $this->_serializeSubInfo($this->_request(),$set_id);
			if($this->info_db->add($data)){
				Sms::sendSms($this->token, '你的表单“'.$this->thisForm['title'].'”中有新的信息'); //发送商家短信
				$this->success($this->thisForm['succ_info']);
			}else{
				$this->error($this->thisForm['err_info']);
			}
		}else{
			//提交记录
			$spoor = $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id))->count();
			$this->assign('spoor',$spoor);

			$this->assign('verify',$formData['verify']);
			$this->assign('formData',$formData['string']);
			$this->display();
		}
	}


	/*表单详情简介*/
	public function detail(){
		$this->display();
	}
	/*表单提交记录*/
	public function spoor(){
		$set_id = $this->_get('id','intval');
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id);
		$list	= $this->info_db->where($where)->order('add_time desc')->select();
	
		foreach($list as $key=>$value){
			$list[$key]['sub_info']	= unserialize($value['sub_info']);
		}
		$this->assign('set_id',$set_id);
		$this->assign('list',$list);
		$this->display();
	}
	/*地图位置*/
	public function maps(){	
		$this->apikey	= C('baidu_map_api');
		$this->assign('apikey',$this->apikey);

		if (!$this->isamap){
			$this->display();
		}else {			
			$link=$this->amap->getPointMapLink($this->thisForm['longitude'],$this->thisForm['latitude'],$this->thisForm['title']);
			header('Location:'.$link);
		}
	}
	/*创建序列化提交信息*/
	private function _serializeSubInfo($post,$set_id){
		$field_info = $this->field_db->where(array('token'=>$this->token,'set_id'=>$set_id))->field('field_name,item_name,field_type')->order('sort desc')->select();
		$info 		= array();
		foreach($field_info as $key=>$value){
			$info[$key]['name'] 	= $value['field_name'];
			if($value['field_type'] == 'checkbox'){
				$info[$key]['value']	= implode(',', $post[$value['item_name']]);
			}else{
				$info[$key]['value']	= $post[$value['item_name']];
			}
		}
		return serialize($info);
	}

	/*获取自定义表单字段信息*/
	private function _createForms($token,$set_id){

		$where	= array('token'=>$token,'set_id'=>$set_id,'is_show'=>'1');
		$forms 	= $this->field_db->where($where)->order('sort desc')->select();

		$str	= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">';
		$arr 	= array();
		foreach($forms as $key=>$value){
			$str	.= '<tr><th>';
			$str	.= $value['field_name'];
			$str 	.= '</th><td>';
			$str	.= $this->_getInput($value);
			$str	.= '</td></tr>';

			$arr[] 	 = array('id'=>$value['item_name'],'name'=>$value['field_name'],'type'=>$value['field_type'],'match'=>$value['field_match'],'is_empty'=>$value['is_empty'],'err_info'=>$value['err_info']);  //js验证信息
		}
		$str	.= '</table>';
		return array('string'=>$str,'verify'=>$arr);
	}

	/*获取自定义表单*/
	private function _getInput($value){
		$input 	= '';
		switch($value['field_type']){
			case 'text':
				$input 	.= '<input type="text" class="px" id="'.$value['item_name'].'" name="'.$value['item_name'].'" value="">';
				break;
			case 'textarea':
				$input 	.= '<textarea name="'.$value['item_name'].'" id="'.$value['item_name'].'" rows="4" cols="25"  ></textarea>';
				break;
			case 'checkbox':
				$option = explode('|', $value['filed_option']);
				for($i=0;$i<count($option);$i++){
					$input 	.= '<input type="checkbox" name="'.$value['item_name'].'[]" id="'.$value['item_name'].'" value="'.$option[$i].'"  />'.$option[$i];
				}
				break;
			case 'radio':
				$option = explode('|', $value['filed_option']);
				for($i=0;$i<count($option);$i++){
					$checked = $i==0?'checked=true':'';
					$input 	.= '<input type="radio" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.$option[$i].'" '.$checked.' />'.$option[$i];
				}
				break;
			case 'select':
				$input 	.= '<select name="'.$value['item_name'].'" id="'.$value['item_name'].'"><option value="">请选择..</option>';
				$op_arr	= explode('|',$value['filed_option']);
				$num	= count($op_arr);
				if($num > 0){
					for($i=0;$i<$num;$i++){
						$input 	.= '<option value="'.$op_arr[$i].'">'.$op_arr[$i].'</option>';
					}
				}
				$input  .='</select>';
				break;
			case 'date':
				$input 	.= '<input type="text" class="px" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.date('Y-m-d',time()).'" onClick="WdatePicker()"/>';
		}

		return $input;
	}

	function generateQRfromGoogle($chl,$widhtHeight ='150',$EC_level='L',$margin='0'){
		$chl = urlencode($chl);
    	$src='http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl;
    return $src;
	}

}
?>