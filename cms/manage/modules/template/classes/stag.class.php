<?php
class stag{
	public $regex;
	function __construct(){
		$this->regex='#\[stl.([a-z]+):([a-z0-9_\-.]+)\]#i';
	}
	function handleStag($str){
		preg_match_all($this->regex,$str,$varArray);
		$checkArr=array();
		if ($varArray[1]){
			$i=0;
			foreach ($varArray[1] as $k=>$tagName){
				$tagValue=$varArray[2][$i];
				$tag='[stl.'.$tagName.':'.$tagValue.']';
				if (!in_array($tag,$checkArr)){
					//start
					if ($tagClass=bpBase::loadSmallTagClass('stag_'.$tagName)){
						$returnStr=$tagClass->getValue($tagValue);
						$str=str_replace($tag,$returnStr,$str);
					}
					//end
					array_push($checkArr,$tag);
				}
				$i++;
			}
		}
		return $str;
	}
	/**
	 * 在字符串中，查找第一个短标签的值
	 *
	 * @param string $str
	 * @param string $tagName 比如 store
	 * @return unknown
	 */
	function getFirstTagValue($str,$tagName){
		preg_match_all($this->regex,$str,$varArray);
		if ($varArray[1]){
			$i=0;
			foreach ($varArray[1] as $v){
				$tagValue=$varArray[2][$i];
				if ($v==$tagName){
					return $tagValue;
				}else {
					return 0;
				}
				$i++;
			}
		}
	}
}
?>