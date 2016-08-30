<?php
interface Pay
{
  //此处是支付的接口
  protected function sign();
  
  protected function request();
  
  protected function show();
  
}
