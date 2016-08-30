<?php
include_once('./Serializableinterface.php');
define(SDK_SIGN_CERT_PATH,'/data/golo/ext/Unionpay/certificate/config/renbao/test/PM_700000000000001_acp.pfx')
class sign 
{
  //此类用于解决支付接口签名问题
  public function sign($param) {
    
    //参数转成key=value&形式
    $str_param = serializeParam($param);
    $ha1x16_param = sha1($str_param,FALSE);
    
    //签名证书路径
    $cert_path = SDK_SIGN_CERT_PATH;
    //获取私钥
    $private_key_certificate = file_get_contents($cert_path);
    openssl_pkcs12_read($private_key_certificate,$private_key,'rb4231');
    
    //获取签名
    $sign_flag = openssl_sign($ha1x16_param,$signature,$private_key['pkey'],1);
    
    $sign = $fign_flag ? base64_encode ( $signature ):'';
  }
}
