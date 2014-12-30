<?php
class ShakeAction extends UserAction{
    public $shake_model;
    public $token_where;
    public $keyword_model;
    public function _initialize(){
        parent :: _initialize();
        $this -> canUseFunction('shake');
        $this -> shake_model = M('Shake');
        $this -> token_where['token'] = $this -> token;
        $this -> keyword_model = M('Keyword');
    }
    public function index(){
        $count = $this -> shake_model -> where($this -> token_where) -> count();
        $page = new Page($count, 20);
        $info = $this -> shake_model -> where($this -> token_where) -> order('id desc') -> limit($page -> firstRow . ',' . $page -> listRows) -> select();
        $this -> assign('page', $page -> show());
        $this -> assign('info', $info);
        $this -> display();
    }
    public function add(){
        if (IS_POST){
            if (!trim($_POST['title'])){
                $this -> error('请填写标题');
            }
            $fileds = array('title', 'keyword', 'thumb', 'intro', 'background', 'backgroundmusic', 'music', 'rule', 'info', 'qrcode', 'logo', 'cheer');
            $row = array();
            foreach ($fileds as $f){
                $row[$f] = $this -> _post($f);
            }
            $intFields = array('clienttime', 'showtime', 'starttime', 'endshake', 'shownum', 'shaketype', 'isopen', 'usetime', 'isact');
            foreach ($intFields as $f){
                $row[$f] = intval($this -> _post($f));
            }
            $row['token'] = $this -> token;
            $row['time'] = time();
            $id = $this -> shake_model -> add($row);
            if ($id){
                $this -> keyword_model -> add(array('module' => 'Shake', 'pid' => $id, 'token' => $this -> token, 'keyword' => $row['keyword']));
            }
            $this -> success('添加成功', U('Shake/index', array('token' => session('token'))));
        }else{
            $info = array();
            $info['shaketype'] = 1;
            $info['isopen'] = 1;
            $info['clienttime'] = 3;
            $info['showtime'] = 3;
            $info['starttime'] = 3;
            $info['endshake'] = 600;
            $info['shownum'] = 10;
            $info['pass'] = '';
            $this -> assign('info', $info);
            $this -> display('set');
        }
    }
    public function edit(){
        if (IS_POST){
            if (!trim($_POST['title'])){
                $this -> error('请填写标题');
            }
            $fileds = array('title', 'keyword', 'thumb', 'intro', 'background', 'backgroundmusic', 'music', 'rule', 'info', 'qrcode', 'logo', 'cheer');
            $row = array();
            foreach ($fileds as $f){
                $row[$f] = $this -> _post($f);
            }
            $intFields = array('clienttime', 'showtime', 'starttime', 'endshake', 'shownum', 'shaketype', 'isopen', 'usetime', 'isact');
            foreach ($intFields as $f){
                $row[$f] = intval($this -> _post($f));
            }
            $updateWhere = array();
            $updateWhere['token'] = $this -> token;
            $updateWhere['id'] = intval($_POST['id']);
            $rt = $this -> shake_model -> where($updateWhere) -> save($row);
            if ($rt){
                $this -> keyword_model -> where(array('module' => 'Shake', 'pid' => $updateWhere['id'])) -> save(array('keyword' => $row['keyword']));
            }
            $this -> success('修改成功', U('Shake/index', array('token' => session('token'))));
        }else{
            $where['token'] = $this -> token;
            $where['id'] = $this -> _get('id', 'intval');
            $info = $this -> shake_model -> where($where) -> find();
            $this -> assign('info', $info);
            $this -> display('set');
        }
    }
    public function del(){
        $this -> token_where['id'] = intval($_GET['id']);
        $rt = $this -> shake_model -> where($this -> token_where) -> delete();
        if ($rt){
            $this -> keyword_model -> where(array('module' => 'Shake', 'pid' => $this -> token_where['id'])) -> delete();
            M('Wall_member') -> where(array('act_id' => $this -> token_where['id'], 'act_type' => '2', 'token' => $this -> token)) -> delete();
            M('shake_rt') -> where(array('token' => $this -> token, 'shakeid' => $this -> token_where['id'])) -> delete();
            $this -> success('操作成功', U(MODULE_NAME . '/index'));
        }
    }
    public function screen(){
        $this -> token_where['isopen'] = 1;
        $info = $this -> shake_model -> where($this -> token_where) -> find();
        $this -> assign('info', $info);
        $this -> display();
    }
    public function show_fens(){
        $id = $this -> _get('id', 'intval');
        $keyword = $this -> _post('keyword', 'trim');
        $where = array('token' => $this -> token, 'act_id' => $id, 'act_type' => '2');
        if(!empty($keyword)){
            $where['nickname|truename'] = array('like', '%' . $keyword . '%');
        }
        $count = M('Wall_member') -> where($where) -> count();
        $Page = new Page($count, 15);
        $list = M('Wall_member') -> where($where) -> order('time desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
        foreach ($list as $key => $value){
            $mwhere = array('token' => $this -> token, 'is_scene' => '0', 'shakeid' => $id, 'wecha_id' => $value['wecha_id']);
            $round = M('shake_rt') -> where($mwhere) -> max('round');
            $list[$key]['round'] = $round?$round:0;
        }
        $this -> assign('pid', $id);
        $this -> assign('page', $Page -> show());
        $this -> assign('list', $list);
        $this -> display();
    }
    public function show_rank(){
        $sceneid = $this -> _get('sceneid', 'intval');
        $wecha_id = M('Wall_member') -> where(array('token' => $this -> token, 'id' => $this -> _get('id', 'intval'))) -> getField('wecha_id');
        $where = array('token' => $this -> token, 'shakeid' => $this -> _get('pid', 'intval'), 'wecha_id' => $wecha_id);
        if(empty($sceneid)){
            $where['is_scene'] = '0';
        }else{
            $where['is_scene'] = '1';
        }
        $count = M('Shake_rt') -> where($where) -> count();
        $Page = new Page($count, 7);
        $list = M('Shake_rt') -> where($where) -> order('round desc') -> limit($Page -> firstRow . ',' . $Page -> listRows) -> select();
        foreach ($list as $key => $value){
            $rtwhere = array('token' => $this -> token, 'shakeid' => $this -> _get('pid', 'intval'), 'round' => $value['round'], 'is_scene' => $where['is_scene']);
            $user_arr = M('Shake_rt') -> where($rtwhere) -> order('count desc') -> getField('count', true);
            $ucount = M('Shake_rt') -> where($rtwhere) -> order('count desc') -> count();
            $list[$key]['rank'] = array_search($value['count'], $user_arr) + 1;
            $list[$key]['ucount'] = $ucount;
        }
        $this -> assign('info', $list);
        $this -> assign('page', $Page -> show());
        $this -> display();
    }
    public function del_fens(){
        $id = $this -> _get('id', 'intval');
        $pid = $this -> _get('pid', 'intval');
        $where = array('token' => $this -> token, 'id' => $id, 'act_type' => '2');
        $wecha_id = M('Wall_member') -> where($where) -> getField('wecha_id');
        if(M('Wall_member') -> where($where) -> delete()){
            M('Shake_rt') -> where(array('token' => $this -> token, 'is_scene' => '0', 'wecha_id' => $wecha_id)) -> delete();
            $this -> success('删除成功', U('Shake/show_fens', array('token' => $this -> token, 'id' => $pid)));
        }
    }
}
?>