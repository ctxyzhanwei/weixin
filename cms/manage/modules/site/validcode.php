<?php
class validcode {
	function __construct() {

	}
	function vc(){
		$session_storage = getSessionStorageType();
		bpBase::loadSysClass($session_storage);
		$captcha = bpBase::loadSysClass('checkCode');
		//width
		if (isset($_GET['width']) && intval($_GET['width'])) $captcha->width = intval($_GET['width']);
		if ($captcha->width <= 0) {
			$captcha->width = 70;
		}
		//height
		if (isset($_GET['height']) && intval($_GET['height'])) $captcha->height = intval($_GET['height']);
		if ($captcha->height <= 0) {
			$captcha->height = 25;
		}
		//codeNum
		if (isset($_GET['codeNum']) && intval($_GET['codeNum'])) $captcha->code_len = intval($_GET['codeNum']);
		if ($captcha->codeNum > 8 || $captcha->codeNum < 2) {
			$captcha->codeNum = 4;
		}
		//backGround
		if (isset($_GET['backGround']) && trim(urldecode($_GET['backGround'])) && preg_match('/(^[a-z0-9]{6}$)/im', trim(urldecode($_GET['backGround'])))) $captcha->backGround = '#'.trim(urldecode($_GET['backGround']));
		//fontColor
		if (isset($_GET['fontColor']) && trim(urldecode($_GET['fontColor'])) && preg_match('/(^[a-z0-9]{6}$)/im', trim(urldecode($_GET['fontColor'])))) $captcha->fontColor = '#'.trim(urldecode($_GET['fontColor']));
		$captcha->showImg();
		$_SESSION['validCode']=$captcha->getCaptcha();
	}
}
?>