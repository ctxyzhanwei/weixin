<?php
class Custom_formAction extends UserAction{
	public $token;
	public $set_id;
	public $field_db;

	public function _initialize(){
		parent::_initialize();
		$this->token 	= session('token');
		$this->field_db	= D('Custom_field');
		$this->set_id 	= $this->_get('set_id','intval');
		$this->assign('set_id',$this->set_id);
		
	}


	public function index(){
		$list 	= $this->field_db->where(array('set_id'=>$this->set_id))->order('sort desc')->select();
		$list	= $this->_createInput($list);


		$this->assign('list',$list);
		$this->display();
	}

	/*生成预览表单*/
	public function _createInput($list){

		foreach($list as $key=>$value){
			$list[$key]['input']	= $this->_getInput($value['field_type']);
		}
		return $list;
	}

	/*设置表单*/
	public function set(){
		$field_id	= $this->_get('field_id','intval');
		$field_info	= $this->field_db->where(array('field_id'=>$field_id))->find();

		/*POST提交*/
		if(IS_POST){
			if($field_info){//修改
				$where	= array('token'=>$this->token,'field_id'=>$field_id);	
				$this->field_db->where($where)->save($_POST);
				$this->success('修改成功',U('Custom_form/index',array('token'=>$this->token,'set_id'=>$this->set_id)));
			}else{//添加
				$_POST['item_name']	= $this->_getItemName($this->set_id);
				$_POST['token']		= $this->token;
				
				if($this->field_db->create($_POST)){
					$id 	= $this->field_db->add($_POST);
					$this->success('添加成功',U('Custom_form/index',array('token'=>$this->token,'set_id'=>$this->set_id)));
				}else{
					echo $this->error($this->field_db->getError());
				}
			}
			
			

		}else{//设置页

			$this->assign('set',$field_info);
			$this->assign('field_type',$this->_formConf('field_type',$field_info['field_type']));
			$this->assign('field_match',$this->_formConf('field_match',$field_info['field_match']));
			$this->display();
		}


	}

	/*删除字段项*/

	public function del(){
		$where = array('token'=>$this->token,'field_id'=>$this->_get('field_id','intval'));

		if($this->field_db->where($where)->delete()){
			$this->success('删除成功');
		}
	}
	/*获取表单name唯一值*/
	public function _getItemName($set_id,$length=5){

		$str 		= 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str_length = strlen($str);
		$item 		= '';
		for($i=0;$i<=$length;$i++){
			$rand  	= mt_rand(0,$str_length);

			$item 	.= $str[$rand];
		}

		$item 		= $item.'_'.$set_id;
		//如果字段名称重复 重新获取
		if($this->field_db->where(array('set_id'=>$set_id,'item_name'=>$tiem))->find()){
			return $this->_getItemName($set_id);
		}else{
			return $item;
		}
	}
	/*字段预览表单*/
	public function _getInput($type){
		$str 	= '';
		switch ($type) {
			case 'text':
				$str = '<input type="text" class="px">';
				break;
			case 'textarea':
				$str = '<textarea rows="2" cols="20" style="height:35px;border:1px solid #cccccc;"></textarea>';
				break;	
			case 'select':
				$str = '<select><option value="">请选择</select>';
				break;
			case 'checkbox':
				$str = '<input type="checkbox">';
				break;	
			case 'radio':
				$str = '<input type="radio">';
				break;
			case 'date':
				$str = '<input type="text" class="px" value="2014-01-01">';
				break;				
		}
		return $str;
	}
	/*字段类型和匹配项*/
	public function _formConf($type='',$select=''){
		$conf 		= array(
			'field_type'	=> array(
				array('value'=>'','text'=>'请选择类型'),
				array('value'=>'text','text'=>'单行文本框'),
				array('value'=>'textarea','text'=>'多行文本框'),
				array('value'=>'checkbox','text'=>'多选选框'),
				array('value'=>'radio','text'=>'单选按钮'),
				array('value'=>'select','text'=>'下拉框'),
				array('value'=>'date','text'=>'日期选择'),
			),
			'field_match'	=> array(
				array('value'=>'','text'=>'常用输入验证'),
				array('value'=>'^[\u4e00-\u9fa5\a-zA-Z0-9]+$','text'=>'英文数字汉字'),
				array('value'=>'^[A-Za-z]+$','text'=>'英文大小写字符'),			
				array('value'=>'^[1-9]\d*|0$','text'=>'0或正整数'),
				array('value'=>'^[0-9]{1,30}$','text'=>'正整数'),
				array('value'=>'^[-\+]?\d+(\.\d+)?$','text'=>'小数'),
				array('value'=>'\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*','text'=>'邮箱'),
				array('value'=>'^13[0-9]{9}$|^15[0-9]{9}$|^18[0-9]{9}$','text'=>'手机'),
			)

		);

		$str  		= '';
		foreach($conf[$type] as $key=>$value){
			if($select == $value['value']){
				$selected	= 'selected';
			}else{
				$selected	= '';
			}

			$str 	.='<option value="'.$value['value'].'" '.$selected.'>'.$value['text'].'</option>';
		}

		return $str;
	}

}


?>