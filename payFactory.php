<?php
include_once('./PayCenter');
namespace PayCenter;

use PayCenter\PayCenter;
class PayFactory extends PayCenter {

    protected function payMethod(PayInterface $obj) {

        $payClass = $obj;
        $result = $payClass->doWork();
        return $result;
    }
} 
