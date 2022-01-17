<?php

namespace app\api\model\order;

use \app\common\model\order\OrderApply as OrderApplyModel;

/**
 * 订单地址模型
 */
class OrderApply extends OrderApplyModel
{
    /**
     * 隐藏字段
     */
    protected $hidden = [
        'app_id',
        'create_time',
    ];



}