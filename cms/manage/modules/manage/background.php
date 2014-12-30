<?php
bpBase::loadAppClass('manage','manage',0);
class background extends manage {
	public $accessCityIDs;
	public $accessCities;
	public $accessAllCities;
	function __construct() {
		parent::__construct();
	}
	public function home() {
		if (!$this->site){
			header('Location:?m=config&c=config&a=site');
		}
		if (!$this->site['template']){
			header('Location:?m=template&c=m_template&a=selectTemplate');
		}
		//
		include $this->showManageTpl('home');
	}
	public function logout(){
		session_unset();
		header('Location:login.php');
	}
	public function frameTop(){
		//user
		$userObj=bpBase::loadAppClass('userObj','user',1);
		$thisUser=$userObj->getUserByUID($_SESSION['autoAdminUid']);
		//menu
		$menuObj=bpBase::loadAppClass('menu','manage',1);
		
		$menu=$menuObj->menus();

		include $this->showManageTpl('frameTop');
	}
	public function frameLeft(){
		$menuType=isset($_GET['type'])?$_GET['type']:'home';
		//menu
		if (!defined('SYSTEM_NAME')){
			$menuObj=bpBase::loadAppClass('menu','manage',1);
		}else {
			$menuObj=bpBase::loadAppClass('menu_'.SYSTEM_NAME,'manage',1);
		}
		$menu=$menuObj->menus;
		$subMenuFuncName='submenu_'.$menuType;
		$subMenu=$menuObj->$subMenuFuncName();
		include $this->showManageTpl('frameLeft');
	}
	public function picUpload(){
		include $this->showManageTpl('picUpload');
	}
	public function upyunPicUpload(){
		include $this->showManageTpl('upyunPicUpload');
	}
	public function flashUpload(){
		include $this->showManageTpl('flashUpload');
	}
	public function action_flashUpload(){
		$rt=0;
		$filePath='';
		if (isset($_FILES['filePath'])){
			$flash=$_FILES['filePath'];
			if ($flash['type']!='application/x-shockwave-flash'&&$flash['type']!='application/octet-stream'&&$flash['type']!='video/x-flv'){
				echo '您上传的不是flash:'.$flash['type'];
			}elseif ($flash['size']>50000000) {
				echo '您上传的文件不能超过50M';
			}else {
				$filename=$flash['name'];
				$nameInfos=explode('.',$filename);
				$nameInfosCount=count($nameInfos);
				$subfix=$nameInfos[$nameInfosCount-1];
				$time=SYS_TIME;
				$rand=randStr(4);
				$year=date('Y',$time);
				$month=date('m',$time);
				$day=date('d',$time);
				$pathInfo=upFileFolders($time);
				$dstFolder=$pathInfo['path'];
				$abspath=$dstFolder.$time.$rand.'.'.$subfix;
				$location=MAIN_URL_ROOT.'/upload/images/'.$year.'/'.$month.'/'.$day.'/'.$time.$rand.'.'.$subfix;
				move_uploaded_file($flash['tmp_name'],$abspath);
				//delete the temporary file
				echo $location;
			}
		}else {
			echo '您上传的不是flash';
		}
	}
	public function action_picUpload(){
		$error=0;
		if (isset($_FILES['thumb'])){
			$photo=$_FILES['thumb'];
			if(substr($photo['type'], 0, 5) == 'image') {
				switch ($photo['type']) {
					case 'image/jpeg':
					case 'image/jpg':
					case 'image/pjpeg':
						$ext = '.jpg';
						break;
					case 'image/gif':
						$ext = '.gif';
						break;
					case 'image/png':
					case 'image/x-png':
						$ext = '.png';
						break;
					default:
						$error=-1;
						break;
				}
				if($error==0){
					$time=SYS_TIME;
					$year=date('Y',$time);
					$month=date('m',$time);
					$day=date('d',$time);
					$pathInfo=upFileFolders($time);
					$dstFolder=$pathInfo['path'];
					$dstFile=ABS_PATH.'upload'.DIRECTORY_SEPARATOR.'temp'.$ext;
					//the size of file uploaded must under 1M
					if($photo['size']>2000000){
						$error=-2;
						return $error;
					}
				}else {
					return $error;
				}
				//if no error
				if($error==0){
					$rand=randStr(4);
					
					//delete primary files
					
					if(file_exists($dstFolder.$time.$rand.$ext)){
						unlink($dstFolder.$time.$rand.$ext);
					}
					if ($ext!='.gif'&&$ext!='.png'){
						//save the temporary file 
						move_uploaded_file($photo['tmp_name'],$dstFile);
						$imgInfo=getimagesize($dstFile);
						//generate new files
						$imageWidth=intval($_POST['width'])!=0?intval($_POST['width']):$imgInfo[0];
						$imageHeight=intval($_POST['height'])!=0?intval($_POST['height']):$imgInfo[1];
						bpBase::loadSysClass('image');
						image::zfResize($dstFile,$dstFolder.$time.$rand.'.jpg',$imageWidth,$imageHeight,1|4,2);
						$ext='.jpg';
						//
					}else {
						move_uploaded_file($photo['tmp_name'],$dstFolder.$time.$rand.$ext);
					}
					if (isset($_POST['channelid'])){//内容缩略图
						$channelObj=bpBase::loadAppClass('channelObj','channel');
						$thisChannel=$channelObj->getChannelByID($_POST['channelid']);
						$articleObj=bpBase::loadAppClass('articleObj','article');
						$articleObj->setOtherThumb($thisChannel,$dstFile,$dstFolder,$time.$rand,'jpg');
					}
					if ($ext!='.gif'&&$ext!='.png'){
						@unlink($dstFile);
					}
					$location='http://'.$_SERVER['HTTP_HOST'].CMS_DIR_PATH.'/upload/images/'.$year.'/'.$month.'/'.$day.'/'.$time.$rand.$ext;
					$error=0;
				}
			}else {
				$error=-1;
			}
		}else {
			$error=-1;
		}
		
		if ($error==0){
			
			echo $location;
		}else {
			$errors=array(-1=>'你上传的不是图片',-2=>'文件不能超过2M',-3=>'图片地址不正确');
			echo $errors[intval($error)];
		}
	}
}
?>