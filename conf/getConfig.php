<?php
namespace PayCenter\conf;

use Lavender\Exception;

class getConfig
{
    private static $config_cache = array();

    /**
     * @param $environment 环境
     * @param $type        支付类型
     */
    public static function get_config($environment,$type,$file_name) {

        if(empty($environment) || empty($type) || empty($file_name)) return ;
        if(empty(self::$config_cache)) {
            //没有缓存
            self::$config_cache = include L_WORKSPACE_PATH."golo/PayCenter/conf/{$environment}/{$type}/{$file_name}.php";
            if(self::$config_cache === false) throw  new Exception("config {$flie_name} not exists,file path:{$file}");
            return self::$config_cache;

        }
        //已有缓存
        return self::$config_cache;

    }
}
