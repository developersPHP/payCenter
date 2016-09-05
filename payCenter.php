<?php
abstract class PayCenter
{
  //此处处理不同的支付类对象
  protected abstract function payMethod(Pay $diffrent_method);
  
  public function startPay($payMethod) {
    $result = $this->payMethod($payMethod);
    return $result;
  }
}
