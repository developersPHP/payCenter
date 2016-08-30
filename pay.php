<?php
interface Pay
{
  //此处是支付的接口
  public function sign();
  
  public function request();
  
  public function show();
  
}
