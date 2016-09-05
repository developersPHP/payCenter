<?php
namespace PayCenter;
use PayCenter\PayInterface;

abstract class PayCenter
{
    //此处处理不同的支付类对象
    protected  abstract function payMethod(PayFactory $different_method);

    public function startPay($payMethod) {
        $result = $this->payMethod($payMethod);
        return $result;
    }
}
