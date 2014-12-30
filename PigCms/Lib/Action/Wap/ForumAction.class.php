<?php


class ForumAction extends WapAction{


	
	public function _initialize(){
		parent::_initialize();
		session('wapupload',1);
		$token = $this->_request('token');
		$isopen = M('Forum_config')->field('isopen,bgurl')->where("token = '$token'")->find();
		
		if($isopen['isopen'] == 0){
			$this->error('请联系官方开启讨论社区');
		}

		$fans = $this->fans;
		if($fans['portrait'] == ''){
		
			$fans['portrait'] = './tpl/static/forum/images/face.png';
		}
		
		
		
		$this->assign('fans',$fans);

		$this->assign('bgurl',$isopen['bgurl']);
		
	}
	
	//论坛首页
	public function index(){
		$forum = M('Forum_topics');
		$token = $this->_get('token');
		$where = array('status'=>'1','token'=>$token);
		$count      = $forum->where( $where )->count();
        $Page       = new Page($count,10);
        $show       = $Page->show();
		$wecha_id = $this->_get('wecha_id');
		$list = $forum->where( $where )->order('createtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$messageNum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$wecha_id' AND status = 1 AND isread = 1")->count();

		$wxname = M('Wxuser')->where("token = '$token'")->field('wxname')->find();

		foreach($list as $k=>$v){
			$list["$k"]["content"] = htmlspecialchars_decode($v['content']);
			$id = $v['id'];
			$comment = M('Forum_comment')->field('id')->where("tid = $id AND status = 1")->select();
			$list["$k"]["cnum"] = count($comment);
			if($v['photos'] != ''){
				$list["$k"]["photoArr"] = explode(',',$v['photos']);
			}
			$list["$k"]["uinfo"] = $this->uinfo($v['uid'],$token);
		}
		//增加浏览量
		if(!cookie('pv')){
			M('Forum_config')->where("token = '$token'")->setInc('pv');
			cookie('pv','1',24*60*60);
		}

		$config = M('Forum_config')->where("token = '$token'")->find();

		
		$this->assign('messageNum',$messageNum);
		$this->assign('config',$config);
		$this->assign('wxname',$wxname['wxname']);
		$this->assign('show',$show);
		$this->assign('count',$count);
		$this->assign('list',$list);
		
		$this->assign('wecha_id',$wecha_id);
		$this->display();

	
	}
	
	//首页ajax加载
	public function moreList(){
		
		
		$token = $this->_get('token');	
		$forum = M('Forum_topics');
		$where = array('status'=>'1','token'=>$token);
		$count      = $forum->where( $where )->count();
        $Page       = new Page($count,10);
		$wecha_id = $this->_get('wecha_id');

		$pageNum = $this->_post('pageNum','intval');
		$pagetotal = ceil($count/10);
		if($pageNum > $pagetotal){
				exit;
		}
		
		$list2 = $forum->where( $where )->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();

		if(!empty($list2)){
			foreach($list2 as $k=>$v){
				$list2["$k"]["content"] = htmlspecialchars_decode($v['content'],ENT_QUOTES);
				$id = $v['id'];
				$comment = M('Forum_comment')->field('id')->where("tid = $id AND status = 1 AND token = '$token'")->select();
				$v["cnum"] = count($comment);
				if($v['photos'] != ''){
					$v["photoArr"] = explode(',',$v['photos']);
				}
				
				$uinfo = $this->uinfo($v['uid'],$token);

				$tpl .='<article><header><ul class="tbox"><li><a href="#" class="head_img"><img src="'.$uinfo['portrait'].'" onerror="this.src='.'./tpl/static/forum/images/face.png'.';" /></a></li><li><h5>'.$v['uname'].'</h5><p>'.date('Y-m-d H:i:s',$v['createtime']).'</p></li></ul></header><section>';
		
				if($v['photoArr'] != NULL){
						$photonum = count($v['photoArr']);
						$tpl .='<figure data-count="'.$photonum.'张图片"><div>';
										
						for($i=0;$i<$photonum;$i++){
							$tpl .='<img src="'.$v['photoArr'][$i].'" data-src="'.$v['photoArr'][$i].'" data-gid="g7" onload="preViewImg(this, event);"/>';
						}
				$tpl .= '</div></figure>';
						}
				
				$tpl .='<div style="clear:both"></div><div><h5>'.$v['title'].'</h5><div>'.htmlspecialchars_decode($list2["$k"]["content"],ENT_QUOTES).'</div></div><a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$v['id'].'&wecha_id='.$wecha_id.'&token='.$token.'">查看全文</a></section><footer><ul class="box"><li>';
											
											
				if(in_array($wecha_id,explode(',',$v['likeid']))){
										
					$tpl .= '<a href="javascript:;" class="a_collect on" onclick="collectTrends('.$v['id'].',\''.$wecha_id.'\')" ><span class="icons icons_collect" >&nbsp;</span><label>';
											
						}else{
									
					$tpl .= '<a href="javascript:;" class="a_collect" onclick="collectTrends('.$v['id'].',\''.$wecha_id.'\')" ><span class="icons icons_collect" >&nbsp;</span>';
						}

					if($v['likeid'] == NULL){
						$tpl .= '<label>0</label></a>';
												
					}else{
												
						$tpl .= '<label>'.count(explode(',',$v['likeid'])).'</label></a>';
													
					}	
											
										
				$tpl .= '</li><li><a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$v['id'].'&wecha_id='.$wecha_id.'&token='.$token.'" class="a_comment"><span class="icons icons_comment" >&nbsp;</span><label>'.$v['cnum'].'</label></a></li><li>';
										
					if(in_array($wecha_id,explode(',',$v['favourid']))){

						$tpl .= '<a href="javascript:;" class="a_like on" onclick="praiseTrends('.$v['id'].',\''.$wecha_id.'\')"><span class="icons icons_like">&nbsp;</span><label>';
												
					}else{
						$tpl .= '<a href="javascript:;" class="a_like" onclick="praiseTrends('.$v['id'].',\''.$wecha_id.'\')"><span class="icons icons_like">&nbsp;</span>';
					}
		
					if($v['favourid'] == NULL){
											
						$tpl .= '<label>0</label></a>';
												
					}else{
												
						$tpl .= '<label>'.count(explode(',',$v['favourid'])).'</label></a>';
												
					}
											
						$tpl .= '</li></ul></footer></article>';
			}
			
				echo $tpl;
			}else{
				echo 0;
			}
	}
	
	//发表帖子页面
	public function add(){
		
		$uid = $this->_get('wecha_id');
		$token = $this->_get('token');

		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		S("fans_".$token."_".$uid,NULL);
		$fans = $this->fans;
			
		if($fans == NULL){
		
			$this->error('您需要完善个人信息才能进入发帖',U('Userinfo/index',array('token'=>$token,'wecha_id'=>$uid,'redirect'=>'Forum/add|wecha_id:'.$uid)));
		}		
		$this->display();
	
	}
	
	//发布新帖子
	public function checkAdd(){
		$data = array();
		$data['uid'] = $this->_post('wecha_id');
		$token = $this->_post('token');
		if($data['uid'] == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$data['title'] = $this->_post('title');
		$data['content'] = $this->_post('form_article');
		$wecha_id = $data['uid'];
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$wecha_id' AND token = '$token'")->find();
		$data['uname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		$data['token'] = $this->_post('token');
		$data['createtime'] = time();

		

		$conf = M('Forum_config')->field('ischeck')->where("token = '$token'")->find();

		if($conf['ischeck'] == 1){
			$data['status'] = -1;
		}else{
			$data['status'] = 1;
		}

		$photos[] = $_POST['pics1'];
		$photos[] = $_POST['pics2'];
		$photos[] = $_POST['pics3'];
		$photos[] = $_POST['pics4'];
		$photos[] = $_POST['pics5'];
		$photos[] = $_POST['pics6'];
		$photos[] = $_POST['pics7'];
		$photos[] = $_POST['pics8'];

		foreach($photos as $k=>$v){

			if($v == ''){
				unset($photos[$k]);
			}
		}

		$data['photos'] = implode(',',$photos);
		
		//添加记录
		$forum = M('Forum_topics');

			if(session('forumAdd')+60 < time()){
				if($forum->add($data)){
				session('forumAdd',time());
					if($conf['ischeck'] == 1){
						$this->error('等待管理员审核后您的帖子才可以显示',U('Forum/myContent',array('wecha_id'=>$data['uid'],'token'=>$data['token'])));
					}else{
						$this->redirect(U('Forum/index',array('wecha_id'=>$data['uid'],'token'=>$data['token'])));
					}
					
				}else{
					$this->error('发布失败');
				}
			}else{
				$this->error('距上次发表时间小于1分钟');
			}


	}
	
	//喜欢
	public function likeAjax(){
		$uid = $this->_post('uid');
		
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$id = $this->_post('tid','intval');
		
		$info = M('Forum_topics')->field('likeid')->where("id = $id AND status = 1")->find();

		if($info['likeid'] == ''){
		
			$data['likeid'] = $this->_post('uid');
			$boo = M('Forum_topics')->where("id = $id")->setField($data);
			
		}else{
		
			$likeid = explode(',',$info['likeid']);
			if(in_array($this->_post('uid'),$likeid)){
			
				unset($likeid[array_search($this->_post('uid'),$likeid)]);
				$data['likeid'] = implode(',',$likeid);
				$boo = M('Forum_topics')->where("id = $id")->setField($data);
				
			}else{
		
				$data['likeid'] = $info['likeid'].','.$this->_post('uid');
				$boo = M('Forum_topics')->where("id = $id")->setField($data);
			}
		}
		if($boo){
		
			echo 1;
		}else{
			echo 0;
		}

	}
	
	
	//赞
	public function favourAjax(){
		$uid = $this->_post('uid');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$id = $this->_post('tid','intval');
		
		$info = M('Forum_topics')->field('favourid')->where("id = $id")->find();

		if($info['favourid'] == ''){
		
			$data['favourid'] = $this->_post('uid');
			$boo = M('Forum_topics')->where("id = $id")->setField($data);

		}else{
		
			$favourid = explode(',',$info['favourid']);
			if(in_array($this->_post('uid'),$favourid)){
			
				unset($favourid[array_search($this->_post('uid'),$favourid)]);
				$data['favourid'] = implode(',',$favourid);
				$boo = M('Forum_topics')->where("id = $id")->setField($data);

			}else{
			
				$data['favourid'] = $info['favourid'].','.$this->_post('uid');
				$boo = M('Forum_topics')->where("id = $id")->setField($data);
			}
		}

		if($boo){
		
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//帖子和评论详情页面
	public function comment(){
		
		
		$id = $this->_get('tid','intval');
		$token = $this->_get('token');
		$topics = M('Forum_topics')->where("id = $id AND status = 1 AND token = '$token'")->find();
		$topics['content'] = htmlspecialchars_decode($topics['content']);
		$wecha_id = $this->_get('wecha_id');

		//load comment
		
		$comment = M('Forum_comment')->order('createtime DESC')->where("tid = $id AND status = 1")->select();
		$cnum = count($comment);
		foreach($comment as $k=>$v){
			$comment["$k"]["content"] = htmlspecialchars_decode($v['content']);
			$comment["$k"]["uinfo"] = $this->uinfo($v['uid'],$token);
			if($v['replyid'] != NULL){
				$reuid = $v['replyid'];
				$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$reuid' AND token = '$token'")->find();
				$comment[$k]['reuname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
				
			}
		}

		$this->assign('wecha_id',$wecha_id);
		$this->assign('cnum',$cnum);
		$this->assign('comment',$comment);
		$this->assign('topics',$topics);
		$this->display();
	
	}

	//评论提交处理
	public function checkCommentAdd(){
		
		$data['uid'] = $this->_post('wecha_id');
		
		if($data['uid'] == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$token = $this->_post('token');
		$data['tid'] = $this->_post('tid','intval');
		
		$wecha_id = $data['uid'];
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$wecha_id' AND token = '$token'")->find();
		$data['uname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		
		$data['content'] = $this->_post('form_article');
		$data['token'] = $this->_post('token');
		
		$data['createtime'] = time();
		
		$conf = M('Forum_config')->field('comcheck')->where("token = '$token'")->find();
		if($conf['comcheck'] == 1){
			$data['status'] = -1;
		}else{
			$data['status'] = 1;
		}
		
		
		$comment = M('Forum_comment');
		


			if($comment->add($data)){
				$tid = $data['tid'];
				$token = $data['token'];
				$uid = M('Forum_topics')->where("token = '$token' AND id = $tid AND status = 1")->field('uid')->find();
				if($conf['comcheck'] == 1){
					$message['content'] = '<a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$data['tid'].'&wecha_id='.$uid['uid'].'&token='.$data['token'].'">'.$data['uname'].'评论了您的帖子,该评论需要等待管理员审核后才能显示</a>';
				}else{
					$message['content'] = '<a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$data['tid'].'&wecha_id='.$uid['uid'].'&token='.$data['token'].'">'.$data['uname'].'评论了您的帖子</a>';
				}
				
				$message['createtime'] = time();
				$message['fromuid'] = $data['uid'];
				$message['token'] = $data['token'];
				$message['touid'] = $uid['uid'];
				$message['tid'] = $data['tid'];
				$message['cid'] = NULL;
				$message['fromuname'] = $data['uname'];
				
			$touid = $uid['uid'];
			$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$touid' AND token = '$token'")->find();
			$message['touname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';

				
				M('Forum_message')->add($message);
				
				if($conf['comcheck'] == 1){
					$this->error('等待管理员审核后您的评论才可以显示',U('Forum/comment',array('wecha_id'=>$data['uid'],'tid'=>$tid,'token'=>$data['token'])));
				}else{
					$this->redirect(U("Forum/comment",array('tid'=>$data['tid'],'wecha_id'=>$data['uid'],'token'=>$data['token'])));
				}
				
				
			}else{
				$this->error('评论失败');
			}


	
	}
	
	//赞评论
	public function commentFavourAjax(){
		$uid = $this->_post('uid');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$id = $this->_post('id','intval');
		
		
		$comment = M('Forum_comment');

		$fav = $comment->field('favourid')->where("id = $id")->find();
		
		if($fav['favourid'] == NULL){
			
			
			$boo = $comment->where("id = $id")->setField(array('favourid'=>$uid));
			
		}else{
			
			$favArray = explode(',',$fav['favourid']);
			if(in_array($uid,$favArray)){
				
				unset($favArray[array_search($uid,$favArray)]);
				$res['favourid'] = implode(',',$favArray);
				$boo = $comment->where("id = $id")->setField($res);
				
			}else{
				$boo = $comment->where("id = $id")->setField(array('favourid'=>$fav['favourid'].','.$uid));
			}
		}

		if($boo){
			echo 1;
		}else{
			echo 0;
		}
	
	}
	
	
	//回复评论页面
	public function recomment(){
		$uid = $this->_get('wecha_id');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$uid = $this->_get('reid');
		
		$data = M('Forum_comment')->where("uid = '$uid'")->field('uname')->find();
		$uname = $data['uname'];

		
		$this->assign('uname',$uname);
		$this->display();
	}
	

	//回复评论提交处理
	public function checkRecomment(){
		$data['uid'] = $this->_post('wecha_id');
		if($data['uid'] == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$data['tid'] = $this->_post('tid','intval');
		$data['replyid'] = $this->_post('reid');
		$data['token'] = $this->_post('token');
		$token = $data['token'];
		$wecha_id = $data['uid'];
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$wecha_id'  AND token = '$token'")->find();
		$data['uname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		
		
		$data['content'] = $this->_post('form_article');
		$data['createtime'] = time();
		
		$conf = M('Forum_config')->field('comcheck')->where("token = '$token'")->find();
		if($conf['comcheck'] == 1){
			$data['status'] = -1;
		}else{
			$data['status'] = 1;
		}
		
		$comment = M('Forum_comment');
		if($comment->create()){
		
			if($comment->add($data)){
				if($conf['comcheck'] == 1){
					$message['content'] = '<a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$data['tid'].'&wecha_id='.$data['replyid'].'&token='.$data['token'].'">'.$data['uname'].'回复了您的评论，该评论在管理员审核后才能显示</a>';
				}else{
					$message['content'] = '<a href="/index.php?g=Wap&m=Forum&a=comment&tid='.$data['tid'].'&wecha_id='.$data['replyid'].'&token='.$data['token'].'">'.$data['uname'].'回复了您的评论。</a>';
				}
				
				$message['createtime'] = time();
				$message['fromuid'] = $data['uid'];
				$message['token'] = $data['token'];
				$message['touid'] = $data['replyid'];
				$message['tid'] = $data['tid'];
				$message['cid'] = $this->_post('cid','intval');
				$message['fromuname'] = $data['uname'];
				
				$touid = $message['touid'];
				$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$touid' AND token = '$token'")->find();
				$message['touname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
				

				M('Forum_message')->add($message);
				if($conf['comcheck'] == 1){
					$this->error('等待管理员审核后您的评论才可以显示',U('Forum/comment',array('wecha_id'=>$data['uid'],'tid'=>$data['tid'],'token'=>$data['token'])));
				}else{
					$this->redirect(U("Forum/comment",array('tid'=>$data['tid'],'wecha_id'=>$data['uid'],'token'=>$data['token'])));
				}

					
			}
		}else{
			$this->error('系统错误');
		}
		
		
	}
 
	//我发表的帖子页面
	public function myContent(){
		
		$uid = $this->_get('wecha_id');
		
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		$token = $this->_get('token');

		
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$uid' AND token = '$token'")->find();
		$uname = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
 
		$list = M('Forum_topics')->order('createtime DESC')->where("uid = '$uid' AND status != 0 AND token = '$token'")->select();

		$mylikenum = M('Forum_topics')->field('id')->order('createtime DESC')->where("status = 1 AND token = '$token' AND likeid like '%$uid%'")->count();
		$mymessagenum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$uid' AND status = 1")->count();
		$messageNum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$uid' AND status = 1 AND isread = 1")->count();
		foreach($list as $k=>$v){
			$list["$k"]["content"] = htmlspecialchars_decode($v['content']);
			$id = $v['id'];
			$comment = M('Forum_comment')->field('id')->where("tid = $id AND status = 1 AND token = '$token'")->select();
			$list["$k"]["cnum"] = count($comment);
		}
		
		$this->assign('mymessagenum',$mymessagenum); 
		$this->assign('messageNum',$messageNum); 
		$this->assign('mylikenum',$mylikenum); 
		$this->assign('uname',$uname); 
		$this->assign('list',$list); 
		$this->display();
	}
	//其他用户页面
	public function otherUser(){
		$wecha_id = $this->_get('wecha_id');	
		
		if($wecha_id == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		$uid = $this->_get('uid');
		$token = $this->_get('token');

		
		$userinfo = M('Userinfo')->field('wechaname,portrait')->where("wecha_id = '$uid' AND token = '$token'")->find();
		$uname = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		if($userinfo['portrait'] == ''){
			$portrait = './tpl/static/forum/images/face.png';
		}else{
			$portrait = $userinfo['portrait'];
		}
		$list = M('Forum_topics')->order('createtime DESC')->where("uid = '$uid' AND status = 1 AND token = '$token'")->select();


		$messageNum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$wecha_id' AND status = 1 AND isread = 1")->count();
		foreach($list as $k=>$v){
			$list["$k"]["content"] = htmlspecialchars_decode($v['content']);
			$id = $v['id'];
			$comment = M('Forum_comment')->field('id')->where("tid = $id AND status = 1 AND token = '$token'")->select();
			$list["$k"]["cnum"] = count($comment);
		}
		

		$this->assign('messageNum',$messageNum); 
		
		$this->assign('wecha_id',$wecha_id);
		$this->assign('uname',$uname); 
		$this->assign('portrait',$portrait); 
		$this->assign('list',$list); 
		$this->display();
	}
	
	//我喜欢过的帖子页面
	public function myLike(){
		
		$uid = $this->_get('wecha_id');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		$token = $this->_get('token');
		$list = M('Forum_topics')->order('createtime DESC')->where("status = 1 AND token = '$token' AND likeid like '%$uid%'")->select();
		
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$uid' AND token = '$token'")->find();
		$uname = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		
		$mytopicsnum = M('Forum_topics')->field('id')->order('createtime DESC')->where("uid = '$uid' AND status = 1 AND token = '$token'")->count();
		$mymessagenum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$uid' AND status = 1")->count();
		$messageNum = M('Forum_message')->field('id')->where("token = '$token' AND touid = '$uid' AND status = 1 AND isread = 1")->count();
		foreach($list as $k=>$v){
			$list["$k"]["content"] = htmlspecialchars_decode($v['content']);
			$id = $v['id'];
			$comment = M('Forum_comment')->field('id')->where("tid = $id AND status = 1")->select();
			$list["$k"]["cnum"] = count($comment);
			$list["$k"]["uinfo"] = $this->uinfo($v['uid'],$token);
		}
		$wecha_id = $this->_get('wecha_id');
		$this->assign('mytopicsnum',$mytopicsnum);
		$this->assign('mymessagenum',$mymessagenum);
		$this->assign('messageNum',$messageNum);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('list',$list); 
		$this->assign('uname',$uname); 
		$this->display();	
		
	}
	
	//我的消息页面
	public function myMessage(){
		
		$uid = $this->_get('wecha_id');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		$token = $this->_get('token');
		
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$uid' AND token = '$token'")->find();
		$uname = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		
		$list = M('Forum_message')->order('createtime DESC')->where("touid = '$uid' AND token = '$token' AND status = 1")->select();
		
		foreach($list as $k=>$v){
		
			$list["$k"]['uinfo'] = $this->uinfo($v['fromuid'],$token);
		}
		
		$mylikenum = M('Forum_topics')->field('id')->order('createtime DESC')->where("status = 1 AND token = '$token' AND likeid like '%$uid%'")->count();
		$mytopicsnum = M('Forum_topics')->field('id')->order('createtime DESC')->where("uid = '$uid' AND status = 1 AND token = '$token'")->count();

		M('Forum_message')->where("token = '$token' AND touid = '$uid' AND status = 1 AND isread = 1")->setField('isread',0);
		
		$this->assign('list',$list);

		$this->assign('mylikenum',$mylikenum);
		$this->assign('uname',$uname);
		$this->assign('mytopicsnum',$mytopicsnum);
		$this->display();
	
	
	
	}

	//编辑我的帖子页面
	public function myContentEdit(){
		$wecha_id = $this->_get('wecha_id');	
		if($wecha_id == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$tid = $this->_get('tid','intval');
		$wecha_id = $this->_get('wecha_id');
		$token = $this->_get('token');
		$data = M('Forum_topics')->where("id = $tid AND token = '$token' AND uid = '$wecha_id'")->find();
		$data['photoArr'] = explode(',',$data['photos']);
		$data['content'] = htmlspecialchars_decode($data['content']);

		$this->assign('data',$data);
		$this->display();
	}
	
	
	//更新我的帖子提交处理
	public function myContentUpdate(){
		
		
		$data = array();
		$data['uid'] = $this->_post('wecha_id');
		if($data['uid'] == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		$topics = M('Forum_topics');
		$data['title'] = $this->_post('title');
		$data['content'] = $this->_post('form_article');
		$data['token'] = $this->_post('token');
		$token = $data['token'];
		$wecha_id = $data['uid'];
		$userinfo = M('Userinfo')->field('wechaname')->where("wecha_id = '$wecha_id' AND token = '$token'")->find();
		$data['uname'] = $userinfo['wechaname'] ? $userinfo['wechaname'] : '游客';
		

		
		$data['updatetime'] = time();
		
		

		$tid = $this->_post('tid','intval');
		
		$tinfo = $topics->field('photos')->where("token = '$token' AND uid = '$wecha_id' AND status = 1 AND id = $tid")->find();

		$photos[] = $_POST['pics1'];
		$photos[] = $_POST['pics2'];
		$photos[] = $_POST['pics3'];
		$photos[] = $_POST['pics4'];
		$photos[] = $_POST['pics5'];
		$photos[] = $_POST['pics6'];
		$photos[] = $_POST['pics7'];
		$photos[] = $_POST['pics8'];
		

		foreach($photos as $k=>$v){
		
			if($v == ''){
				unset($photos[$k]);
			}
		}
		

		$data['photos'] = implode(',',$photos);

		if($topics->create()){
		
			if($topics->where("id = $tid AND token = '$token' AND uid = '$wecha_id' AND status = 1")->setField($data)){
				$this->redirect(U("Forum/myContent",array('wecha_id'=>$data['uid'],'token'=>$data['token'])));
			}
		}else{
			$this->error('系统错误');
		}
		
	}
	
	//删除帖子
	public function delTopics(){
		$uid = $this->_post('wecha_id');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		$id = $this->_post('tid','intval');
		
		$token = $this->_post('token');
		
		if(M('Forum_topics')->where("id = $id AND token = '$token' AND uid = '$uid' AND status = 1")->setField('status',0)){
				echo 1;
		}else{	
				echo 0;
		}
	}
	
	//删除评论
	public function delComment(){
		$uid = $this->_get('wecha_id');
		if($uid == ''){
			$this->error('您需要从此社区的公众号聊天窗口进入才能访问');
		}
		
		
		$cid = $this->_post('cid','intval');
		$token = $this->_get('token');
		
		
		if(M('Forum_comment')->where("id = $cid AND token = '$token' AND uid = '$uid' AND status = 1")->setField('status',0)){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//获取头像
	
	protected function uinfo($wid='',$to=''){
	
		$uinfo = M('Userinfo')->field('portrait,wechaname')->where("wecha_id = '$wid' AND token = '$to'")->find();
		if($uinfo['portrait'] == ''){
			$uinfo['portrait'] = './tpl/static/forum/images/face.png';
		}
		return $uinfo;
	}


	
	

}