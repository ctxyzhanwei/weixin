<?php
class keylink{
	function _base64_encode($t,$str) {
		return $t."\"".base64_encode($str)."\"";
	}
	function _base64_decode($t,$str) {
		return $t."\"".base64_decode($str)."\"";
	}
	function delete_keylinks($txt,$linkdatas) {
		$txt=str_replace('title=""','',$txt);
		$search = "/(alt\s*=\s*|title\s*=\s*)[\"|\'](.+?)[\"|\']/ise";
		$replace = "\$this->_base64_encode('\\1','\\2')";
		$replace1 = "\$this->_base64_decode('\\1','\\2')";
		@$txt = preg_replace($search, $replace, $txt);
		
		if($linkdatas) {
			$word = $replacement = array();
			foreach($linkdatas as $v) {
				if ($v[0]!='欧诺'){
				$word1[] = '/(?!(<a.*?))' . preg_quote($v[0], '/') . '(?!.*<\/a>)/s';
				$word2[] = $v[0];
				$replacement[] = '<a href="'.$v[1].'" target="_blank" class="contentTextLink">'.$v[0].'</a>';
				}

			}
			$txt = preg_replace($word1, $replacement, $txt, 1);
		}
		@$txt = preg_replace($search, $replace1, $txt);
		return $txt;
	}
	function _keylinks($txt,$linkdatas) {
		$txt=str_replace('title=""','',$txt);
		$search = "/(alt\s*=\s*|title\s*=\s*)[\"|\'](.+?)[\"|\']/ise";
		$replace = "\$this->_base64_encode('\\1','\\2')";
		$replace1 = "\$this->_base64_decode('\\1','\\2')";
		@$txt = preg_replace($search, $replace, $txt);
		mb_regex_encoding("gb2312");
		if($linkdatas) {
			$word = $replacement = array();
			foreach($linkdatas as $v) {
				$txt=mb_ereg_replace($v[0],'<a href="'.$v[1].'" target="'.$v[3].'" class="contentTextLink">'.$v[0].'</a>',$txt);
			}
		}
		@$txt = preg_replace($search, $replace1, $txt);
		return $txt;
	}
}
?>