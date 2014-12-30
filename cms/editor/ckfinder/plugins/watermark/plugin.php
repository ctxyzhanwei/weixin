<?php
/***自己写的start***/
if (!defined('ABS_PATH')){
	define('ABS_PATH', dirname(__FILE__).'/../../../../');
}
if (!defined('MANAGE_DIR')){
	include_once(ABS_PATH.'./config/config.inc.php');
}
if (!function_exists('loadConfig')){
	include_once(ABS_PATH.MANAGE_DIR.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.'global.func.php');
}
/***自己写的end***/
class Watermark
{
	public $watermarkConfig;
	/***自己写的start***/
	function __construct() {
		$this->watermarkConfig=loadConfig('watermark');
		$this->watermarkConfig['leftTopWaterMarkText']=$this->watermarkConfig['leftTopWaterMarkText']?$this->watermarkConfig['leftTopWaterMarkText']:$this->watermarkConfig['waterMarkText'];
		$this->watermarkConfig['leftDistance']=isset($this->watermarkConfig['leftDistance'])?intval($this->watermarkConfig['leftDistance']):9;
		$this->watermarkConfig['topDistance']=isset($this->watermarkConfig['topDistance'])?intval($this->watermarkConfig['topDistance']):16;
		$this->watermarkConfig['rightDistance']=isset($this->watermarkConfig['rightDistance'])?intval($this->watermarkConfig['rightDistance']):5;
		$this->watermarkConfig['bottomDistance']=isset($this->watermarkConfig['bottomDistance'])?intval($this->watermarkConfig['bottomDistance']):10;
	}
	/***自己写的end***/
	function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
    {
        global $config;
        $watermarkSettings = $config['Plugin_Watermark'];
        /***自己写的start***/
        if ($this->watermarkConfig['useWaterMark']){
        	$watermarkConfig=$this->watermarkConfig;
        	$sourceImageAttr = @getimagesize($sFilePath);
        	if ((!$watermarkConfig['picMinWidth'] || ($watermarkConfig['picMinWidth']&&$sourceImageAttr[0]>$watermarkConfig['picMinWidth'])) && (!$watermarkConfig['picMinHeight'] || ($watermarkConfig['picMinHeight']&&$sourceImageAttr[1]>$watermarkConfig['picMinHeight']))){

        		if ($this->watermarkConfig['waterMarkType']=='text'){
        			$this->createTextWatermark($sFilePath,$this->watermarkConfig['waterMarkText']);
        		}else {
        			$this->createWatermark($sFilePath, $watermarkSettings['source'], $this->watermarkConfig['rightDistance'],$this->watermarkConfig['bottomDistance'], $watermarkSettings['quality'], $watermarkSettings['transparency']);
        		}
        	}
        }
         /***自己写的end***/
   

        return true;
    }
    /*
    function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
    {
        global $config;
        $watermarkSettings = $config['Plugin_Watermark'];
        $this->createWatermark($sFilePath, $watermarkSettings['source'], $watermarkSettings['marginRight'],
            $watermarkSettings['marginBottom'], $watermarkSettings['quality'], $watermarkSettings['transparency']);

        return true;
    }
    */
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
                        //$oImage = @imagecreatefromgif($sourceFile);
                        $ermsg = 'GIF images are not supported';
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
                imagejpeg($oImage, $sourceFile, 100);
                break;
            case 'image/png':
                imagepng($oImage, $sourceFile);
                break;
            case 'image/wbmp':
                imagewbmp($oImage, $sourceFile);
                break;
        }
        imageDestroy($oImage);
        imageDestroy($oWatermarkImage);
    }
    /**
     * 自己写的
     *
     * @param unknown_type $watermarkFile
     * @param unknown_type $watermarkText
     */
    function createTextWatermark($watermarkFile,$watermarkText){
    	if (strlen($watermarkText)){
			//$watermarkText = iconv('GB2312','UTF-8',$watermarkText);
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
			if($this->watermarkConfig['leftTop']){
			// Add some shadow to the text,左上角
			//$this->watermarkConfig['leftTopWaterMarkText'] = iconv('GB2312','UTF-8',$this->watermarkConfig['leftTopWaterMarkText']);
			imagettftext($dstScaleImg, 10, 0, $this->watermarkConfig['leftDistance'], abs($txtHeight)+$this->watermarkConfig['topDistance'], $black, $font, $this->watermarkConfig['leftTopWaterMarkText']);
			}
			// Add the text,右下角
			imagettftext($dstScaleImg, 10, 0, $width-$this->watermarkConfig['rightDistance']-$txtWidth, $height-$this->watermarkConfig['bottomDistance'], $black, $font, $watermarkText);
			ImageJPEG($dstScaleImg,$watermarkFile,100);//保存图片
		}
    }
}

$watermark = new Watermark();
$config['Hooks']['AfterFileUpload'][] = array($watermark, 'onAfterFileUpload');
$config['Plugin_Watermark'] = array(
	"source" => "logo.png",
	"marginRight" => 5,
	"marginBottom" => 5,
	"quality" => 90,
	"transparency" => 80,
);
