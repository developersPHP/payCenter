<?php
namespace PayCenter\model;

interface PayInterface
{
    public function doWork($param);
    //此处是支付的功能接口
    public  function sign();

    public  function request();

    public  function show();

}
