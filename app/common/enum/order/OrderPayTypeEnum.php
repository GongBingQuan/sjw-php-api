<?php

namespace app\common\enum\order;

use MyCLabs\Enum\Enum;

/**
 * 订单支付方式枚举类
 */
class OrderPayTypeEnum extends Enum
{
    // 线下支付
    const LINEPAY = 0;
    // 余额支付
    const BALANCE = 10;

    // 后台下单
    const BACK = 40;

    // 微信支付
    const WECHAT = 20;

    // 支付宝支付
    const ALIPAY = 30;

    /**
     * 获取枚举数据
     */
    public static function data()
    {
        return [
            self::LINEPAY => [
                'name' => '线下打款',
                'value' => self::LINEPAY,
            ],
            self::BALANCE => [
                'name' => '余额支付',
                'value' => self::BALANCE,
            ],
            self::WECHAT => [
                'name' => '微信支付',
                'value' => self::WECHAT,
            ],
            self::ALIPAY => [
                'name' => '支付宝支付',
                'value' => self::ALIPAY,
            ],
            self::BACK => [
                'name' => '后台下单',
                'value' => self::BACK,
            ],
        ];
    }

}