<?php

namespace app\common\model\plus\giftpackage;

use app\common\model\BaseModel;
use app\common\service\order\OrderService;

/**
 * Class Order
 * 礼包购模型
 * @package app\common\model\plus\giftpackage
 */
class Order extends BaseModel
{
    protected $name = 'gift_order';
    protected $pk = 'order_id';

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo("app\\common\\model\\user\\User", 'user_id', 'user_id')->field('user_id,nickName');
    }

    /**
     * 生成订单号
     */
    public function orderNo()
    {
        return OrderService::createOrderNo();
    }
    /**
     * 关联礼包购活动
     */
    public function gift()
    {
        return $this->belongsTo("app\\common\\model\\plus\\giftpackage\\GiftPackage", 'gift_package_id', 'gift_package_id')->field('gift_package_id,name');
    }
    /**
     * 支付状态
     */
    public function getPayStatusAttr($value)
    {
        $status = [10 => '未付款', 20 => '已付款'];
        return ['text' => $status[$value], 'value' => $value];
    }
     /**
     * 支付方式
     */
    public function getPayTypeAttr($value)
    {
        $status = [10 => '余额', 20 => '微信'];
        return ['text' => $status[$value], 'value' => $value];
    }
    /**
     * 详情
     */
    public static function detail($order_id)
    {
        return (new static())->find($order_id);
    }
}