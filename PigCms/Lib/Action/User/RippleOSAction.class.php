<?php

class RippleOSAction extends UserAction{
	const AUTHCAT_NODE_API = 'https://api.authcat.org';
	const AUTHCAT_CREATE_NODE = '/node_api/create_node';
	const AUTHCAT_UPDATE_NODE = '/node_api/update_node';
	const AUTHCAT_RETRIEVE_NODE = '/node_api/retrieve_node';
	const AUTHCAT_DELETE_NODE = '/node_api/delete_node';
	const DB_ERROR = '操作数据库失败';
	const WX_NAME_ERROR = '微信公众号不存在';
	//配置
	public function set(){
		if(IS_POST)
		{
			$node=D('Rippleos_node')->where(array('token'=>session('token')))->find();
			if($node==false)
			{
				$ret = $this->rptk_create($_POST);
			}
			else
			{
				$ret = $this->rptk_edit($_POST);	
			}
			if (is_string($ret))
			{
				//echo $ret;
				$this->error($ret, U('RippleOS/set'));
			}
			else
			{
				//echo "修改成功";
				$this->success('修改成功', U('RippleOS/set'));
			}
		}
		else
		{
			// display
			$node_info = array();
			if (!$this->rptk_display($node_info))
			{
				return;
			}
			$this->assign('node_info', $node_info);
			$this->display();
		}
	}

	public function delete()
	{
		$token = session('token');
		$node=D('Rippleos_node')->where(array('token'=>$token))->find();
		if (is_array($node))
		{
			$this->rptk_delete_node($node['node']);
			M('Keyword')->where(array('module'=>'RippleOS_url', 'token'=>$token))->delete();
			M('Keyword')->where(array('module'=>'RippleOS_code', 'token'=>$token))->delete();
			D('Rippleos_node')->where(array('token'=>$token))->delete();
		}
		$this->success('清除成功', U('RippleOS/set'));
	}

	private function rptk_create($input)
	{
		$wx=D('Wxuser')->where(array('token'=>session('token')))->find();
		if ($wx == false)
		{
			return self::WX_NAME_ERROR;
		}
		$node_info = array();
		$node_info = $input;
		$node_info['wx_id'] = $wx['weixin'];
		$node_info['wx_name'] = $wx['wxname'];
		$node_info['name'] = $wx['weixin'];
		$authcat_id = $this->rptk_update_node(null, $node_info);
		if ($authcat_id < 0)
		{
			return $this->err_msg[abs($authcat_id)];
		}
		$keyword = array('token' => session('token'),
						'node' => $authcat_id,
						'keyword' => $input['keyword'],
						'code_keyword' => $input['code_keyword'],
						'text' => $input['text']);
		if (!$this->rptk_add_keyword($keyword))
		{
			$this->rptk_delete_node($authcat_id);
			return self::DB_ERROR;
		}
		return true;
	}

	private function rptk_edit($input)
	{
		$data = D('Rippleos_node');
		$node=D('Rippleos_node')->where(array('token'=>session('token')))->find();
		$wx=D('Wxuser')->where(array('token'=>session('token')))->find();
		if ($wx == false)
		{
			return self::WX_NAME_ERROR;
		}
		$node_info = array();
		$node_info = $input;
		unset($input['wx_id']);
		unset($input['wx_name']);
		$node_info['wx_id'] = $wx['weixin'];
		$node_info['wx_name'] = $wx['wxname'];
		$authcat_id = $this->rptk_update_node($node['node'], $node_info);
		if ($authcat_id < 0)
		{
			return $this->err_msg[abs($authcat_id)];
		}
		$keyword = array('keyword' => $input['keyword'],
						'text' => $input['text'],
						'code_keyword' => $input['code_keyword']);
		if (!$this->rptk_update_keyword($keyword))
		{
			return self::DB_ERROR;
		}
		return true;
	}

	private function rptk_add_keyword($keyword)
	{
		$data = D('Rippleos_node');
		if ($id = $data->add($keyword))
		{
		    $da['pid']     = $id;
		    $da['module']  = 'RippleOS_url';
		    $da['token']   = session('token');
		    $da['keyword'] = $keyword['keyword'];
		    $code['pid']     = $id;
		    $code['module']  = 'RippleOS_code';
		    $code['token']   = session('token');
		    $code['keyword'] = $keyword['code_keyword'];
		    M('Keyword')->add($da);
		    M('Keyword')->add($code);
			return true;
		}
		else
		{
			return false;
		}
	}

	private function rptk_update_keyword($keyword)
	{
		$data = D('Rippleos_node');
		$node=D('Rippleos_node')->where(array('token'=>session('token')))->find();
		if ($data->where(array('id' => $node['id']))->save($keyword))
		{
			$wh = array();
		    $wh['pid']     = $node['id'];
		    $wh['module']  = 'RippleOS_url';
		    $wh['token']   = session('token');
		    M('Keyword')->where($wh)->save(array('keyword' => $keyword['keyword']));
			$code = array();
		    $code['pid']     = $node['id'];
		    $code['module']  = 'RippleOS_code';
		    $code['token']   = session('token');
		    M('Keyword')->where($code)->save(array('keyword' => $keyword['code_keyword']));
		}
		return true;
	}

	private function rptk_display(&$node_info)
	{
		//if ((strlen($this->node_api_id) == 0)
//			|| (strlen($this->node_api_key) == 0)
//			|| (strlen($this->wx_auth_api_id) == 0)
//			|| (strlen($this->wx_auth_api_key) == 0))
//		{
//			echo '请联系管理员开通微WIFI功能';
//			return false;
//		}
		$token=session('token');
		$node=D('Rippleos_node')->where(array('token'=>$token))->find();
		if (is_array($node))
		{
			$node_info = $this->rptk_retrieve_node($node['node']);
			if ($node_info['status'] < 0)
			{
				if (abs($node_info['status']) == 5)
				{
					M('Keyword')->where(array('module'=>'RippleOS_url', 'token'=>$token))->delete();
					M('Keyword')->where(array('module'=>'RippleOS_code', 'token'=>$token))->delete();
					D('Rippleos_node')->where(array('token'=>$token))->delete();
					$node_info=null;				
				}
				else
				{
					echo $this->err_msg[abs($node_info['status'])];
					return false;
				}					
			}
			else
			{
				$node_info['created_at'] = date('Y-m-d H:i:s', $node_info['create_time']);
				$node_info['updated_at'] = date('Y-m-d H:i:s', $node_info['update_time']);
				$node_info['is_portal'] = $node_info['is_portal'] ? '1' : '0';
				$node_info['hide_cp'] = $node_info['hide_cp'] ? '1' : '0';
				$node_info['keyword'] = $node['keyword'];
				$node_info['code_keyword'] = $node['code_keyword'];
				$node_info['text'] = $node['text'];
			}
		}
                $node_info['bind_name'] = $this->bind_name;
		$node_info['bind_key'] = $this->bind_key;
		$node_info['token'] = $token;
		return true;
	}

    public function _initialize()
    {
        parent::_initialize();
    	$this->node_api_id = C('rptk_node_api_id');
    	$this->node_api_key = C('rptk_node_api_key');
    	$this->bind_name = C('rptk_bind_name');
    	$this->bind_key = C('rptk_bind_key');
    	$this->wx_auth_api_id = C('rptk_wx_auth_api_id');
    	$this->wx_auth_api_key = C('rptk_wx_auth_api_key');
		$this->err_msg = array(' ',
								'请求格式错误',
								'参数不完整',
								'参数类型错误',
								'服务器错误',
								'节点不存在',
								'节点API ID或KEY错误',
								'节点名重复');
    }

	private function rptk_update_node($authcat_id, $input_info){
		if (!is_array($input_info))
		{
			return -1;
		}
		$node_info = array('api_id' => $this->node_api_id,
							'api_key' => $this->node_api_key,
							'login_url' => htmlspecialchars_decode($input_info['login_url']),
							'success_url' => htmlspecialchars_decode($input_info['success_url']),
							'login_timeout' => intval($input_info['login_timeout'] == null ? 1440 : $input_info['login_timeout']),
							'is_portal' => $input_info['is_portal'] == '1' ? true : false,
							'weixin_login' => true,
							'wx_id' => $input_info['wx_id'],
							'wx_name' => $input_info['wx_name'],
							'wx_unauth_timeout' => empty($input_info['wx_unauth_timeout'])? 10 : intval($input_info['wx_unauth_timeout']),
							'wx_reject_timeout' => empty($input_info['wx_reject_timeout'])? 3 : intval($input_info['wx_reject_timeout']),
							'hide_cp' => $input_info['hide_cp'] == '1' ? true : false,
							'auth2nd' => intval($input_info['auth2nd']));

		if ($authcat_id == null)
		{
			$node_info['name'] = $input_info['name'];
			if (!empty($input_info['white_list']))
			{
				$node_info['white_list'] = $input_info['white_list'];
			}
			$receive = $this->rptk_send_msg(self::AUTHCAT_CREATE_NODE, $node_info);
		}
		else
		{
			$node_info['node'] = intval($authcat_id);
			$node_info['white_list'] = empty($input_info['white_list']) ? null : $input_info['white_list'];
			$receive = $this->rptk_send_msg(self::AUTHCAT_UPDATE_NODE, $node_info);
		}
		if ($receive['status'] === 0)
		{
			return $receive['node'];
		}
		return $receive['status'];
	}

	public function rptk_delete_node($authcat_id){
		$node_info = array('api_id' => $this->node_api_id,
							'api_key' => $this->node_api_key,
							'node' => intval($authcat_id));
		$receive = $this->rptk_send_msg(self::AUTHCAT_DELETE_NODE, $node_info);
		if ($receive['status'] == 0)
		{
			return 0;
		}
		return -1;
	}

	private function rptk_retrieve_node($authcat_id){
		$node_info = array('api_id' => $this->node_api_id,
							'api_key' => $this->node_api_key,
							'node' => intval($authcat_id));
		$receive = $this->rptk_send_msg(self::AUTHCAT_RETRIEVE_NODE, $node_info);
		$receive['created_at'] = date('Y-m-d H:i:s', $receive['create_time']);
		$receive['updated_at'] = date('Y-m-d H:i:s', $receive['update_time']);
		return $receive;
	}

	private function rptk_send_msg($action, $msg){
		//echo '<br>post url: ';
		//var_dump(self::AUTHCAT_NODE_API.$action);
		//echo '<br>post msg: ';
		//var_dump(json_encode($msg));
		$ret = json_decode($this->rptk_post_json(self::AUTHCAT_NODE_API.$action, json_encode($msg)), true);
		//echo '<br>post msg ret:';
		//var_dump($ret);
		//echo '<br><br>';
		if (!is_array($ret))
		{
			return array('status' => -999,
						'err_msg' => 'Received nothing');
		}
		return $ret;
	}

	private function rptk_post_json($url, $jsonData){
		$ch = curl_init($url) ;
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}
?>