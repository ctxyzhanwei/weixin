<?php
bpBase::loadAppClass('manage','manage',0);
class article extends manage {
	function __construct() {
		$this->article_db = bpBase::loadModel('article_model');
		//$this->user_db = bpBase::loadModel('user_model');
	}
	
	public function picUpload(){
		$result = array();
		if (count($_POST)) {
			$result['post'] = $_POST;
		}
		if (count($_FILES)) {
			$result['files'] = $_FILES;
		}
		// Validation
		$error = false;
		if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
			$error = 'Invalid Upload';
			exit();
		}
		// Processing start
		$photo=$_FILES['Filedata'];
		$time=SYS_TIME;
		$year=date('Y',$time);
		$month=date('m',$time);
		$day=date('d',$time);
		$pathInfo=upFileFolders($time);
		$dstFolder=$pathInfo['path'];
		$rand=randStr(4);
		$dstFile=$dstFolder.$time.$rand.$photo['name'];
		//the size of file uploaded must under 1M
		if($photo['size']>3000000){
			$error = '图片太大不能超过3M';
			exit();
		}

		//save the temporary file
		@move_uploaded_file($photo['tmp_name'],$dstFile);
		//
		//自动缩放
		$imgInfo = @getimagesize($dstFile);
		$maxPicWidth=intval(loadConfig('cmsContent','maxPicWidth'));
		$maxPicWidth=$maxPicWidth<1?500:$maxPicWidth;
		if ($imgInfo[0]>$maxPicWidth){
			$newWidth=$maxPicWidth;
			$newHeight=$imgInfo[1]*$newWidth/$imgInfo[0];
		}else {
			$newWidth=$imgInfo[0];
			$newHeight=$imgInfo[1];
		}
		bpBase::loadSysClass('image');
		bpBase::loadSysClass('watermark');
		image::zfResize($dstFile,$dstFolder.$time.$rand.'.jpg',$newWidth,$newHeight,1,2,0,0,1);
		//delete the temporary file
		@unlink($dstFile);

		$location=CMS_DIR_PATH.$pathInfo['url'].$time.$rand.'.jpg';
		//
		bpBase::loadSysClass('image');
		bpBase::loadSysClass('watermark');
		$wm=new watermark();
		$wm->wm($dstFolder.$time.$rand.'.jpg');
		//
		$filePath=$location;
		//processing end
		if ($error) {
			$return = array(
			'status' => '0',
			'error' => $error
			);

		} else {
			$return = array(
			'status' => '1',
			'name' => ABS_PATH.$filePath
			);
			// Our processing, we get a hash value from the file
			$return['hash'] = '';
			// ... and if available, we get image data
			if ($imgInfo) {
				$return['width'] = $newWidth;
				$return['height'] = $newHeight;
				$return['mime'] = $imgInfo['mime'];
				$return['url'] = $filePath;
				$return['randnum'] = rand(0,999999);
			}
		}
		// Output
		if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
			// header('Content-type: text/xml');

			// Really dirty, use DOM and CDATA section!
			echo '<response>';
			foreach ($return as $key => $value) {
				echo "<$key><![CDATA[$value]]></$key>";
			}
			echo '</response>';
		} else {
			// header('Content-type: application/json');
			echo json_encode($return);
		}
	}
	
}
?>