<?php

/*
 * PHP SDK for WechatQcloud    
 * @version        v1.0.0
 * @copyright    Copyright (c) 1998-2014 Tencent. (http://weixin.qcloud.com)
 */

class WechatQcloud
{

    //微信云cgi配置
    const CST_CGI_QCLOUD = "http://weixin.qcloud.com/cgi/open";

    //请求有效期，单位是分钟
    const CST_REQUEST_TIME = 5;

    //返回码
    private static $arrReturnCode = array(

        'CODE_SUCCESS' => array('code' => 0, 'msg' => '处理成功'),
        'CODE_ERR_TIMESTAMP' => array('code' => 100000, 'msg' => '时间戳不能为空'),
        'CODE_ERR_REQUEST_EXPIRED' => array('code' => 100001, 'msg' => '请求超过5分钟导致失效'),
        'CODE_ERR_SIGNATURE' => array('code' => 100002, 'msg' => '签名信息错误'),
        'CODE_ERR_QUERYSUBORDER' => array('code' => 100003, 'msg' => '确认发货信息失败'),
        'CODE_ERR_CONFIRMDELIVERY' => array('code' => 100004, 'msg' => '发货完成通知失败'),

    );

    //签名key
    private $strSignKey = '9c51aff699fdef9ce53edc49ad11ab93';

    //请求参数
    private $arrDataQuery = array();

    /**
     * 构造函数
     *    
     * @param   string  $strSignKey     签名字符串
     * @return     void    
     */
    public function __construct($strSignKey)
    {

        $this->setSignKey($strSignKey);

    }

    /**
     * 远程POST请求函数
     *    
     * @param     string     $url     请求的url
     * @param     array     $data     请求的参数列表
     * @return     mixed    
	 * private -> public
     */
    public static function httpCurl($url, $data = array())
    {

        $result = FALSE;

        $ch = curl_init($url);

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;

    }

    /**
     * 构造微信云cgi请求参数数组
     *    
     * @param   string     $openId         每个用户在一个服务商分配唯一标识
     * @param   string     $providerId        服务商id
     * @param   string     $subOrderId        发货子订单id
     * @param   string     $timeStamp         时间戳
     * @param   string     $signature         签名
     * @return  void    
     */
    private function setQueryData($openId, $providerId, $subOrderId, $timeStamp, $signature)
    {
        $this->arrDataQuery = array(    
            'openId' => $openId,    
            'providerId' => $providerId,
            'subOrderId' => $subOrderId,
            'timeStamp' => $timeStamp,
            'signature' => $signature
        );
    }

    /**
     * 获取请求参数
     *    
     * @return     array    
     */
    private function getQueryData()
    {
        return $this->arrDataQuery;
    }

    /**
     * 设置签名key
     *    
     * @param   string  $strSignKey     签名字符串
     * @return  string    
     */
    private function setSignKey($strSignKey)
    {
        $this->strSignKey = $strSignKey;
    }

    /**
     * 获取签名key
     *    
     * @return     string    
     */
    private function getSignKey()
    {
        return $this->strSignKey;
    }

    /**
     * 签名计算
     *    
     * @param     array     $data    请求参数
     * @return    string    
     */
    public function createMD5Signature($data)
    {
        $params = array(    
            'openId' => isset($data['openId']) ? $data['openId'] : '',    
            'providerId' => isset($data['providerId']) ? $data['providerId'] : '',    
            'subOrderId' => isset($data['subOrderId']) ? $data['subOrderId'] : '',    
            'timeStamp' => isset($data['timeStamp']) ? $data['timeStamp'] : 0
        );

        //按键值排序
        ksort($params);

        $strParam = urldecode(http_build_query($params));
        $strParam .= $this->getSignKey();

        return md5($strParam);
    }

    /**
     * 参数有效性检测
     *
     *     1、检测时间戳
     *     2、检测签名信息
     *    
     * @return     array    
     */
    private function checkParams()
    {
        $arrParams = $this->getQueryData();

        $timeStamp = $arrParams['timeStamp'];
        $signature = $arrParams['signature'];

        //检测时间戳
        if($timeStamp <= 0){
            return self::$arrReturnCode['CODE_ERR_TIMESTAMP'];
        }

        if((time() - ($timeStamp / 1000)) / 60 > self::CST_REQUEST_TIME){
            return self::$arrReturnCode['CODE_ERR_REQUEST_EXPIRED'];
        }

        //检测签名信息
        if($signature != $this->createMD5Signature($arrParams)){

            return self::$arrReturnCode['CODE_ERR_SIGNATURE'];
        }

        return self::$arrReturnCode['CODE_SUCCESS'];
    }


    /**
     * 公共请求包装
     *    
     * 因为代码相似度很高，所以这里提取成为公用方法， 方便维护
     *
     * @param string     $action     如：querySubOrder
     * @param array     $errorMsg     如：self::$arrReturnCode['CODE_ERR_QUERYSUBORDER']
     * @return     array    
     */
    private function commonRequest($action, $errorMsg)
    {
        //有效性检测
        $checker = $this->checkParams();
        if($checker['code'] > 0){
            return $checker;
        }

        $url = self::CST_CGI_QCLOUD . "?action=" . $action;

        $retCurl = self::httpCurl($url, $this->getQueryData());
        $arrRetCurl = json_decode($retCurl, true);

        if(!isset($arrRetCurl['code']) || $arrRetCurl['code'] != 0){

            //返回cgi错误码
            $errorMsg['msg'] .= '('. $arrRetCurl['code'] .' : '. $arrRetCurl['msg'] .')';

            return $errorMsg;
        }

        return self::$arrReturnCode['CODE_SUCCESS'];
    }

    /**
     * 查询并确认发货信息
     *    
     * @param   string  $openId         每个用户在一个服务商分配唯一标识
     * @param   string  $providerId     服务商id
     * @param   string  $subOrderId     发货子订单id
     * @param   string  $timeStamp      时间戳
     * @param   string  $signature      签名
     * @return     array    
     */
    public function queryOrderInfo($openId, $providerId, $subOrderId, $timeStamp, $signature)
    {
        //设置请求参数
        $this->setQueryData($openId, $providerId, $subOrderId, $timeStamp, $signature);

        return $this->commonRequest(
            'querySubOrder',    
            self::$arrReturnCode['CODE_ERR_QUERYSUBORDER']
        );
    }

    /**
     * 发货完成后通知微信云
     *    
     * @param   string  $openId         每个用户在一个服务商分配唯一标识
     * @param   string  $providerId     服务商id
     * @param   string  $subOrderId     发货子订单id
     * @param   string  $timeStamp      时间戳
     * @param   string  $signature      签名
     * @return     array    
     */
    public function confirmDelivery($openId, $providerId, $subOrderId, $timeStamp, $signature)
    {
        //设置请求参数
        $this->setQueryData($openId, $providerId, $subOrderId, $timeStamp, $signature);

        return $this->commonRequest(
            'confirmDelivery',    
            self::$arrReturnCode['CODE_ERR_CONFIRMDELIVERY']
        );
    }

}
