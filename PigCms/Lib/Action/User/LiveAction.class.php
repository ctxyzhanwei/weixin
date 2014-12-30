<?php

class LiveAction extends UserAction{

    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction("Live");
       
        $company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
        if(empty($company)) {
			$this->error('您还没有添加您的公司信息',U('Company/index',array('token' => $this->token)));
		}

    }

    public function index(){

        $search     = $this->_post('search','trim');
        $where      = array('token'=>$this->token);
        if($search){
            $where['name|keyword']  = array('like','%'.$search.'%');
        }

        $count      = M('Live')->where($where)->count();
        $Page       = new Page($count,15);
        $list       = M('Live')->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$Page->show());
        $this->assign('list',$list);
        $this->display();
    
    }

    public function add(){
        $keyword_db     = M('Keyword'); //关键词
        $id             = $this->_get('id','intval');
        $where          = array('token'=>$this->token,'id'=>$id);
        $live_info      = M('Live')->where($where)->find();
        if(IS_POST){

            if(D('Live')->create()){
                if($live_info){
                    $up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
                    M('live')->where($where)->save($_POST);

                   /* $keyword['pid']     = $this->_post('id','intval');
                    $keyword['module']  = 'Live';
                    $keyword['token']   = $this->token;
                    $keyword['keyword'] = $this->_post('keyword','trim');
                    $keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('id','intval'),'module'=>'Live'))->save($keyword);//更新关键词表
*/
                    $this->handleKeyword($this->_post('id','intval'),'Live',$this->_post('keyword','trim'));
                    $this->success('修改成功',U('Live/index',array('token'=>$this->token)));
                
                }else{
                    $_POST['token']     = $this->token;
                    $_POST['add_time']  = time();

                    $id                 = M('live')->add($_POST);
                    /*$keyword['pid']     = $id;
                    $keyword['module']  = 'Live';
                    $keyword['token']   = $this->token;
                    $keyword['keyword'] = $this->_post('keyword','trim');
                    $keyword_db->add($keyword);*/

                    $this->handleKeyword($id,'Live',$this->_post('keyword','trim'));
                    $this->success('添加成功',U('Live/index',array('token'=>$this->token)));
                }

            }else{
                $this->error(D('Live')->getError());
            }

        }else{

            $this->assign('info',$live_info);
            $this->display();
        }
    }


    public function del(){
        $id     = $this->_get('id','intval');
        $where  = array('token'=>$this->token,'id'=>$id);
        if(M('Live')->where($where)->delete()){
            M('Live_content')->where(array('token'=>$this->token,'live_id'=>$id))->delete();
            M('Live_company')->where(array('token'=>$this->token,'live_id'=>$id))->delete();
            M('Keyword')->where(array('token'=>$this->token,'pid'=>$id,'module'=>'Live'))->delete();
            $this->success('删除',U('Live/index',array('token'=>$this->token)));
        }
    }


    /*场景内容管理*/
    public function content(){
        $live_id    = $this->_get('id','intval');
        $search     = $this->_post('search','trim');
        $type     = $this->_post('type','intval');
        $where      = array('token'=>$this->token,'live_id'=>$live_id);
        
        if($search){
            $where['name']  = array('like','%'.$search.'%');
        }

        if($type>0){
            $where['type']  = $type;
        }

        $count      = M('Live_content')->where($where)->count();
        $Page       = new Page($count,15);
        $list       = M('Live_content')->where($where)->order('sort desc,add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$Page->show());
        $this->assign('live_id',$live_id);
        $this->display();
    }

    public function content_add(){
        $live_id    = $this->_get('live_id','intval');
        $id         = $this->_get('id','intval');

        $info       = M('Live_content')->where(array('token'=>$this->token,'id'=>$id,'live_id'=>$live_id))->find();
        if(IS_POST){
            if($info){
                $where  = array('token'=>$this->token,'live_id'=>$this->_post('live_id','intval'),'id'=>$this->_post('id','intval'));
                
                M('Live_content')->where($where)->save($_POST);
                
                $this->success('修改成功',U('Live/content',array('token'=>$this->token,'id'=>$this->_post('live_id','intval'))));
            }else{
                $_POST['add_time']      = time();
                $_POST['token']         = $this->token;

                if(M('Live_content')->add($_POST)){
                    $this->success('添加成功',U('Live/content',array('token'=>$this->token,'id'=>$this->_post('live_id','intval'))));
                }
            }

        }else{

            $this->assign('info',$info);
            $this->assign('live_id',$live_id);
            $this->display();
        }
    }

    public function content_del(){
        $live_id    = $this->_get('live_id','intval');
        $id         = $this->_get('id','intval');
        $where  = array('token'=>$this->token,'live_id'=>$live_id,'id'=>$id);
        
        if(M('Live_content')->where($where)->delete()){
            $this->success('删除成功',U('Live/content',array('token'=>$this->token,'id'=>$live_id)));
        }
    }

    /*添加分支商户信息*/

    public function company(){
        $live_id    = $this->_get('id','intval');
        $search     = $this->_post('search','trim');
        $where      = array('token'=>$this->token,'live_id'=>$live_id);
        
        if($search){
            $where['name']  = array('like','%'.$search.'%');
        }

        $count      = M('Live_company')->where($where)->count();
        $Page       = new Page($count,15);
        $list       = M('Live_company')->where($where)->order('sort desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$Page->show());
        $this->assign('live_id',$live_id);
        $this->display();
    }

    public function company_add(){
        $live_id    = $this->_get('live_id','intval');
        $id         = $this->_get('id','intval');
        $info       = M('Live_company')->where(array('token'=>$this->token,'id'=>$id,'live_id'=>$live_id))->find();

        if(IS_POST){
            if(D('Live_company')->create()){
                if($info){
                    $where  = array('token'=>$this->token,'live_id'=>$this->_post('live_id','intval'),'id'=>$this->_post('id','intval'));  
                    
                    M('Live_company')->where($where)->save($_POST);
                    $this->success('修改成功',U('Live/company',array('token'=>$this->token,'id'=>$this->_post('live_id','intval'))));
                }else{
                    $_POST['token']         = $this->token;

                    if(M('Live_company')->add($_POST)){
                        $this->success('添加成功',U('Live/company',array('token'=>$this->token,'id'=>$this->_post('live_id','intval'))));
                    }
                }
            }else{
                $this->error(D('Live_company')->getError());
            }
        }else{
            $this->assign('info',$info);
            $this->assign('live_id',$live_id);
            $this->display();
        }
    }

    public function company_del(){
        $live_id    = $this->_get('live_id','intval');
        $id         = $this->_get('id','intval');
        $where  = array('token'=>$this->token,'live_id'=>$live_id,'id'=>$id);
        
        if(M('Live_company')->where($where)->delete()){
            $this->success('删除成功',U('Live/company',array('token'=>$this->token,'id'=>$live_id)));
        }
    }

    public function company_list(){
        $where      = array('token'=>$this->token,'display'=>1);
        $data       = M('Company')->where($where)->order('id desc')->select();
        
        $this->assign('data',$data);
        $this->display();
    }

}

?>