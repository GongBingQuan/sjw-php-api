<?php

namespace app\job\controller;


use app\common\library\alipay\AliPay;
use app\common\library\easywechat\WxPay;

/**
 * 微信支付回调
 */
class Notify
{
    /**
     * 微信支付回调
     */
    public function wxpay()
    {
        // 微信支付组件：验证异步通知
        $WxPay = new WxPay(false);
        $WxPay->notify();
    }

    /**
     * 支付宝支付回调（同步）
     */
    public function alipay_return()
    {
        $AliPay = new AliPay();
        $url = $AliPay->return();
        if($url){
            return redirect($url);
        }
        return false;
    }

    /**
     * 支付宝支付回调（异步）
     */
    public function alipay_notify()
    {
        $AliPay = new AliPay();
        $AliPay->notify();
    }
}
