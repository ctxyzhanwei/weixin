<?php
class HairdressingAction extends UserAction{

     public function _initialize() {
        parent::_initialize();
        $function=M('Function')->where(array('funname'=>'Hairdressing'))->find();
        if (intval($this->user['gid'])<intval($function['gid'])){
            $this->error('您还开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>$this->token)));
        }
    }

    public function index(){
        $compay = M('Company');
        $where = array('token'=>session('token'),'isbranch'=>1);
        $compay_list =  $compay->where($where)->field('id,name')->order('taxis DESC')->select();
        $t_hairdressing = D('Hairdressing');
        $setindex = $t_hairdressing->where(array('token'=>session('token')))->find();
        if(IS_POST){
            $filters = array(
                'keyword'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'title'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'head_url'=>array(
                    'filter'=>FILTER_VALIDATE_URL
                ),
                'info'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'bls_id'=>array('filter'=>FILTER_VALIDATE_INT)
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('表单包含敏感字符!'));
            }else{
                $_POST['token'] = session('token');

                if(!$t_hairdressing->create()){
                    exit($this->error($t_hairdressing->getError()));
                }else{
                    $hid = filter_var($this->_post('hid'),FILTER_VALIDATE_INT);
                    $type = filter_var($this->_post('type'),FILTER_SANITIZE_STRING);

                    if('edit'==$type && $hid != ''){
                        $o =  $t_hairdressing->where(array('hid'=>$hid, 'token'=>session('token')))->save($_POST);
                        if($o){
                            $data2['keyword'] = filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING);
                            M('Keyword')->where(array('pid'=>$hid,'token'=>session('token'),'module'=>'Hairdressing'))->data($data2)->save();
                            exit($this->success('修改成功',U('Hairdressing/index',array('token'=>session('token')))));
                        }else{
                            exit($this->error('修改失败',U('Hairdressing/index',array('token'=>session('token')))));
                        }
                    }else{

                        if($id=$t_hairdressing->data($_POST)->add()){
                            $data1['pid']=$id;
                            $data1['module']='Hairdressing';
                            $data1['token']=session('token');
                            $data1['keyword']=filter_var($this->_post('keyword'),FILTER_SANITIZE_STRING);
                            M('Keyword')->add($data1);
                            $this->success('添加成功',U('Hairdressing/index',array('token'=>session('token'))));exit;
                        }else{
                            $this->error('服务器繁忙,添加失败,请稍候再试');exit;
                        }
                    }//edit & add
                }

            }
        }
        $this->assign('setindex',$setindex);
        $this->assign('compay_list',$compay_list);
        $this->display();
    }

    public function setmeal(){
        $data = D('Hairdressing_item');
        $where = array('token'=>session('token'));
        $count      = $data->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $items = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);

        $this->assign('items',$items);
        $this->display();
    }

    public function setmeal_add(){
        $t_hairdressing_i = D('Hairdressing_item');
        $iid = filter_var($this->_get('iid'),FILTER_VALIDATE_INT);
        $where = array('token'=>session('token'),'iid'=>$iid);
        $items = $t_hairdressing_i->where($where)->find();
        if(IS_POST){
            $filters = array(
                'cname'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                ),
                'info_intro'=>array(
                    'filter'=>FILTER_SANITIZE_STRIPPED,
                    'flags'=>FILTER_SANITIZE_STRING,
                    'options'=>FILTER_SANITIZE_ENCODED
                )
            );

            $check = filter_var_array($_POST,$filters);
            if(!$check){
                exit($this->error('表单包含敏感字符!'));
            }else{

                $_POST['token'] = session('token');
                if(!$t_hairdressing_i->create()){
                    exit($this->error($t_hairdressing_i->getError()));
                }else{
                    $iid = filter_var($this->_post('iid'),FILTER_VALIDATE_INT);
                    $type = filter_var($this->_post('type'),FILTER_SANITIZE_STRING);

                    if('edit'==$type && $iid != ''){
                        $o =  $t_hairdressing_i->where(array('iid'=>$iid, 'token'=>session('token')))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U('Hairdressing/setmeal',array('token'=>session('token')))));
                        }else{
                            exit($this->error('修改失败',U('Hairdressing/setmeal',array('token'=>session('token')))));
                        }
                    }else{
                        if($id=$t_hairdressing_i->data($_POST)->add()){
                            $this->success('添加成功',U('Hairdressing/setmeal',array('token'=>session('token'))));exit;
                        }else{
                            $this->error('服务器繁忙,添加失败,请稍候再试');exit;
                        }
                    }//edit & add
                }

            }
        }
        $this->assign('items',$items);
        $this->display();
    }

    public function setmeal_del(){
        $iid = filter_var($this->_get('iid'),FILTER_VALIDATE_INT);
        $res = M('Hairdressing_item');
        $find = array('iid'=>$iid,'token'=>session('token'));
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('iid'=>$result['iid']))->delete();
            $this->success('删除成功',U('Hairdressing/setmeal',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

    public function formset(){
        $t_show = M('Hairdressing_show');
        $where = array('token'=>session('token'),'type'=>'meirong');
        $shows = $t_show->where($where)->find();

        if(IS_POST){
            $_POST['token'] = session('token');
            $id = filter_var($this->_post('id'),FILTER_VALIDATE_INT);
            if(isset($id) && $id != ''){
                        $o =  $t_show->where(array('id'=>$id, 'token'=>session('token'),'type'=>'meirong'))->save($_POST);
                        if($o){
                            exit($this->success('修改成功',U('Hairdressing/formset',array('token'=>session('token')))));
                        }else{
                            exit($this->error('修改失败',U('Hairdressing/formset',array('token'=>session('token')))));
                        }
            }else{
                        if($id=$t_show->data($_POST)->add()){
                            $this->success('添加成功',U('Hairdressing/formset',array('token'=>session('token'))));exit;
                        }else{
                            $this->error('服务器繁忙,添加失败,请稍候再试');exit;
                        }
            }//edit & add
        }
        $this->assign('shows',$shows);
        $this->display();
    }

    public function guanlist(){
        $t_reservebook = M('Reservebook');
        $where = array('token'=>session('token'),'type'=>'meirong');
        $count      = $t_reservebook->where($where)->count();
        $Page       = new Page($count,20);
        $show       = $Page->show();
        $books = $t_reservebook->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
        $this->assign('page',$show);
        $this->assign('books',$books);
        $this->assign('count',$t_reservebook->count());
        $this->assign('ok_count',$t_reservebook->where('remate=1')->count());
        $this->assign('lose_count',$t_reservebook->where('remate=2')->count());
        $this->assign('call_count',$t_reservebook->where('remate=0')->count());
        $this->display();

    }

    public function guanlist_edit(){
        $id = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $token = $this->_get('token');
        $where = array('id'=>$id,'token'=>$token);
        $t_reservebook = M('Reservebook');
        $userinfo = $t_reservebook->where($where)->find();
        $this->assign('userinfo',$userinfo);

        if(IS_POST){
            $id = $this->_post('id');
            $token = session('token');
            $where =  array('id'=>$id,'token'=>$token);
            $ok = $t_reservebook->where($where)->save($_POST);
            if($ok){
                $this->assign('ok',1);
            }else{
                $this->assign('ok',2);
            }

        }
        $this->display();
    }

    public function guanlist_del(){
        $id = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $type = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $res = M('Reservebook');
        $find = array('id'=>$id,'token'=>session('token'),'type'=>$type);
        $result = $res->where($find)->find();
         if($result){
            $res->where(array('id'=>$result['id'],'token'=>session('token')))->delete();
            $this->success('删除成功',U('Hairdressing/guanlist',array('token'=>session('token'))));
             exit;
         }else{
            $this->error('非法操作！');
             exit;
         }
    }

}