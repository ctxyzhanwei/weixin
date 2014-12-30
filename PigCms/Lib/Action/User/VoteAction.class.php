<?php
class VoteAction extends UserAction{


    public function index(){
        $this->canUseFunction('Vote');
        $id     = $this->_get('id','trim');
        $type   = $this->_request('type','trim');
        $keyword   = $this->_post('keyword','trim');
        $user   = M('Users')->field('gid,activitynum')->where(array('id'=>session('uid')))->find();
        $group  = M('User_group')->where(array('id'=>$user['gid']))->find();
        $this->assign('group',$group);
        $this->assign('activitynum',$user['activitynum']);

       // $type = isset($this->_get('type')) ? $this->_get('type') : 'text';
        $where  = array('token'=>session('token'));
        if($id){
            $where['id'] = array('in',explode(',', $id));
        }

        if($type){
            $where['type'] = $type;
        }

        if(!empty($keyword)){
            $where['title'] = array('like','%'.$keyword.'%');
        }

        $list=M('Vote')->where($where)->order('id DESC')->select();
        $count = M('Vote')->where($where)->count();
        $this->assign('count',$count);

        $scene  = M();
        $is     = $scene->query('SHOW TABLES LIKE "'.C('DB_PREFIX') .'wechat_scene"');
        if($is){
            $is_scene = M('wechat_scene')->where(array('is_open'=>'1','token'=>$this->token))->field('id')->find();
        }else{
            $is_scene = 0;
        }
        $this->assign('is_scene',$is_scene);
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->display();
    }

    public function totals(){
        $token      = session('token');
        $id         = $this->_get('id');
        $t_vote     = M('Vote');
        $t_record   = M('Vote_record');
        $where      = array('id'=>$id,'token'=>session('token'));
        $vote       = $t_vote->where($where)->find();
        if(empty($vote)){
            exit('非法操作');
        }


        $vote_item = M('Vote_item')->where('vid='. $vote['id'])->select();
        $vcount = $t_record->where(array('vid'=>$id))->count();
        $this->assign('count',$vcount);
        $item_count = M('Vote_item')->where('vid='.$id)->select();


        $xml='<chart borderThickness="0" caption="'.$vote['title'].'" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
        
        foreach ($item_count as $k=>$value) {
            $xml.='<set label="'.$value['item'].'" value="'.$value['vcount'].'"/>';
        }            
        $xml.='</chart>';

        $Page     = new Page($vcount,15);

        $record = $t_record->where(array('vid'=>$id))->limit($Page->firstRow.','.$Page->listRows)->select();

        foreach($record as $key=>$value){
            $name   = M('userinfo')->where(array('wecha_id'=>$value['wecha_id']))->getField('wechaname');
            $record[$key]['wxname']     = $name?$name:'匿名';
            $record[$key]['itemname']   = $this->_getItemName($value['item_id']);
        }



        $this->assign('page',$record);
        $this->assign('page',$Page->show());
        $this->assign('record',$record);
        $this->assign('xml',$xml);
        $this->assign('vote_item', $vote_item);
        $this->assign('vote',$vote);
        $this->display();
    }


    public function del_record(){

        $id = $this->_get('id','intval');
        $record_info = M('vote_record')->where(array('token'=>$this->token,'id'=>$id))->find();
        if(M('vote_record')->where(array('id'=>$id))->delete()){
            M('vote_item')->where(array('id'=>array('in',"{$record_info['item_id']}")))->setDec('vcount',1);
            M('vote')->where(array('token'=>$this->token,'id'=>$record_info['vid']))->setDec('count',1);
 
            $this->success('删除成功',U('Vote/index',array('token'=>session('token'))));
        }

    }


    public function _getItemName($item_id){
        $id     = explode(',', $item_id);
        $name   = '';

        foreach ($id as $key => $value) {
            if($value){
                $name .= M('Vote_item')->where('id='. $value)->getField('item').',';
            }
        }

        return rtrim($name,',');
    }

    public function add(){
     $this->assign('type',$this->_get('type'));
        if(IS_POST){
         //var_dump($_REQUEST);exit;
            $adds = $_REQUEST['add'];
            if(empty($adds) || empty($_REQUEST['add']['item'][0]) && empty($_REQUEST['add']['startpicurl'][0])){
                $this->error('投票选项你还没有填写');
                exit;
            }
            foreach ($adds as $ke => $value) {
                 foreach ($value as $k => $v) {
                    if($v != "")
                     $item_add[$k][$ke]=$v;
                 }
            }
            $data=D('Vote');
            //$_POST['id']=$this->_get('id');
            $_POST['token']=session('token');
            $_POST['type'] = $this->_get('type');
            $_POST['statdate']=strtotime($this->_post('statdate'));
            $_POST['enddate']=strtotime($this->_post('enddate'));
            $_POST['cknums'] = $this->_post('cknums');
            $_POST['display'] = intval($this->_post("display"));
            $_POST['info'] = strip_tags($this->_post("info"));
            $_POST['picurl'] = $this->_post("picurl");
            $_POST['title'] = $this->_post("title");
            $_POST['keyword'] = $this->_post('keyword');

            if($_POST['enddate']<$_POST['statdate']){
                $this->error('结束时间不能小于开始时间!');
                exit;
            }

            //$isset_keyword = $data->where(array('keyword' => $_POST['keyword'],'token'=>$_POST['token']))->field('keyword')->find();
            //if($isset_keyword != NULL){
             //   $this->error('关键词已经存在！');
              //  exit;
           // }
            $t_item = M('Vote_item');

            if($data->create()!=false){
                if($id=$data->add()){
                    foreach ($item_add as $k => $v) {
                      if($v['item'] != ''){
                        $data2['vid'] = $id;
                        $data2['item']=$v['item'];
                        $data2['rank']=empty($v['rank']) ? "1" : $v['rank'];
                        $data2['vcount']=empty($v['vcount']) ? "0" : $v['vcount'];
                        if($_POST['type'] == 'img' || $this->_get('type') == 'scene'){
                            $data2['startpicurl']=empty($v['startpicurl']) ? "#" : $v['startpicurl'];
                            $data2['tourl']=empty($v['tourl']) ? "#" : $v['tourl'];
                        }
                        $t_item->add($data2);
                      }

                    }
                    $data1['pid']=$id;
                    $data1['module']='Vote';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
                    if($this->_get('type') != 'scene'){
                        M('keyword')->add($data1);
                    }
                    //$ukeywordser=M('Users')->where(array('id'=>session('uid')))->setInc('activitynum');
                    $this->success('添加成功',U('Vote/index',array('token'=>session('token'))));
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $this->display();
        }

    }

    public function del(){
        $type = $this->_get('type');
        $id = $this->_get('id');
        $vote = M('Vote');
        $find = array('id'=>$id,'type'=>$type);
        $result = $vote->where($find)->find();
         if($result){
            $vote->where('id='.$result['id'])->delete();
            M('Vote_item')->where('vid='.$result['id'])->delete();
            M('Vote_record')->where('vid='.$result['id'])->delete();
            $where = array('pid'=>$result['id'],'module'=>'Vote','token'=>session('token'));
            M('Keyword')->where($where)->delete();
            $this->success('删除成功',U('Vote/index',array('token'=>session('token'))));
         }else{
            $this->error('非法操作！');
         }

    }

    public function setinc(){
        $id=$this->_get('id');
        $where=array('id'=>$id,'token'=>session('token'));
        $check=M('Vote')->where($where)->find();
        if($check==NULL)$this->error('非法操作');
        $user=M('Users')->field('gid,activitynum')->where(array('id'=>session('uid')))->find();
        $group=M('User_group')->where(array('id'=>$user['gid']))->find();
        if($user['activitynum']>=$group['activitynum']){
            $this->error('您的免费活动创建数已经全部使用完,请充值后再使用',U('Home/Index/price'));
        }
        if ($check['status']==0){
            $data=M('Vote')->where($where)->save(array('status'=>1));
            $tip='恭喜你,活动已经开始';
        }else {
            $data=M('Vote')->where($where)->save(array('status'=>0));
            $tip='设置成功,活动已经结束';
        }

        if($data!=NULL){
            $this->success($tip);
        }else{
            $this->error('设置失败');
        }

    }
    public function setdes(){
        $id=$this->_get('id');
        $where=array('id'=>$id,'token'=>session('token'));
        $check=M('Vote')->where($where)->find();
        if($check==NULL)$this->error('非法操作');
        $data=M('Vote')->where($where)->setDec('status');
        if($data!=NULL){
            $this->success('活动已经结束');
        }else{
            $this->error('服务器繁忙,请稍候再试');
        }

    }

    public function edit(){
        $this->assign('type',$this->_get('type'));
        if(IS_POST){
            $data=D('Vote');
            $_POST['id']= (int)$this->_post('id');
            $_POST['token']=session('token');
            $_POST['type'] = $this->_get('type');
            $_POST['statdate']=strtotime($this->_post('statdate'));
            $_POST['enddate']=strtotime($this->_post('enddate'));
            $_POST['cknums'] = (int)$this->_post('cknums');
            $_POST['display'] = $this->_post("display");
            $_POST['info'] = strip_tags($this->_post("info"));
            $_POST['picurl'] = $this->_post("picurl");
            $_POST['title'] = $this->_post("title");
             if($_POST['enddate']<$_POST['statdate']){
                $this->error('结束时间不能小于开始时间!');
                exit;
            }
            $where=array('id'=>$_POST['id'],'token'=>session('token'));
            $check=$data->where($where)->find();

            if($check==NULL) exit($this->error('非法操作'));
            if(empty($_REQUEST['add'])){
                $this->error('投票选项必须填写');
                exit;
            }

            $t_item = M('Vote_item');
            $datas = $_REQUEST['add'];
            //$datas = array_filter($datas);
             foreach ($datas as $ke => $value) {
                 foreach ($value as $k => $v) {
                    if( $v != ""){
                        $item_add[$k][$ke]=$v;
                    }
                 }
            }

            $isnull =  $t_item->where('vid='.$_POST['id'])->find();

            foreach ($item_add as $k => $v) {
                $a++;
                if($v['item'] !=""){
                    $i_id['id']=$v['id'];
                    if($i_id['id'] != ''){
                        $data2['item']=$v['item'];
                        $data2['rank']=empty($v['rank']) ? "1" : $v['rank'];
                        $data2['vcount']=empty($v['vcount']) ? "0" : $v['vcount'];
                        if($this->_get('type') == 'img' || $this->_get('type') == 'scene'){
                            $data2['startpicurl']=$v['startpicurl'];
                            $data2['tourl']=empty($v['tourl']) ? "#" : $v['tourl'];
                        }
                      $t_item->where(array('id'=>$i_id['id'],'vid'=>$_POST['id']))->save($data2);

                    }else{

                            $data2['vid'] = $_POST['id'];
                            $data2['item']=$v['item'];
                            $data2['rank']=empty($v['rank']) ? "1" : $v['rank'];
                            $data2['vcount']=empty($v['vcount']) ? "0" : $v['vcount'];
                            if($_POST['type'] == 'img'){
                                $data2['startpicurl']=empty($v['startpicurl']) ? "#" : $v['startpicurl'];
                                $data2['tourl']=empty($v['tourl']) ? "#" : $v['tourl'];
                            }
                            $t_item->add($data2);

                    }
                }

            }

            if($data->create()){

                if($data->where($where)->save($_POST)){
                    $data1['pid']=$_POST['id'];
                    $data1['module']='Vote';
                    $data1['token']=session('token');

                    $da['keyword']=trim($_POST['keyword']);
                    if($this->_get('type') != 'scene'){
                        $ok = M('keyword')->where($data1)->save($da);
                    }
                    $this->success('修改成功!',U('Vote/index',array('token'=>session('token'))));exit;
                }else{
                    //$this->error('没有做任何修改！');exit;
                    $this->success('修改成功',U('Vote/index',array('token'=>session('token'))));exit;
                }
            }else{
                $this->error($data->getError());
            }


        }else{
            $id=(int)$this->_get('id');
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Vote');
            $check=$data->where($where)->find();
            if($check==NULL)$this->error('非法操作');
            $vo=$data->where($where)->find();
            $items = M('Vote_item')->where('vid='.$id)->order('rank DESC')->select();
            $this->assign('items',$items);

            $this->assign('vo',$vo);

            $this->display('add');
        }
    }

    public function del_tab(){
         $da['tid']      = strval($this->_post('id'));
         M('Vote_item')->where(array('id'=>$da['tid']))->delete();
         //$arr=array('errno'=>0,'tid'=>$da['tid']);
         //echo json_encode($arr);
         exit;
    }


}



?>