<?php
bpBase::loadAppClass('front','front',0);
class widget extends front {
	function __construct() {
		parent::__construct();

	}
	public function picUpload(){
		$this->display();
	}
	public function action_picUpload(){
		if (!isset($_SESSION['canupload'])){
			exit();
		}
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
					if ($ext!='.gif'){
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
						move_uploaded_file($photo['tmp_name'],$dstFolder.$time.$rand.'.gif');
					}
					if (isset($_POST['channelid'])){//内容缩略图
						$channelObj=bpBase::loadAppClass('channelObj','channel');
						$thisChannel=$channelObj->getChannelByID($_POST['channelid']);
						$articleObj=bpBase::loadAppClass('articleObj','article');
						$articleObj->setOtherThumb($thisChannel,$dstFile,$dstFolder,$time.$rand,'jpg');
					}
					if ($ext!='.gif'){
						@unlink($dstFile);
					}
					$location=MAIN_URL_ROOT.'/upload/images/'.$year.'/'.$month.'/'.$day.'/'.$time.$rand.$ext;
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