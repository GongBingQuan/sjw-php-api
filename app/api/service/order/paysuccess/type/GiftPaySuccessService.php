<?php

namespace app\api\service\order\paysuccess\type;

use app\api\model\plus\giftpackage\Order as OrderModel;
use app\api\model\user\User as UserModel;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\user\balanceLog\BalanceLogSceneEnum;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\service\BaseService;
use app\common\model\plus\giftpackage\GiftPackage;
use app\api\model\plus\coupon\UserCoupon;
/**
 * 礼包购订单支付成功后的回调
 */
class GiftPaySuccessService extends BaseService
{
    // 订单模型
    public $model;

    // 当前用户信息
    private $user;

    /**
     * 构造函数
     */
    public function __construct($orderNo)
    {
        // 实例化订单模型
        $this->model = OrderModel::getPayDetail($orderNo);
        // 获取用户信息
        $this->user = UserModel::detail($this->model['user_id']);
    }

    /**
     * 订单支付成功业务处理
     */
    public function onPaySuccess($payType, $payData = [])
    {
        if (empty($this->model)) {
            $this->error = '未找到该订单信息';
            return false;
        }
        // 更新付款状态
        return $this->updatePayStatus($payType, $payData);
    }

    /**
     * 更新付款状态
     */
    private function updatePayStatus($payType, $payData = [])
    {
        // 验证余额支付时用户余额是否满足
        if ($payType == OrderPayTypeEnum::BALANCE) {
            if ($this->user['balance'] < $this->model['pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        // 事务处理
        $this->model->transaction(function () use ($payType, $payData) {
            // 更新订单状态
            $this->updateOrderInfo($payType, $payData);
            // 累积用户总消费金额
            $this->user->setIncPayMoney($this->model['pay_price']);
            // 记录订单支付信息
            $this->updatePayInfo($payType);
            //赠送礼包
            $this->sendGift();
            //赠送商品
            $this->sendProduct();
            //更新购买人数；
            $this->updatePeople();
        });
        return true;
    }
    //更新购买任务
    private function updatePeople()
    {
        $gift = GiftPackage::detail($this->model['gift_package_id']);
        if (empty($gift)) {
            return false;
        }
        return (new GiftPackage())->where('gift_package_id','=',$this->model['gift_package_id'])->update(['people'=>$gift['people']+1]);
    }
    //赠送商品
    private function sendProduct(){
        $product_ids = $this->model['product_ids'];
        if($product_ids){
            $OrderModel = new OrderModel;
            $OrderModel->addOrder($product_ids,$this->model['order_no'], $this->model['app_id']);
        }
    }
    /**
     * 赠送礼包
     */
    private function sendGift()
    {
        $gift = GiftPackage::detail($this->model['gift_package_id']);
        if (empty($gift)) {
            return;
        }
        if ($gift['is_coupon'] == 1) {
            $user_coupon = new UserCoupon();
            $user_coupon->addNewUserCoupon($gift['coupon_ids'], $this->model['user_id'], $this->model['app_id']);
        }
        if ($gift['is_point'] == 1) {
            $this->user->setIncPoints($gift['point'], '礼包购赠送积分');
        }
    }

    /**
     * 更新订单记录
     */
    private function updateOrderInfo($payType, $payData)
    {
        $order = [
            'pay_type' => $payType,
            'pay_status' => 20,
            'pay_time' => time(),
        ];
        if ($payType == OrderPayTypeEnum::WECHAT) {
            $order['transaction_id'] = $payData['transaction_id'];
        }
        // 更新订单状态
        return $this->model->save($order);
    }

    /**
     * 记录订单支付信息
     */
    private function updatePayInfo($payType)
    {
        // 余额支付
        if ($payType == OrderPayTypeEnum::BALANCE) {
            // 更新用户余额
            $this->user->where('user_id', '=', $this->user['user_id'])
                ->dec('balance', $this->model['pay_price'])
                ->update();
            BalanceLogModel::add(BalanceLogSceneEnum::CONSUME, [
                'user_id' => $this->user['user_id'],
                'money' => -$this->model['pay_price'],
            ], ['order_no' => $this->model['order_no']]);
        }
    }
}