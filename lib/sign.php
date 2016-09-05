<?php
namespace PayCenter\lib;
use PayCenter\lib\showResult;
//define(SDK_SIGN_CERT_PATH,'/data/golo/ext/Unionpay/certificate/config/renbao/work/renbao_privatekey.pfx');
define(SDK_SIGN_CERT_PATH,'D:\Platform\dev\ext\Unionpay\certificate\config\renbao\work\renbao_privatekey.pfx');
define(SDK_SIGN_CERT_PWD,'rb4231');
class sign
{
    //签名证书路径
    public static function getPrivateKey($cert_path) {

        $pkcs12 = file_get_contents ( $cert_path );
        openssl_pkcs12_read ( $pkcs12, $certs, SDK_SIGN_CERT_PWD );
        return $certs ['pkey'];
    }
    //获取
    public function getSignCertId()
    {
        // 签名证书路径
        return self::getCertId(SDK_SIGN_CERT_PATH);
    }

    /**
     * 取证书ID(.pfx)
     *
     * @return unknown
     */
    public function getCertId($cert_path)
    {
        $pkcs12certdata = file_get_contents($cert_path);
        openssl_pkcs12_read($pkcs12certdata, $certs, SDK_SIGN_CERT_PWD);
        $x509data = $certs['cert'];
        openssl_x509_read($x509data);
        $certdata = openssl_x509_parse($x509data);
        return $certdata['serialNumber'];
    }
}
