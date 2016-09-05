<?php
include_once('../lib/sign.php');
include_once('../lib/showResult.php');
include_once('../lib/httpClient.php');

namespace PayCenter\model;
use PayCenter\lib\httpClient;
use PayCenter\lib\showResult;
use PayCenter\lib\sign;
use PayCenter\model\PayInterface;

define(SDK_App_Request_Url,'https://gateway.95516.com/gateway/api/appTransReq.do');
define(SDK_FRONT_NOTIFY_URL,'http://base.api.dbscar.com/unionpay_ceritificate_notify.php');
define(SDK_BACK_NOTIFY_URL,'http://base.api.dbscar.com/unionpay_ceritificate_notify.php');
define(UNIONPAY_MERID,'898111463000165');
define(OPENSSL_ALGO_SHA1,1);

class unionPay implements PayInterface
{
    private static $param;
    private static $request_result;

    public function __construct($data) {
        //初始化功能
        $this->setParam($data);

    }

    //参数设置方法
    public function setParam(array $data) {
        $this->params = array(
            'version'		=> '5.0.0',				//版本号
            'encoding'		=> 'utf-8',				//编码方式
            'certId'		=> sign::getSignCertId(),		//证书ID
            'txnType'		=> '01',				//交易类型
            'txnSubType'	=> '01',				//交易子类
            'bizType'		=> '000201',			//业务类型
            'frontUrl'		=> SDK_FRONT_NOTIFY_URL,//前台通知地址，控件接入的时候不会起作用
            'backUrl'		=> SDK_BACK_NOTIFY_URL,	//后台通知地址
            'signMethod'	=> '01',				//签名方法
            'channelType'	=> '08',				//渠道类型，07-PC，08-手机
            'accessType'	=> '0',					//接入类型
            'merId'			=> UNIONPAY_MERID,		//商户代码，请改自己的测试商户号
            'orderId'		=> $data['order_id'],			//商户订单号，8-40位数字字母
            'txnTime'		=> date('YmdHis'),		//订单发送时间
            'txnAmt'		=> $data['order_amount'],		//交易金额，单位分
            'currencyCode'	=> '156',				//交易币种
            'orderDesc'		=> $data['order_des'],			//订单描述，可不上送，上送时控件中会显示该信息
            'reqReserved'	=> $data['order_words'],		//请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );
    }

    //业务功能
    public function doWork($data) {

        //设置参数
        //签名
        $this->sign();
        //http请求
        $this->request();
        //返回结果展示
        $result = $this->show();
        return $result;
    }


    //基本接口功能方法
    public function sign() {

        if (isset($this->params['transTempUrl'])) {
            unset($this->params['transTempUrl']);
        }

        // 转换成key=val&串
        $params_str = showResult::serializeParam($this->params);

        $params_sha1x16 = sha1($params_str, FALSE);

        // 签名证书路径
        $cert_path = SDK_SIGN_CERT_PATH;
        $private_key = sign::getPrivateKey($cert_path);


        // 签名
        $sign_falg = openssl_sign($params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1);

        if ($sign_falg) {
            $this->params['signature'] = base64_encode ( $signature );
        }
    }

    public function request() {

        $this->request_result = httpClient::sendHttpRequest($this->params,SDK_App_Request_Url);
    }

    public function show() {

        $last_result = showResult::changeStringToArray($this->request_result);
        return $last_result;
    }

}
