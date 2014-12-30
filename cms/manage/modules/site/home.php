<?php
class home {
	function __construct() {
	}
	/**
	 * 网站首页
	 *
	 */
	function home(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->index();
	}
	function channel(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->channel();
	}
	function content(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->content();
	}
	function search(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->search();
	}
	function map(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->map();
	}
	function share(){
		$sitePage=bpBase::loadAppClass('sitePage','site',1);
		$sitePage->share();
	}
}
?>