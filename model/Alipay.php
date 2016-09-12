<?php

namespace PayCenter\model;

use PayCenter\model\PayInterface;
use PayCenter\lib\sign;
use PayCenter\conf\getConfig;


class Alipay implements PayInterface
{
    private $param;
    private $sign;
    private $order_info;

    public function __construct(array $data) {

        //引入核心类,目前开发状态

        $this->param = $data;
    }

    public function doWork() {

        //首先需要先处理请求数据
        $this->request();
        //请求签名
        $this->sign();
        //返回连接
        $pay_url = $this->show();
        return $pay_url;
    }
    public function sign() {
        $pay_type = (L_ENV =='develop') ? 'develop' : 'work';
        $this ->sign = sign::alipay_sign($pay_type,$this->order_info);

    }

    public function request() {
        //请求数据的处理
        $item_name = $this->param['item_name'];
        $amount = $this->paramm['amount'];
        $order_id = $this->param['order_id'];
        $body = $this->param['body'];
        $pay_type = (L_ENV =='develop') ? 'develop' : 'work';

        $file = ($pay_type == 'develop') ? 'selftour.config' : 'alipay.config';
        $config = getConfig::get_config($pay_type.'Environment','alipay',$file);
        //var_dump($alipay_config['seller_email']);exit;

        switch (L_ENV) {
            case 'develop':
                if(!empty($pay_type) && $pay_type == 'develop')
                    $notify_url = "http://golo.dev.x431.com:8081/dev/alipay_selftout_notify.php";
                else
                    $notify_url = 'http://golo.dev.x431.com:8081/dev/alipay_notify.php';
                break;
            case 'test':
                if(!empty($pay_type) && $pay_type == 'develop')
                    $notify_url = "http://golo.test.x431.com:8008/dev/alipay_selftout_notify.php";
                else
                    $notify_url = 'http://golo.test.x431.com:8008/dev/alipay_notify.php';
                break;
            case 'work':
                if(!empty($pay_type) && $pay_type == 'develop')
                    $notify_url = "http://base.api.dbscar.com/alipay_selftout_notify.php";
                else
                    $notify_url = 'http://base.api.dbscar.com/alipay_notify.php';
                break;
        }

        // 合作者身份ID
        $this->order_info = 'partner="'. $config['partner']. '"';
        //卖家
        $this->order_info .= '&seller_id="'. $config['seller_email'] .'"';

        // 商户网站唯一订单号
        $this->order_info .= '&out_trade_no="' . $order_id . '"';
        // 商品名称
        $this->order_info .= '&subject="' . $item_name . '"';
        // 商品详情
        $this->order_info .= '&body="' . $body . '"';
        // 商品金额
        $this->order_info .= '&total_fee="' . $amount . '"';
        // 服务器异步通知页面路径
        $this->order_info .= '&notify_url="' . $notify_url . '"';
        // 接口名称， 固定值
        $this->order_info .= '&service="mobile.securitypay.pay"';
        // 参数编码， 固定值
        $this->order_info .= '&_input_charset="utf-8"';
        // 支付类型， 固定值
        $this->order_info .= '&payment_type="1"';

        // 设置未付款交易的超时时间
        // 默认30分钟，一旦超时，该笔交易就会自动被关闭。
        // 取值范围：1m～15d。
        // m-分钟，h-小时，d-天，1c-当天（无论交易何时创建，都在0点关闭）。
        // 该参数数值不接受小数点，如1.5h，可转换为90m。
        $this->order_info .= "&it_b_pay=\"30m\"";

        // 支付宝处理完请求后，当前页面跳转到商户指定页面的路径，可空
        // $orderInfo .= "&return_url=\"m.alipay.com\"";

        // 调用银行卡支付，需配置此参数，参与签名， 固定值
        // $orderInfo .= "&paymethod=\"expressGateway\"";

    }

    public function show() {

        return $this->order_info . '&sign="' . urlencode($this->sign) . '"&sign_type="RSA"';
    }
}
