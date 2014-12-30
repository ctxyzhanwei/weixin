<?php
class watermark
{
	public $watermarkConfig;
	function __construct() {
		$this->watermarkConfig=loadConfig('watermark');
		$this->watermarkConfig['leftTopWaterMarkText']=$this->watermarkConfig['leftTopWaterMarkText']?$this->watermarkConfig['leftTopWaterMarkText']:$this->watermarkConfig['waterMarkText'];

		$this->watermarkConfig['leftDistance']=isset($this->watermarkConfig['leftDistance'])?intval($this->watermarkConfig['leftDistance']):9;
		$this->watermarkConfig['topDistance']=isset($this->watermarkConfig['topDistance'])?intval($this->watermarkConfig['topDistance']):16;
		$this->watermarkConfig['rightDistance']=isset($this->watermarkConfig['rightDistance'])?intval($this->watermarkConfig['rightDistance']):5;
		$this->watermarkConfig['bottomDistance']=isset($this->watermarkConfig['bottomDistance'])?intval($this->watermarkConfig['bottomDistance']):10;
	}
	function wm($sFilePath)
    {
    	
    	$watermarkConfig=$this->watermarkConfig;
       /***自己写的start***/
    	if ($watermarkConfig['useWaterMark']){
    		$sourceImageAttr = @getimagesize($sFilePath);
    		if ((!$watermarkConfig['picMinWidth'] || ($watermarkConfig['picMinWidth']&&$sourceImageAttr[0]>$watermarkConfig['picMinWidth'])) && (!$watermarkConfig['picMinHeight'] || ($watermarkConfig['picMinHeight']&&$sourceImageAttr[1]>$watermarkConfig['picMinHeight']))){

    			if ($sourceImageAttr === false) {
    				return false;
    			}
    			$extensions=explode('.',$sFilePath);
    			$extensionsCount=count($extensions);
    			if ($extensions[$extensionsCount-1]=='gif'){
    				return false;
    			}
    			if ($watermarkConfig['waterMarkType']=='text'){
    				self::createTextWatermark($sFilePath,$watermarkConfig['waterMarkText']);
    			}else {
    				self::createWatermark($sFilePath, ABS_PATH.'/editor/ckfinder/plugins/watermark/logo.png', $this->watermarkConfig['rightDistance'],$this->watermarkConfig['bottomDistance'], 100, 100);
    			}
    		}
    	}
         /***自己写的end***/
        return true;
    }
    function createWatermark($sourceFile, $watermarkFile, $marginLeft = 5, $marginBottom = 5, $quality = 90, $transparency = 100)
    {
        if (!file_exists($watermarkFile)) {
            $watermarkFile = dirname(__FILE__) . "/" . $watermarkFile;
        }
        if (!file_exists($watermarkFile)) {
            return false;
        }

        $watermarkImageAttr = @getimagesize($watermarkFile);
        $sourceImageAttr = @getimagesize($sourceFile);
        if ($sourceImageAttr === false || $watermarkImageAttr === false) {
            return false;
        }

        switch ($watermarkImageAttr['mime'])
        {
            case 'image/gif':
                {
                    if (@imagetypes() & IMG_GIF) {
                        $oWatermarkImage = @imagecreatefromgif($watermarkFile);
                    } else {
                        $ermsg = 'GIF images are not supported';
                    }
                }
                break;
            case 'image/jpeg':
                {
                    if (@imagetypes() & IMG_JPG) {
                        $oWatermarkImage = @imagecreatefromjpeg($watermarkFile) ;
                    } else {
                        $ermsg = 'JPEG images are not supported';
                    }
                }
                break;
            case 'image/png':
                {
                    if (@imagetypes() & IMG_PNG) {
                        $oWatermarkImage = @imagecreatefrompng($watermarkFile) ;
                    } else {
                        $ermsg = 'PNG images are not supported';
                    }
                }
                break;
            case 'image/wbmp':
                {
                    if (@imagetypes() & IMG_WBMP) {
                        $oWatermarkImage = @imagecreatefromwbmp($watermarkFile);
                    } else {
                        $ermsg = 'WBMP images are not supported';
                    }
                }
                break;
            default:
                $ermsg = $watermarkImageAttr['mime'].' images are not supported';
                break;
        }

        switch ($sourceImageAttr['mime'])
        {
            case 'image/gif':
                {
                    if (@imagetypes() & IMG_GIF) {
                        $oImage = @imagecreatefromgif($sourceFile);
                    } else {
                        $ermsg = 'GIF images are not supported';
                    }
                }
                break;
            case 'image/jpeg':
                {
                    if (@imagetypes() & IMG_JPG) {
                        $oImage = @imagecreatefromjpeg($sourceFile) ;
                    } else {
                        $ermsg = 'JPEG images are not supported';
                    }
                }
                break;
            case 'image/png':
                {
                    if (@imagetypes() & IMG_PNG) {
                        $oImage = @imagecreatefrompng($sourceFile) ;
                    } else {
                        $ermsg = 'PNG images are not supported';
                    }
                }
                break;
            case 'image/wbmp':
                {
                    if (@imagetypes() & IMG_WBMP) {
                        $oImage = @imagecreatefromwbmp($sourceFile);
                    } else {
                        $ermsg = 'WBMP images are not supported';
                    }
                }
                break;
            default:
                $ermsg = $sourceImageAttr['mime'].' images are not supported';
                break;
        }

        if (isset($ermsg) || false === $oImage || false === $oWatermarkImage) {
            return false;
        }

        $watermark_width = $watermarkImageAttr[0];
        $watermark_height = $watermarkImageAttr[1];
        $dest_x = $sourceImageAttr[0] - $watermark_width - $marginLeft;
        $dest_y = $sourceImageAttr[1] - $watermark_height - $marginBottom;

        if ($watermarkImageAttr['mime'] == 'image/png') {
            imagecopy($oImage, $oWatermarkImage, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
        }
        else {
            imagecopymerge($oImage, $oWatermarkImage, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $transparency);
        }
        switch ($sourceImageAttr['mime'])
        {
            case 'image/gif':
                imagegif($oImage, $sourceFile);
                break;
            case 'image/jpeg':
                imagejpeg($oImage, $sourceFile, $quality);
                break;
            case 'image/png':
                imagepng($oImage, $sourceFile);
                break;
            case 'image/wbmp':
                imagewbmp($oImage, $sourceFile);
                break;
        }
        imagedestroy($oImage);
        imagedestroy($oWatermarkImage);
    }
    /**
     * 自己写的
     *
     * @param unknown_type $watermarkFile
     * @param unknown_type $watermarkText
     */
    function createTextWatermark($watermarkFile,$watermarkText){
        $watermarkConfig=$this->watermarkConfig;
                
    	if (strlen($watermarkText)){
			$watermarkText = iconv('GB2312','UTF-8',$watermarkText);
			// Create some colors
			$dstScaleImg = @imagecreatefromjpeg($watermarkFile) ;
			$black = imagecolorallocate($dstScaleImg, 0, 0, 0);
			$white = imagecolorallocate($dstScaleImg, 255, 255, 255);
			//
			$imgSize=getimagesize($watermarkFile);//原图尺寸
			$width=$imgSize[0];
			$height=$imgSize[1];
			//font
			$font = ABS_PATH . '/fonts/msyh.ttf';
			//文字区域尺寸
			$txtSizeInfo=imageftbbox(10,0,$font,$watermarkText);
			$txtWidth=$txtSizeInfo[2]-$txtSizeInfo[0];
			$txtHeight=$txtSizeInfo[5]-$txtSizeInfo[1];
			if($watermarkConfig['leftTop']){
			// Add some shadow to the text,左上角
			$this->watermarkConfig['leftTopWaterMarkText'] = iconv('GB2312','UTF-8',$this->watermarkConfig['leftTopWaterMarkText']);
			
			imagettftext($dstScaleImg, 10, 0, $this->watermarkConfig['leftDistance'], abs($txtHeight)+$this->watermarkConfig['topDistance'], $black, $font, $this->watermarkConfig['leftTopWaterMarkText']);
			}
			// Add the text,右下角
			imagettftext($dstScaleImg, 10, 0, $width-$this->watermarkConfig['rightDistance']-$txtWidth, $height-$this->watermarkConfig['bottomDistance'], $black, $font, $watermarkText);
			ImageJPEG($dstScaleImg,$watermarkFile);//保存图片
			imagedestroy($dstScaleImg);
		}
    }
}