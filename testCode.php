<?php
include "./PayCenter/model/Alipay.php";
include "./PayCenter/PayFactory.php";
include "./PayCenter/PayCenter.php";

class testCode
{
  public function testPayCenter() {
        //$data = array('order_id'=>22222,'order_amount'=>223.12,'order_des'=>'人工精洗','order_words'=>'下单');
        $data = array('item_name'=>'人工精洗','body'=>'商品描述','amount'=>223.12,'order_id'=>22222);
        //$unionPayClass = new unionPay($data);
        $alipay = new Alipay($data);
        $payFactory = new PayFactory();
        $result = $payFactory->startPay($alipay);
        return $this->success('',$result);
  }
}

$test = new testCode();
$result = $test->testPayCenter();
print_r($result);
