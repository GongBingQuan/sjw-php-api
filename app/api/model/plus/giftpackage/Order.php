<?php

namespace app\api\model\plus\giftpackage;

use app\api\service\order\PaymentService;
use app\api\service\order\paysuccess\type\GiftPaySuccessService;
use app\common\enum\order\OrderPayTypeEnum;
use app\common\enum\order\OrderTypeEnum;
use app\common\enum\settings\DeliveryTypeEnum;
use app\common\exception\BaseException;
use app\common\model\plus\giftpackage\Order as OrderModel;
use app\common\model\plus\giftpackage\GiftPackage as GiftPackageModel;
use app\api\model\order\OrderAddress as OrderAddress;
use app\api\model\order\Order as OrdersModel;
use app\api\service\user\UserService;
use app\api\model\order\OrderExtract as OrderExtractModel;
use app\api\model\product\Product as ProductModel;
use app\api\model\order\OrderProduct;
use app\api\model\plus\coupon\Coupon;
use app\api\model\product\Product;
use app\common\model\plus\giftpackage\Code as GiftCodeModel;
/**
 * 礼包购模型
 */
class Order extends OrderModel
{

    /**
     * 创建礼包购订单
     * 返回订单id
     */
    public function createOrder($user, $gift_package_id, $params)
    {
        $detail = GiftPackageModel::detail($gift_package_id);
        if ($detail['status'] == 1) {
            $this->error = '活动已终止';
            return false;
        }
        if ($detail['start_time']['value'] > time()) {
            $this->error = '活动还未开始';
            return false;
        }
        if ($detail['end_time']['value'] < time()) {
            $this->error = '活动已结束';
            return false;
        }
        if ($detail['is_times'] == 1) {
            $where = [
                'user_id' => $user['user_id'],
                'pay_status' => 20,
                'gift_package_id' => $gift_package_id,
            ];
            //统计购买数量
            $count = $this->where($where)->count('order_id');
            //判断购买次数
            if ($count >= $detail['times']) {
                $this->error = '已超过限购数量';
                return false;
            }
        }
        //二维码类型10，一码，20，不同码
        switch ($detail['code_type']) {
            case '10':
                //统计已购买数量
                $totalcount = $this->where('gift_package_id', '=', $gift_package_id)
                    ->where('pay_status', '=', 20)
                    ->where('order_status', '<>', 20)
                    ->count();
                if ($totalcount >= $detail['total_num']) {
                    $this->error = '礼包数量不足';
                    return false;
                }
                break;
            case '20':
                //查询码是否使用
                $code = $this->where('gift_package_id', '=', $gift_package_id)
                    ->where('code', '=', $params['code'])
                    ->count();
                if ($code > 0) {
                    $this->error = '优惠码已使用';
                    return false;
                }
                break;
        }
        //选购商品数量
        $product_num = count(json_decode($params['product_ids'], true));
        if ($product_num > $detail['product_num']) {
            $this->error = '商品选购数量超过最大值' . $product_num;
            return false;
        }
        //判断是否符合等级
        if ($detail['is_grade'] == 1 && !in_array($user['grade_id'], explode(',', $detail['grade_ids']))) {
            return false;
        }
        if ($params['delivery_type'] == DeliveryTypeEnum::EXPRESS) {
            if (empty($params['address']) || $params['address'] == 'null') {
                $this->error = '请先选择收货地址';
                return false;
            }
        }
        if ($params['delivery_type'] == DeliveryTypeEnum::EXTRACT) {
            $extract = json_decode($params['extract'], true);
            if ($extract['store_id'] == 0) {
                $this->error = '请先选择自提门店';
                return false;
            }
            if (empty($extract['linkman']) || empty($extract['phone'])) {
                $this->error = '请填写联系人和电话';
                return false;
            }
        }
        $code = isset($params['code']) ? $params['code'] : '';
        // 如果是通码
        if($detail['code_type'] == 10){
            $code = GiftCodeModel::detail($gift_package_id)['code'];
        }
        //写入order表
        $status = $this->save([
            'gift_package_id' => $gift_package_id,
            'order_no' => $this->orderNo(),
            'total_price' => $detail['money'],
            'order_price' => $detail['money'],
            'pay_price' => $detail['money'],
            'user_id' => $user['user_id'],
            'app_id' => self::$app_id,
            'product_ids' => $params['product_ids'],
            'delivery_type' => $params['delivery_type'],
            'address' => isset($params['address']) ? $params['address'] : '',
            'extract' => isset($params['extract']) ? $params['extract'] : '',
            'coupon_ids' => $detail['coupon_ids'],
            'point' => $detail['point'],
            'code' => $code,
        ]);
        // 余额支付标记订单已支付
        if ($status && $params['pay_type'] == OrderPayTypeEnum::BALANCE) {
            if ($user['balance'] < $detail['money']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
            $this->onPaymentByBalance($this['order_no']);
        }

        return $status;
    }

    /**
     * 余额支付标记订单已支付
     */
    public function onPaymentByBalance($orderNo)
    {
        // 获取订单详情
        $PaySuccess = new GiftPaySuccessService($orderNo);
        // 发起余额支付
        return $PaySuccess->onPaySuccess(OrderPayTypeEnum::BALANCE);
        return $status;
    }

    /**
     * 待支付订单详情
     */
    public static function getPayDetail($orderNo)
    {
        $model = new static();
        return $model->where(['order_no' => $orderNo, 'pay_status' => 10, 'is_delete' => 0])->with(['user'])->find();
    }

    /**
     * 构建支付请求的参数
     */
    public static function onOrderPayment($user, $order, $payType, $pay_source)
    {
        if ($payType == OrderPayTypeEnum::WECHAT) {
            return self::onPaymentByWechat($user, $order, $pay_source);
        }
        return [];
    }

    /**
     * 构建微信支付请求
     */
    protected static function onPaymentByWechat($user, $order, $pay_source)
    {
        return PaymentService::wechat(
            $user,
            $order['order_id'],
            $order['order_no'],
            $order['pay_price'],
            OrderTypeEnum::GIFT,
            $pay_source
        );
    }

    //支付完成添加订单
    public function addOrder($product_ids, $order_no, $app_id = null)
    {
        is_null($app_id) && $app_id = self::$app_id;
        $order = $this->where(['order_no' => $order_no])->find();
        $extract = json_decode($order['extract'], true);
        $productArray = json_decode($product_ids, true);
        foreach ($productArray as $key => $value) {
            // 商品详情
            $product = ProductModel::detail($value['product_id']);
            // 商品sku信息
            $product['product_sku'] = ProductModel::getProductSku($product, $value['product_sku_id']);
            // 订单数据
            $data = [
                'user_id' => $order['user_id'],
                'order_no' => $this->orderNo(),
                'total_price' => $product['spec_type'] == 10 ? $product['product_price'] : $product['product_sku']['product_price'],
                'order_price' => $product['spec_type'] == 10 ? $product['product_price'] : $product['product_sku']['product_price'],
                'coupon_id' => 0,
                'coupon_money' => 0,
                'points_money' => 0,
                'points_num' => 0,
                'pay_price' => 0,
                'delivery_type' => $order['delivery_type'],
                'pay_type' => 20,
                'buyer_remark' => '',
                'order_source' => 60,
                'activity_id' => $order['gift_package_id'],
                'points_bonus' => 0,
                'is_agent' => 0,
                'app_id' => $app_id,
                'pay_status' => 20,
                'pay_time' => time(),
                'pay_source' => 'wx',
                'extract_store_id' => $order['delivery_type'] == 20 ? $extract['store_id'] : 0,
            ];
            // 保存订单记录
            $OrdersModel = new OrdersModel;
            $OrdersModel->save($data);
            $new_order_id = $OrdersModel->order_id;
            if ($order['delivery_type'] == 10) {
                $address = json_decode($order['address'], true);
                // 记录收货地址
                $this->saveOrderAddress($address, $new_order_id, $order['user_id'], $app_id);
            } elseif ($order['delivery_type'] == 20) {
                // 记录自提信息
                $this->saveOrderExtract($extract, $new_order_id, $order['user_id'], $app_id);
            }
            //添加商品
            $this->saveOrderProduct($order, $new_order_id, $product, $app_id);
        }
    }

    /**
     * 保存订单商品信息
     */
    private function saveOrderProduct($order, $status, $product, $app_id = null)
    {
        is_null($app_id) && $app_id = self::$app_id;
        // 订单商品列表
        $goods = [
            'order_id' => $status,
            'user_id' => $order['user_id'],
            'app_id' => $app_id,
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'image_id' => $product['image'][0]['image_id'],
            'deduct_stock_type' => $product['deduct_stock_type'],
            'spec_type' => $product['spec_type'],
            'spec_sku_id' => $product['product_sku']['spec_sku_id'],
            'product_sku_id' => $product['product_sku']['product_sku_id'],
            'product_attr' => $product['product_sku']['product_attr'],
            'content' => $product['content'],
            'product_no' => $product['product_sku']['product_no'],
            'product_price' => $product['product_sku']['product_price'],
            'line_price' => $product['product_sku']['line_price'],
            'product_weight' => $product['product_sku']['product_weight'],
            'is_user_grade' => (int)$product['is_user_grade'],
            'grade_ratio' => $product['grade_ratio'] ? $product['grade_ratio'] : 0,
            'grade_product_price' => isset($product['grade_product_price']) ? $product['grade_product_price'] : 0,
            'grade_total_money' => 0,
            'coupon_money' => 0,
            'points_money' => isset($product['points_money']) ? $product['points_money'] : 0,
            'points_num' => isset($product['points_num']) ? $product['points_num'] : 0,
            'points_bonus' => 0,
            'total_num' => 1,
            'total_price' => $product['spec_type'] == 10 ? $product['product_price'] : $product['product_sku']['product_price'],
            'total_pay_price' => 0,
            'is_ind_agent' => $product['is_ind_agent'],
            'agent_money_type' => $product['agent_money_type'],
            'first_money' => 0,
            'second_money' => 0,
            'third_money' => 0,
            'fullreduce_money' => 0,
        ];
        // 记录订单商品来源id
        $goods['product_source_id'] = isset($product['product_source_id']) ? $product['product_source_id'] : 0;
        // 记录订单商品sku来源id
        $goods['sku_source_id'] = isset($product['sku_source_id']) ? $product['sku_source_id'] : 0;
        // 记录拼团类的商品来源id
        $goods['bill_source_id'] = isset($product['bill_source_id']) ? $product['bill_source_id'] : 0;

        $model = new OrderProduct();
        return $model->save($goods);
    }

    /**
     * 记录收货地址
     */
    private function saveOrderAddress($address, $order_id, $user_id, $app_id = null)
    {
        $model = new OrderAddress();
        is_null($app_id) && $app_id = self::$app_id;
        return $model->save([
            'order_id' => $order_id,
            'user_id' => $user_id,
            'app_id' => $app_id,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'region_id' => $address['region_id'],
            'detail' => $address['detail'],
        ]);
    }

    /**
     * 保存上门自提联系人
     */
    private function saveOrderExtract($extract, $order_id, $user_id, $app_id = null)
    {
        $OrderExtract = new OrderExtractModel;
        // 记忆上门自提联系人(缓存)，用于下次自动填写
        UserService::setLastExtract($user_id, trim($extract['linkman']), trim($extract['phone']));
        is_null($app_id) && $app_id = self::$app_id;
        // 保存上门自提联系人(数据库)
        return $OrderExtract->save([
            'order_id' => $order_id,
            'linkman' => trim($extract['linkman']),
            'phone' => trim($extract['phone']),
            'user_id' => $user_id,
            'app_id' => $app_id,
        ]);
    }

    //购买列表
    public function getList($user, $data)
    {
        $list = $this->with(['gift'])
            ->field('order_no,pay_price,pay_type,pay_time,coupon_ids,product_ids,gift_package_id,point')
            ->where('user_id', '=', $user['user_id'])
            ->where('pay_status', '=', 20)
            ->order('create_time', 'desc')
            ->paginate($data);
        foreach ($list as $key => &$value) {
            $value['product_num'] = count(json_decode($value['product_ids']));
            $coupon_num = 0;
            if ($value['coupon_ids']) {
                $coupon_ids = json_decode($value['coupon_ids'], true);
                foreach ($coupon_ids as $k => $v) {
                    $coupon_num += $v['coupon_num'];
                }
            }
            $value['coupon_num'] = $coupon_num;
        }
        return $list;
    }

    //订单详情
    public function orderDetail($order_no)
    {
        $detail = $this->where('order_no', '=', $order_no)->find();
        if ($detail['coupon_ids']) {
            $Coupon = new Coupon();
            $coupon = json_decode($detail['coupon_ids'], true);
            foreach ($coupon as $key => &$value) {
                $couponInfo = $Coupon->getCouponInfo($value['coupon_id']);
                $value['name'] = $couponInfo['name'];
                $value['reduce_price'] = $couponInfo['reduce_price'];
                $value['expire_type'] = $couponInfo['expire_type'];
                $value['expire_day'] = $couponInfo['expire_day'];
                $value['start_time'] = $couponInfo['start_time'];
                $value['end_time'] = $couponInfo['end_time'];
            }
            $detail['coupon_list'] = $coupon;
        }
        if ($detail['product_ids']) {
            $ProductModel = new Product();
            $product = $ProductModel->getProductList($detail['product_ids']);
            $detail['product_list'] = $product->toArray();
        }
        $detail['address'] = $detail['address'] ? json_decode($detail['address'], true) : '';
        $detail['extract'] = $detail['extract'] ? json_decode($detail['extract'], true) : '';
        return $detail;
    }

    /**
     * 订单详情
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        $model = new static();
        $order = $model->where(['order_id' => $order_id, 'user_id' => $user_id])->find();
        if (empty($order)) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }
}