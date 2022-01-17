<?php

namespace app\shop\controller\order;

use app\api\model\settings\Message as MessageModel;
use app\api\service\order\settled\MasterOrderSettledService;
use app\api\service\pay\PayService;
use app\common\enum\order\OrderTypeEnum;
use app\shop\model\product\ProductSite;
use app\shop\controller\Controller;
use app\shop\model\order\Order as OrderModel;
use app\shop\model\store\Store as StoreModel;
use app\common\enum\settings\DeliveryTypeEnum;
use app\shop\model\settings\Express as ExpressModel;
use app\shop\model\store\Clerk as ShopClerkModel;
use app\shop\model\user\User;

/**
 * 订单控制器
 */
class Order extends Controller
{
    /**
     * 订单列表
     */
    public function index($dataType = 'all')
    {
        // 订单列表
        $model = new OrderModel();
        $list = $model->getList($dataType, $this->postData());
        $order_count = [
            'order_count' => [
                'payment' => $model->getCount('payment', $this->postData()),
                'delivery' => $model->getCount('delivery', $this->postData()),
                'received' => $model->getCount('received', $this->postData()),
            ],];
        // 自提门店列表
        $shop_list = StoreModel::getAllList();
        $ex_style = DeliveryTypeEnum::data();
        return $this->renderSuccess('', compact('list', 'ex_style', 'shop_list', 'order_count'));
    }

    /**
     * 添加订单
     */
    public function addOrder()
    {
        // 立即购买：获取订单商品列表
        $params = $this->request->param();
        $productList = \app\api\model\order\Order::getOrderProductListByNow($params);
        $userModel =new User();
        $user = $userModel->detail($params['user_id']);
        // 实例化订单service
        $orderService = new MasterOrderSettledService($user, $productList, $params);
        // 获取订单信息
        $orderInfo = $orderService->settlement(true);
        // 订单结算提交
        if ($orderService->hasError()) {
            return $this->renderError($orderService->getError());
        }
        // 创建订单
        $order_id = $orderService->createOrder($orderInfo);
        if(!$order_id){
            return $this->renderError($orderService->getError() ?: '订单创建失败');
        }
        // 返回结算信息
        return $this->renderSuccess("成功", [
            'order_id' => $order_id,   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式
            'order_type' => OrderTypeEnum::MASTER, //订单类型
        ]);
    }

    /**
     * 获取打卡详情
     */
    public function getOderApplySign($dataType = 'all'){
        $post =  $this->postData();
        $model = new OrderModel();
        $list =  $model->getOrderApply($dataType, $post);
        $site = (new ProductSite())->getList($post['product_id']);
//        foreach ($order as $key => &$value){
//            foreach ($value['apply'] as $index => &$item){
//                $item['sign_time'] =
//            }
//        }
        return $this->renderSuccess('', ["list"=>$list,"site"=>$site]);
    }
    /**
     * 订单详情
     */
    public function detail($order_id)
    {
        // 订单详情
        $detail = OrderModel::detail($order_id);
        if (isset($detail['pay_time']) && $detail['pay_time'] != '') {
            $detail['pay_time'] = date('Y-m-d H:i:s', $detail['pay_time']);
        }
        if (isset($detail['delivery_time']) && $detail['delivery_time'] != '') {
            $detail['delivery_time'] = date('Y-m-d H:i:s', $detail['delivery_time']);
        }
        // 物流公司列表
        $model = new ExpressModel();
        $expressList = $model->getAll();
        // 门店店员列表
        $shopClerkList = (new ShopClerkModel)->getClerk($detail['extract_store_id']);
        return $this->renderSuccess('', compact('detail', 'expressList', 'shopClerkList'));
    }

    /**
     * 确认发货
     */
    public function delivery($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->delivery($this->postData('param'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError('发货失败');
    }

    /**
     * 修改订单价格
     */
    public function updatePrice($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updatePrice($this->postData('order'))) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }
    /**
     * 修改订单归属
     */
    public function updateCompany($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updateCompany($this->postData())) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }
    /**
     * 修改订基础信息
     */
    public function updateOrderInfo($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->updateOrderInfo($this->postData())) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 取消订单
     */
    public function orderCancel($order_no)
    {
        // 订单信息
        $model = OrderModel::detail(['order_no' => $order_no]);
        if ($model->orderCancel($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }
    /**
     *  线下打款确认
     */
    public function remitPay($order_id)
    {
        // 订单信息
        $model = OrderModel::detail($order_id);
        if ($model->remitPay($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }
    /**
     * 虚拟商品发货
     */
    public function virtual($order_id)
    {
        // 订单信息
        $model = OrderModel::detail($order_id);
        if ($model->virtual($this->postData())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}