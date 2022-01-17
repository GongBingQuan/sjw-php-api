<?php

namespace app\api\controller\plus\package;

use app\api\controller\Controller;
use app\api\model\plus\giftpackage\Order as OrderModel;

/**
 *
 * 礼包购订单控制器
 *
 */
class Order extends Controller
{
    /**
     * 购买记录
     */
    public function orderlist()
    {   
        // 用户信息
        $user = $this->getUser();
        $data = $this->postData();
        $model = new OrderModel();
        $list = $model->getList($user,$data);
        return $this->renderSuccess('', compact('list'));
    }
    /**
     * 记录详情
     */
    public function orderdetail($order_no)
    {   
        $model = new OrderModel();
        $detail = $model->orderDetail($order_no);
        return $this->renderSuccess('', compact('detail'));
    }
}