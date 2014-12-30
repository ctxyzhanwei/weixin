<?php
class HairdressingAction extends BaseAction{
    public function index(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);


        $t_hairdressing = D('Hairdressing');
        $reslist =  $t_hairdressing->where(array('token'=>$token))->find();
        $compay = M('Company');
        if(!empty($reslist)){
            $where = array('token'=>$token,'isbranch'=>1,'id'=>$reslist['bls_id']);
            $compay_list =  $compay->where($where)->find();
            $reslist = array_merge($reslist,$compay_list);
        }

        $t_show = M('Hairdressing_show');
        $where2 = array('token'=>$token,'type'=>'meirong');
        $shows = $t_show->where($where2)->find();

        $t_hairdressing_i = D('Hairdressing_item');
        $items = $t_hairdressing_i->where(array('token'=>$token))->order('iid DESC')->select();
        $t_reservebook= M('Reservebook');
        $where4 = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'meirong');
        $count = $t_reservebook->where($where4)->count();
        if(IS_POST){
            $_POST['booktime'] = time();
            if($id=$t_reservebook->data($_POST)->add()){
                $this->success('添加成功',U('Hairdressing/mylist',array('token'=>$token,'wecha_id'=>$wecha_id)));exit;
            }else{
                $this->error('服务器繁忙,添加失败,请稍候再试',array('token'=>$token,'wecha_id'=>$wecha_id));exit;
            }
        }
        $this->assign('count',$count);
        $this->assign('items',$items);
        $this->assign('shows',$shows);
        $this->assign('reslist', $reslist);
        $this->display();
    }

    public function mylist(){
        $token      = $this->_get('token');
        $wecha_id   = $this->_get('wecha_id');
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $t_reservebook= M('Reservebook');
        $where = array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>'meirong');
        $books = $t_reservebook->where($where)->order('id DESC')->select();
        $t_hairdressing = D('Hairdressing');
        $head_url =  $t_hairdressing->where(array('token'=>$token))->getField('head_url');
        $this->assign('head_url',$head_url);
        $this->assign('books',$books);
        $this->display();
    }

    public function del(){
        $id = (int)$this->_get('id');
        $token = $this->_get('token');
        $wecha_id = $this->_get('wecha_id');
        $type = $this->_get('type');
        $t_book   =   M('Reservebook');
        $check = $t_book->where(array('id'=>$id,'wecha_id'=>$wecha_id,'token'=>$token,'type'=>$type))->find();
        if($check){
            $t_book->where(array('id'=>$check['id']))->delete();
            $this->success('删除成功',U('Hairdressing/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$this->_get('type'))));
             exit;
         }else{
            $this->error('非法操作',array('id'=>$id,'wecha_id'=>$wecha_id,'token'=>$token,'type'=>$type));
             exit;
         }

    }


}