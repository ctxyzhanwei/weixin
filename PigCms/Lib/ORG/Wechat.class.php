<?php
class Wechat {
	public $token;
	public $wxuser;
	public $pigsecret;
	private $data = array();
	public function __construct($token, $wxuser = '') {
		$this->auth($token, $wxuser) || exit;
		if (IS_GET) {
			echo($_GET['echostr']);
			exit;
		}else {
			$this->token = $token;
			if (!$wxuser) {
				$wxuser = M('wxuser')->where(array('token' => $this->token))->find();
			}
			$this->wxuser = $wxuser;
			if (!$this->wxuser['pigsecret']) {
				$this->pigsecret = $this->token;
			}else {
				$this->pigsecret = $this->wxuser['pigsecret'];
			}
			$xml = file_get_contents("php://input");
			if ($this->wxuser['encode'] == 2) {
				$this->data = $this->decodeMsg($xml);
			}else {
				$xml = new SimpleXMLElement($xml);
				$xml || exit;
				foreach ($xml as $key => $value) {
					$this->data[$key] = strval($value);
				}
			}
		}
	}
	public function encodeMsg($sRespData) {
		$sReqTimeStamp = time();
		$sReqNonce = $_GET['nonce'];
		$encryptMsg = "";
		import("@.ORG.aes.WXBizMsgCrypt");
		$pc = new WXBizMsgCrypt($this->pigsecret, $this->wxuser['aeskey'], $this->wxuser['appid']);
		$sRespData = str_replace('<?xml version="1.0"?>', '', $sRespData);
		$errCode = $pc->encryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $encryptMsg);
		if ($errCode == 0) {
			return $encryptMsg;
		}else {
			return $errCode;
		}
	}
	public function decodeMsg($msg) {
		import("@.ORG.aes.WXBizMsgCrypt");
		$sReqMsgSig = $_GET['msg_signature'];
		$sReqTimeStamp = $_GET['timestamp'];
		$sReqNonce = $_GET['nonce'];
		$sReqData = $msg;
		$sMsg = "";
		$pc = new WXBizMsgCrypt($this->pigsecret, $this->wxuser['aeskey'], $this->wxuser['appid']);
		$errCode = $pc->decryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
		if ($errCode == 0) {
			$data = array();
			$xml = new SimpleXMLElement($sMsg);
			$xml || exit;
			foreach ($xml as $key => $value) {
				$data[$key] = strval($value);
			}
			return $data;
		}else {
			return $errCode;
		}
	}
	/**
	 * 获取微信推送的数据
	 * @return array 转换为数组后的数据
	 */	
	public function request() {
		return $this->data;
	}
	/**
	 * * 响应微信发送的信息（自动回复）
	 * @param  string $to      接收用户名
	 * @param  string $from    发送者用户名
	 * @param  array  $content 回复信息，文本信息为string类型
	 * @param  string $type    消息类型
	 * @param  string $flag    是否新标刚接受到的信息
	 * @return string          XML字符串
	 */
	public function response($content, $type = 'text', $flag = 0) {
		$this->data = array('ToUserName' => $this->data['FromUserName'], 'FromUserName' => $this->data['ToUserName'], 'CreateTime' => NOW_TIME, 'MsgType' => $type,);
		/* 添加类型数据 */
		$this->$type($content);
		/* 添加状态 */
		$this->data['FuncFlag'] = $flag;
		/* 转换数据为XML */
		$xml = new SimpleXMLElement('<xml></xml>');
		$this->data2xml($xml, $this->data);
		if (isset($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
			exit($this->encodeMsg($xml->asXML()));
		}else {
			exit($xml->asXML());
		}
	}
	/**
	 * 回复文本信息
	 * @param  string $content 要回复的信息
	 */
	private function text($content) {
		$this->data['Content'] = $content;
	}
	/**
	 * 回复音乐信息
	 * @param  string $content 要回复的音乐
	 */
	private function music($music) {
		list($music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl']) = $music;
		$this->data['Music'] = $music;
	}
	/**
	 * 回复图文信息
	 * @param  string $news 要回复的图文内容
	 */
	private function news($news) {
		$articles = array();
		foreach ($news as $key => $value) {
			list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = $value;
			if ($key >= 9) {
				break;
                        }//最多只允许10调新闻
		}
		$this->data['ArticleCount'] = count($articles);
		$this->data['Articles'] = $articles;
	}
	private function transfer_customer_service($content) {
		$this->data['Content'] = '';
	}
	private function data2xml($xml, $data, $item = 'item') {
		foreach ($data as $key => $value) {
                         /* 指定默认的数字key */
			is_numeric($key) && $key = $item;
                        /* 添加子元素 */
			if (is_array($value) || is_object($value)) {
				$child = $xml->addChild($key);
				$this->data2xml($child, $value, $item);
			}else {
				if (is_numeric($value)) {
					$child = $xml->addChild($key, $value);
				}else {
					$child = $xml->addChild($key);
					$node = dom_import_simplexml($child);
					$node->appendChild($node->ownerDocument->createCDATASection($value));
				}
			}
		}
	}
	private function auth($token, $wxuser = '') {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		if (!$wxuser) {
		}
		if ($wxuser && strlen($wxuser['pigsecret'])) {
		}
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if (trim($tmpStr) == trim($signature)) {
			return true;
		}else {
			return true;
		}
		return true;
	}
}

?>