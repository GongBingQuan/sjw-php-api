<?php

namespace app\common\enum\order;

use MyCLabs\Enum\Enum;

/**
 * 订单类型枚举类,用于后期扩展，比如虚拟物品
 */
class OrderTypeEnum extends Enum
{
    // 商城订单
    const MASTER = 10;

    // 礼包购订单
    const GIFT = 20;

    // 余额充值订单
    const BALANCE = 30;

    /**
     * 获取订单类型值
     */
    public static function data()
    {
        return [
            self::MASTER => [
                'name' => '商城订单',
                'value' => self::MASTER,
            ],
            self::GIFT => [
                'name' => '礼包购订单',
                'value' => self::GIFT,
            ],
            self::BALANCE => [
                'name' => '余额充值订单',
                'value' => self::BALANCE,
            ],
        ];
    }

    /**
     * 获取订单类型名称
     */
    public static function getTypeName()
    {
        static $names = [];

        if (empty($names)) {
            foreach (self::data() as $item)
                $names[$item['value']] = $item['name'];
        }

        return $names;
    }

}