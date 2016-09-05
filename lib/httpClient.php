<?php
namespace PayCenter\lib;

class httpClient
{
    public static function sendHttpRequest($params, $url)
    {
        $opts = http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);  //init the url
        curl_setopt($ch, CURLOPT_POST, 1);    //set the patterm
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//check the HOST
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);//你最好别设置这个值，让它使用默认值。 设置为 2 或 3 比较危险，在 SSLv2 和 SSLv3 中有弱点存在
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/x-www-form-urlencoded;charset=UTF-8'));//设置 HTTP 头字段的数组。格式： array('Content-type: text/plain', 'Content-length: 100')
        curl_setopt($ch, CURLOPT_POSTFIELDS, $opts);

        /**
         * 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
         */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。

        // 运行cURL，请求网页
        $html = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close( $ch );

        return $html;
    }
}
