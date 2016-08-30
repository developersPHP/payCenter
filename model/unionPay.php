<?php
include_once('../Pay.php');
include_once('../lib/sendHttpRequest.php');
include_once('../lib/showResult.php');
include_once('../lib/sign.php');

define(SDK_FRONT_NOTIFY_URL,'http://localhost:8085/upacp_sdk_php/demo/utf8/FrontReceive.php');
define(SDK_BACK_NOTIFY_URL,'http://golo.dev.x431.com:8081/dev/unionpay_notify.php');
define(UNIONPAY_MERID,'898111463000165');

class unionPay extends Pay
{
  private $keys;
  private $order_id;
  private $order_amount;
  private $order_des;
  private $order_words;

  public function __construct($order_id,$order_amount,$order_des,$order_words) {
    
    $this->order_id = $order_id;
    $this->order_amount = $order_amount;
    $this->order_des = $order_des;
    $this->order_words = $order_words;
    
  }
  public function bulid_param() {
    return $params = array(
	      'version'		=> '5.0.0',				  //版本号
	      'encoding'		=> 'utf-8',				//编码方式
	      'txnType'		=> '01',				    //交易类型	
	      'txnSubType'	=> '01',				  //交易子类
	      'bizType'		=> '000201',			  //业务类型
	      'frontUrl'		=> SDK_FRONT_NOTIFY_URL,//前台通知地址，控件接入的时候不会起作用
	      'backUrl'		=> SDK_BACK_NOTIFY_URL,	//后台通知地址	
	      'signMethod'	=> '01',				  //签名方法
	      'channelType'	=> '08',				  //渠道类型，07-PC，08-手机
	      'accessType'	=> '0',					  //接入类型
	      'merId'			=> UNIONPAY_MERID,	//商户代码，请改自己的测试商户号
	      'orderId'		=> $this->order_id,			  //商户订单号，8-40位数字字母
	      'txnTime'		=> date('YmdHis'),	//订单发送时间
	      'txnAmt'		=> $this->order_amount,		//交易金额，单位分
	      'currencyCode'	=> '156',				//交易币种
	      'orderDesc'		=> $this->order_des,		//订单描述，可不上送，上送时控件中会显示该信息
	      'reqReserved'	=> $this->order_words,	//请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
);
  }
  //此处处理银联支付类
  public function getKey() {
    $param = $this->bulid_param();
    
    if(isset($param['transTempUrl'])) {
      unset($param['transTempUrl']);
    }
    $this->key = sign($param);
    
  }
  
  //http请求
  public function getRequest() {
    
  }
  
  //获取数据展示
  public function showResult() {
    
  }
}
