<?php

/**
 * 通用模板管理
 * */
class TmplsAction extends UserAction {

    public function index() {

			
        $db = D('Wxuser');
        $where['token'] = session('token');
        $where['uid'] = session('uid');
        $info = $db->where($where)->find();
		include('./PigCms/Lib/ORG/index.Tpl.php');
		
		foreach($tpl as $k=>$v){
			$sort[$k] = $v['sort'];
			$tpltypeid[$k] = $v['tpltypeid'];
		}
		array_multisort($sort, SORT_DESC , $tpltypeid , SORT_DESC ,$tpl);
		$this->assign('info', $info);
		$this->assign('tpl',$tpl);
		
		
		$whe['token']=session('token');
		$whe['fid']=intval($_GET['fid']);
		if(isset($_GET['cid'])){
			$whe['fid'] = (int)$_GET['cid'];
		}
		$Classify=D('Classify');
		$classinfo=$Classify->where($whe)->order('sorts desc')->select();

		$this->assign('classinfo',$classinfo);
		
		
		
        $this->display();
    }
	
	public function QRcode(){
		include './PigCms/Lib/ORG/phpqrcode.php';
		$viewUrl = C('site_url').U('Wap/Index/index',array('token'=>$this->token));
        $url = urldecode($viewUrl);
		QRcode::png($url,false,0,8);
	}

    public function add() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
		
		include('./PigCms/Lib/ORG/index.Tpl.php');
		
		foreach ($tpl as $k=>$v){
		
			if($gets == $v['tpltypeid']){
				$data['tpltypeid'] = $v['tpltypeid'];
                $data['tpltypename'] = $v['tpltypename'];
			}
		
		
		}

        $where['token'] = session('token');

		S("homeinfo_".$where['token'],NULL);
		S("wxuser_".$where['token'],NULL);
		
        $db->where($where)->save($data);
        
        M('Home')->where(array('token'=>session('token')))->save(array('advancetpl'=>0));
        if (isset($_GET['noajax'])) {
        	$this->success('设置成功','/index.php?g=User&m=Tmpls&a=index&token='.$this->token);
        }
    }

    public function lists() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
        switch ($gets) {
            case 4:
                $data['tpllistid'] = 4;
                $data['tpllistname'] = 'ktv_list';
                break;
            case 1:
                $data['tpllistid'] = 1;
                $data['tpllistname'] = 'yl_list';
                break;
        }
        $where['token'] = session('token');
        $db->where($where)->save($data);
    }

    public function content() {
        $gets = $this->_get('style');
        $db = M('Wxuser');
        switch ($gets) {
            case 1:
                $data['tplcontentid'] = 1;
                $data['tplcontentname'] = 'yl_content';
                break;
            case 3:
                $data['tplcontentid'] = 3;
                $data['tplcontentname'] = 'ktv_content';
                break;
        }
        $where['token'] = session('token');
        $db->where($where)->save($data);
    }
    
    public function background() {
        $data['color_id'] = $this->_get('style');
        $db = M('Wxuser');
        $where['token'] = session('token');

		S("homeinfo_".$where['token'],NULL);
		S("wxuser_".$where['token'],NULL);
        $db->where($where)->save($data);
    }

    public function insert() {
        
    }

    public function upsave() {
	
    }

}

?>