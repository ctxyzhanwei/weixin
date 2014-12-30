<?php
class tag{
	var $tags;
	function __construct(){
		$this->tags=array('a','ad','ads','channel','channels','content','contents','focusViewer','include','location','pageContents','pageItem','pageItems','site','sites','input','inputContents','pageInputContents','inputContent','groupContents');
	}
	/**
	 * get $attribute(eg. channelIndex="notice") value from string,eg the $str is:<stl:a ***></stl:a>
	 *
	 * @param string $str
	 * @param string $attribute
	 * @return string
	 */
	function getAttributeValue($str,$attribute){//<stl:a ***></stl:a>
		$strArr=explode('>',$str);//get <stl:a ***>
		$quoteCount=substr_count($strArr[0],'"');
		if ($quoteCount%2>0){//<stl:locaiton separator=">>">
			$i=$quoteEndPos=strpos($str,'>');
			while ($quoteCount%2>0&&$i<strlen($str)){//<stl:locaiton separator=">>">
				$quoteEndPos=strpos($str,'>',$i);
				$strArr[0]=substr($str,0,$i+1);
				$i++;
				$quoteCount=substr_count($strArr[0],'"');
			}
		}
		$attributePos=strpos($strArr[0],$attribute);
		if ($attributePos){
			$attributeQuoteStartPos=$attributePos+strlen($attribute)+2;//name="
			if (strlen($strArr[0])>$attributeQuoteStartPos){
				$attributeQuoteEndPos=strpos($strArr[0],'"',$attributeQuoteStartPos);//"
			}
			return substr($strArr[0],$attributeQuoteStartPos,$attributeQuoteEndPos-$attributeQuoteStartPos);
		}else {
			return null;
		}
	}
	function getAttributeValues($str,$attributes){
		//get attribute values
		$avs=array();
		if ($attributes){
			foreach ($attributes as $attribute){
				$avs[$attribute]=$this->getAttributeValue($str,$attribute);
			}
		}
		return $avs;
	}
	function getTagForbody($str){//eg.<stl:contents......>
		$strArr=explode('>',$str);
		//
		$quoteCount=substr_count($strArr[0],'"');
		if ($quoteCount%2>0){//<stl:locaiton separator=">>">
			$i=$quoteEndPos=strpos($str,'>');
			while ($quoteCount%2>0&&$i<strlen($str)){//<stl:locaiton separator=">>">
				$quoteEndPos=strpos($str,'>',$i);
				$strArr[0]=substr($str,0,$i+1);
				$i++;
				$quoteCount=substr_count($strArr[0],'"');
			}
		}
		//
		return $strArr[0].'>';
	}
	function getTagAftbody($tagname,$gTag){//eg.</stl:contents>
		return '</'.$gTag.':'.$tagname.'>';
	}
	function getMiddleBody($str,$tagname,$gTag){//string between <stl:contents......> and </stl:contents>
		$forStr=$this->getTagForbody($str);
		$aftStr=$this->getTagAftbody($tagname,$gTag);
		$middleStr=substr($str,strlen($forStr),strlen($str)-strlen($forStr)-strlen($aftStr));
		return $middleStr;
	}
	function removeProperties($str,$properties){
		$forbody=$this->getTagForbody($str);
		$handledForbody=$forbody;
		if ($properties){
			foreach ($properties as $property){
				$handledForbody=preg_replace('# '.$property.'="([a-z0-9 _]+)"#i','',$handledForbody);
			}
		}
		return str_replace($forbody,$handledForbody,$str);
	}
}
?>