<?php

namespace app\api\controller\plus\package;


use app\api\controller\Controller;
use app\api\model\plus\giftpackage\GiftPackage as GiftPackageModel;
use app\api\model\plus\giftpackage\Order as OrderModel;
use app\api\service\pay\PayService;
use app\common\enum\order\OrderTypeEnum;

/**
 * 礼包购控制器
 */
class Package extends Controller
{
    /**
     * 获取数据
     */
    public function index($package_id)
    {   
        // 用户信息
        $user = $this->getUser(false);
        $params = $this->request->param();
        $model = new GiftPackageModel();
        $data = $model->getGiftPackage($package_id,$params,$user);
        return $this->renderSuccess('', compact('data'));
    }

    /**
     * 礼包购
     */
    public function buy($package_id)
    {
        // 用户信息
        $user = $this->getUser();
        $params = $this->request->param();
        if($this->request->isGet()){
            $model = new GiftPackageModel();
            $data = $model->checkGiftPackage($package_id,$params,$user);
            // 是否开启支付宝支付
            $show_alipay = PayService::isAlipayOpen($params['pay_source'], $user['app_id']);
            if($data){
               return $this->renderSuccess('', compact('data', 'show_alipay'));
           }else{
                return $this->renderError($model->getError() ?: '购买失败');
           }
        }
        // 生成礼品订单
        $model = new OrderModel;
        // 创建订单
        if (!$model->createOrder($user, $package_id, $params)) {
            return $this->renderError($model->getError() ?: '订单创建失败');
        }
        // 构建支付请求
        $payment = OrderModel::onOrderPayment($user, $model, $params['pay_type'], $params['pay_source']);
        // 返回结算信息
        return $this->renderSuccess(['success' => '支付成功', 'error' => '订单未支付'], [
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式,仅支持微信
            'payment' => $payment,               // 微信支付参数
            'order_type' => OrderTypeEnum::GIFT, //订单类型
        ]);
    }
}