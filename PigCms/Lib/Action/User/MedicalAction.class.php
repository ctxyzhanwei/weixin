<?php
/**
 * User 微医疗
**/
class MedicalAction extends UserAction{

    public function _initialize() {
        parent::_initialize();
        $function=M('Function')->where(array('funname'=>'medical'))->find();
        if (intval($this->user['gid'])<intval($function['gid'])){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>$this->token)));
        }

        $this->canUseFunction('medical');
    }

    public function index(){
        $tb_reservation = D('Reservation');
        $where = array('token'=>session('token'),'addtype'=>'medical');
        $reslist=$tb_reservation->where($where)->find();
        $_POST['addtype']   =   'medical';
        if(IS_POST){
            $_POST['token']=session('token');
            if($reslist==false){
                if($tb_reservation->create()){
                    if($tb_reservation->add()){
                        $this->success('操作成功');
                    }else{
                        $this->error('服务器繁忙，请稍候再试');
                    }
                }else{
                    $this->error($tb_reservation->getError());
                }

            }else{
                $id=(int)$_POST['id'];
                $where = array('token'=>session('token'),'addtype'=>'medical','id'=>$id);
                if($tb_reservation->create()){
                    if($tb_reservation->where($where)->save()!=false){
                        $this->success('操作成功');
                    }else{
                        $this->error('服务器繁忙，请稍候再试');
                    }
                }else{
                    $this->error($tb_reservation->getError());
                }
            }
        }else{
            $this->assign('reslist',$reslist);
            $this->display();
        }
    }

    public function aboutus(){

        $t_company = M('Company');
        $token = session('token');
        $where =  array('token'=>$token,'shortname'=>'Medical');
        $check = $t_company->where($where)->find();

        $this->assign('set',$check);

        if(IS_POST){

            if($check == null){

                    if($t_company->add($_POST)){
                        $this->success('添加成功',U('Medical/aboutus',array('token'=>session('token'))));
                        exit;
                    }else{
                        $this->error('服务器繁忙,请稍候再试');exit;
                    }
           }else{
             $wh = array('id'=>$this->_post('id'));

             if($t_company->where($wh)->save($_POST)){
                    $this->success('修改成功',U('Medical/aboutus',array('token'=>session('token'))));
                    exit;
                }else{
                    $this->error('操作失败');exit;
                }
           }
        }

        $this->display();
    }

    public function setIndex(){
        $Photo = M("Photo");
        $photo = $Photo->where(array('token'=>session('token')))->order('id desc')->select();
        $this->assign('photo',$photo);
        $data = D('Medical_set');
        $where = array('token'=>session('token'));
        $classify = M('Classify')->where($where)->field('id as cid,name')->order('id DESC')->select();
        $this->assign('classify',$classify);
        $medicalSet = $data->where($where)->find();

        if(IS_POST){
            if($medicalSet == NULL){
                      if($data->create()!=false){

                        if($id=$data->data($_POST)->add()){
                                $data1['pid']=$id;
                                $data1['module']='medicalSet';
                                $data1['token']=session('token');
                                $data1['keyword']=trim($_POST['keyword']);
                                M('Keyword')->add($data1);
                                $this->success('添加成功',U('Medical/setIndex',array('token'=>session('token'))));
                                 exit;
                        }else{
                            $this->error('添加操作失败,请检查是否有空项,或者重复体提交!');exit;
                        }

                    }else{
                        $this->error($data->getError());exit;
                    }
            }else{
                // change
            $_id =filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            $wh = array('token'=>session('token'),'id'=>(int)$_id);
             if($data->where($wh)->save($_POST)){
                    $data1['pid']=$_id;
                    $data1['module']='medicalSet';
                    $data1['token']=session('token');
                    $da['keyword']=trim($this->_post('keyword'));
                    M('Keyword')->where($data1)->save($da);

                    $this->success('修改成功',U('Medical/setIndex',array('token'=>session('token'))));exit;
                }else{
                    $this->error('修改操作失败,请检查是否有空项,或者重复体提交!');exit;
                }

            }

        }else{
          $this->assign('medicalSet',$medicalSet);
           $this->display();
        }

    }

    public function reser_manage(){
        $t_reservebook = M('Medical_user');

        $where = array('token'=>session('token'),'type'=>'medical');
        $count      = $t_reservebook->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $books = $t_reservebook->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('iid DESC')->select();
        $this->assign('page',$show);

        $this->assign('books',$books);
        $this->assign('count',$t_reservebook->where($where)->count());
        $this->assign('ok_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>1))->count());
        $this->assign('lose_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>2))->count());
        $this->assign('call_count',$t_reservebook->where(array('token'=>session('token'),'remate'=>0))->count());
        $this->display();
    }

    public function reser_uinfo(){
        $iid = $this->_get('iid');
        $token = $this->_get('token');
        $where = array('iid'=>$iid,'token'=>$token);
        $t_reservebook = M('Medical_user');
        $userinfo = $t_reservebook->where($where)->find();
        $this->assign('userinfo',$userinfo);
        if(IS_POST){
            $iid = $this->_post('iid');
            $token = session('token');
            $where =  array('iid'=>$iid,'token'=>$token);
            $ok = $t_reservebook->where($where)->save($_POST);
            if($ok){
                $this->assign('ok',1);
            }else{
                     $this->assign('ok',2);
            }

        }
        $this->display();
    }

     public function manage_del(){

        $iid = $this->_get('iid');
        $t_reservebook = M('Medical_user');
        $where = array('iid'=>$iid,'token'=>$this->_get('token'));
        $check  = $t_reservebook->where($where)->find();

        if(!empty($check)){
                $t_reservebook->where(array('iid'=>$check['iid']))->delete();
                $this->success('删除成功',U('Medical/reser_manage',array('token'=>session('token'))));
                exit;
        }else{
            $this->error('非法操作！');
            exit;
        }
    }

}
?>