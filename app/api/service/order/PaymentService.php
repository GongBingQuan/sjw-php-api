<?php

namespace app\api\service\order;
use app\common\library\alipay\AliPay;
use app\common\library\easywechat\AppOpen;
use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\AppMp;
use app\common\library\easywechat\WxPay;

class PaymentService
{
    /**
     * 构建微信支付
     */
    public static function wechat(
        $user,
        $orderId,
        $orderNo,
        $payPrice,
        $orderType,
        $pay_source
    )
    {
        // 统一下单API
        if($pay_source == 'wx'){
            $app = AppWx::getWxPayApp($user['app_id']);
            $open_id = $user['open_id'];
        }else if($pay_source == 'mp'){
            $app = AppMp::getWxPayApp($user['app_id']);
            $open_id = $user['mpopen_id'];
        } else if($pay_source == 'payH5'){
            $app = AppMp::getWxPayApp($user['app_id']);
            $open_id = '';
        } else if($pay_source == 'app'){
            $app = AppOpen::getWxPayApp($user['app_id']);
            $open_id = $user['appopen_id'];
        }


        $WxPay = new WxPay($app);
        $payment = $WxPay->unifiedorder($orderNo, $open_id, $payPrice, $orderType, $pay_source);
        if($pay_source == 'wx'){
            return $payment;
        }else if($pay_source == 'mp'){
            $jssdk = $app->jssdk;
            return $jssdk->bridgeConfig($payment['prepay_id']);
        }else if($pay_source == 'payH5'){
            return $payment;
        }else if($pay_source == 'app'){
            return $payment;
        }
        return false;
    }

    /**
     * 构建支付宝支付
     */
    public static function alipay(
        $user,
        $orderId,
        $orderNo,
        $payPrice,
        $orderType,
        $pay_source
    )
    {
        $AliPay = new AliPay();
        $payment = $AliPay->unifiedorder($orderNo, $payPrice, $orderType, $pay_source);
        if($pay_source == 'payH5'){
            return $payment;
        }
        return false;
    }
}