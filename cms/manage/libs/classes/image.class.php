<?php
define("OP_TO_FILE", 1);              // Output to file
define("OP_OUTPUT", 2);               // Output to browser
define("OP_NOT_KEEP_SCALE", 4);       // Free scale
define("OP_BEST_RESIZE_WIDTH", 128);    // Scale to width
define("OP_BEST_RESIZE_HEIGHT", 128);  // Scale to height

define("CM_DEFAULT",0);               // Clipping method: default
define("CM_LEFT_OR_TOP",1);           // Clipping method: left or top
define("CM_MIDDLE",2);                // Clipping method: middle
define("CM_RIGHT_OR_BOTTOM",3);       // Clipping method: right or bottom

class image
{
	public $sourcePath; //sourece of picture
	public $thumbPath; //path of thumb
	public $toFile = true; //creat file or not
	public $maxWidth = 500; //the maxuim width of image
	public $maxHeight = 600; //the maxuim height of image
	function __construct($sourcePath='', $thumbPath='')
	{
		$this->sourcePath = $sourcePath;
		$this->thumbPath=$thumbPath;
	}
	function Image($sourcePath, $thumbPath)
	{
		$this->sourcePath = $sourcePath;
		$this->thumbPath=$thumbPath;
	}
	function makeThumb($sourFile,$width=128,$height=128)
	{
		$imageInfo = $this->getInfo($sourFile);
		$sourFile = $this->sourcePath . $sourFile;
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . "_thumb.jpg";
		switch ($imageInfo["type"])
		{
			case 1: //gif
			$img = imagecreatefromgif($sourFile);
			break;
			case 2: //jpg
			$img = imagecreatefromjpeg($sourFile);
			break;
			case 3: //png
			$img = imagecreatefrompng($sourFile);
			break;
			default:
			return 0;
			break;
		}
		if (!$img)
		return 0;
		$width = ($width > $imageInfo["width"]) ? $imageInfo["width"] : $width;
		$height = ($height > $imageInfo["height"]) ? $imageInfo["height"] : $height;
		$srcW = $imageInfo["width"];
		$srcH = $imageInfo["height"];
		if ($srcW * $width > $srcH * $height)
		$height = round($srcH * $width / $srcW);
		else
		$width = round($srcW * $height / $srcH);
		//*
		if (function_exists("imagecreatetruecolor")) //GD2.0.1
		{
			$new = imagecreatetruecolor($width, $height);
			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		else
		{
			$new = imagecreate($width, $height);
			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		//*/
		if ($this->toFile)
		{
			if (file_exists($this->thumbPath . $newName))
			unlink($this->thumbPath . $newName);
			imagejpeg($new, $this->thumbPath . $newName,100);
			return $this->thumbPath . $newName;
		}
		else
		{
			imagejpeg($new);
		}
		imagedestroy($new);
		imagedestroy($img);
	}
	function getInfo($file)
	{
		$file = $this->sourcePath . $file;
		$data = getimagesize($file);
		$imageInfo["width"] = $data[0];
		$imageInfo["height"]= $data[1];
		$imageInfo["type"] = $data[2];
		$imageInfo["name"] = basename($file);
		return $imageInfo;
	}
	//
	public function zfResize($srcFile, $dstFile, $dstW, $dstH, $option=1, $cutmode=0, $startX=0, $startY=0, $scale=0) {
		$img_type = array(1=>"gif", 2=>"jpeg", 3=>"png");
		$type_idx = array("gif"=>1, "jpg"=>2, "jpeg"=>2, "jpe"=>2, "png"=>3);

		if (!file_exists($srcFile)) {
			return array(-1, "Source file not exists: $srcFile.");
		}

		$path_parts = @pathinfo($dstFile);
		$ext = strtolower ($path_parts["extension"]);
	
		if ($ext == "") {
			return array(-5, "Can't detect output image's type.");
		}
	
		$func_output = "image" . $img_type[$type_idx[$ext]];
	
		if (!function_exists ($func_output)) {
			return array(-2, "Function not exists for output$func_output.");
		}
	
		$data = @GetImageSize($srcFile);
		$func_create = "imagecreatefrom" . $img_type[$data[2]];
	
		if (!function_exists ($func_create)) {
			return array(-3, "Function not exists for create$func_create.");
		}
	
		$im = @$func_create($srcFile);
	
		$srcW = @imagesx($im);
		$srcH = @ImageSY($im);
		$srcX = 0;
		$srcY = 0;
		$dstX = 0;
		$dstY = 0;
	
		if ($option & OP_BEST_RESIZE_WIDTH) {
			$dstH = round($dstW * $srcH / $srcW);
		}
	
		if ($option & OP_BEST_RESIZE_HEIGHT) {
			$dstW = round($dstH * $srcW / $srcH);
		}
		
		if ($scale){
			if ($srcW>$srcH){
				$dstH = round($dstW * $srcH / $srcW);
			}else {
				$dstW = round($dstH * $srcW / $srcH);
			}
		}
	
		$fdstW = $dstW;
		$fdstH = $dstH;
	
		if ($cutmode != CM_DEFAULT) { // clipping method 1: left or top 2: middle 3: right or bottom
	
			$srcW -= $startX;
			$srcH -= $startY;
	
			if ($srcW*$dstH > $srcH*$dstW) { 
				$testW = round($dstW * $srcH / $dstH);
				$testH = $srcH;
			} else {
				$testH = round($dstH * $srcW / $dstW);
				$testW = $srcW;
			}
			
			switch ($cutmode) {
				case CM_LEFT_OR_TOP: $srcX = 0; $srcY = 0; break;
				case CM_MIDDLE: $srcX = round(($srcW - $testW) / 2);
								$srcY = round(($srcH - $testH) / 2); break;
				case CM_RIGHT_OR_BOTTOM: $srcX = $srcW - $testW;
										 $srcY = $srcH - $testH;
			}
	
			$srcW = $testW;
			$srcH = $testH;
			$srcX += $startX;
			$srcY += $startY;
	
		} else {
			if (!($option & OP_NOT_KEEP_SCALE)) {
				if ($srcW*$dstH>$srcH*$dstW) { 
					$fdstH=round($srcH*$dstW/$srcW); 
					$dstY=floor(($dstH-$fdstH)/2); 
					$fdstW=$dstW;
				} else { 
					$fdstW=round($srcW*$dstH/$srcH); 
					$dstX=floor(($dstW-$fdstW)/2); 
					$fdstH=$dstH;
				}
	
				$dstX=($dstX<0)?0:$dstX;
				$dstY=($dstX<0)?0:$dstY;
				$dstX=($dstX>($dstW/2))?floor($dstW/2):$dstX;
				$dstY=($dstY>($dstH/2))?floor($dstH/s):$dstY;
	
			}
		}
	
		if( function_exists("imagecopyresampled") and 
			function_exists("imagecreatetruecolor") ){
			$func_create = "imagecreatetruecolor";
			$func_resize = "imagecopyresampled";
		} else {
			$func_create = "imagecreate";
			$func_resize = "imagecopyresized";
		}
	
		$newim = @$func_create($dstW,$dstH);
		$black = @ImageColorAllocate($newim, 0,0,0);
		$back = @imagecolortransparent($newim, $black);
		@imagefilledrectangle($newim,0,0,$dstW,$dstH,$black);
		@$func_resize($newim,$im,$dstX,$dstY,$srcX,$srcY,$fdstW,$fdstH,$srcW,$srcH);

		if ($option & OP_TO_FILE) {
			switch ($type_idx[$ext]) {
				case 1:
				case 3:
					@$func_output($newim,$dstFile,100);
					break;
				case 2:
					@$func_output($newim,$dstFile,100);
					break;
			}
		}
	
		if ($option & OP_OUTPUT) {
			if (function_exists("headers_sent")) {
				if (headers_sent()) {
					return array(-4, "HTTP already sent, can't output image to browser.");
				}
			}
			header("Content-type: image/" . $img_type[$type_idx[$ext]]);
			@$func_output($newim);
		}
	
		@imagedestroy($im);
		@imagedestroy($newim);
	
		return array(0, "OK");
	}
	public function generalConfirmCode($code='',$name='vc') {
		$str=strtoupper($code);
		// create the image
		$fn = ABS_PATH . '/images/wbg' . rand(1,3) . '.png';
		$im = imagecreatefrompng($fn);

		// create some colors
		$fg = imagecolorallocate($im, 240, 240, 230);
		$bg = imagecolorallocate($im, 120, 140, 190);
		$bbg = imagecolorallocate($im, 20, 40, 40);

		$fonts = array(0 => 'zt', 1 => 'cgn', 2 => 'carbon');
		$font = ABS_PATH . '/fonts/zt.ttf';

		// add some shadow to the text
		$x = rand(10, 60);

		// add the text
		imagettftext($im, 14, 0, 10, 24, $bg, $font, $str);
		imagettftext($im, 14, 0, 11, 23, $bg, $font, $str);
		imagettftext($im, 14, 0, 9, 21, $bg, $font, $str);
		imagettftext($im, 14, 0, 8, 20, $bbg, $font, $str);
		imagettftext($im, 14, 0, 10, 22, $fg, $font, $str);
		
		imagepng($im, ABS_PATH . '/images/' . $name . '.png');
	}
}
?>
