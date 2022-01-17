<?php

namespace app\api\model\plus\lottery;

use app\common\enum\settings\DeliveryTypeEnum;
use app\common\model\order\Order as OrderModel;
use app\api\model\order\OrderAddress as OrderAddress;
use app\api\model\order\Order as OrdersModel;
use app\api\service\user\UserService;
use app\api\model\order\OrderExtract as OrderExtractModel;
use app\api\model\product\Product as ProductModel;
use app\api\model\order\OrderProduct;
use app\api\model\product\Product;
use app\common\model\store\Store as StoreModel;
use app\common\model\settings\Setting as SettingModel;
use think\Exception;

/**
 * 转盘兑换订单模型
 */
class Order extends OrderModel
{
    //获取数据
    public function getOrderData($param, $user)
    {
        $data = [];
        if (!isset($param['delivery_type'])) {
            $params['delivery_type'] = current(SettingModel::getItem('store')['delivery_type']);
        }
        $data['delivery_type'] = $param['delivery_type'];
        //配送方式
        $deliveryType = SettingModel::getItem('store')['delivery_type'];
        $data['deliverySetting'] = $deliveryType;
        // 处理配送方式
        if ($param['delivery_type'] == DeliveryTypeEnum::EXPRESS && $user) {
            $data['address'] = $user['address_default'];
            $data['exist_address'] = $user['address_id'] > 0;
        } elseif ($param['delivery_type'] == DeliveryTypeEnum::EXTRACT && $param['store_id'] > 0 && $user) {
            $data['last_extract'] = UserService::getLastExtract($user['user_id']);
            $data['extract_store'] = StoreModel::detail($param['store_id']);
        }
        return $data;
    }

    //添加订单
    public function addOrder($param, $user)
    {
        // 开启事务
        $this->startTrans();
        try {
            $detail = (new Record)->detail($param['record_id']);
            $app_id = self::$app_id;
            $extract = json_decode($param['extract'], true);
            // 商品详情
            $product = ProductModel::detail($detail['award_id']);
            // 商品sku信息
            $product['product_sku'] = ProductModel::getProductSku($product, $param['product_sku_id']);
            // 订单数据
            $data = [
                'user_id' => $user['user_id'],
                'order_no' => $this->orderNo(),
                'total_price' => $product['spec_type'] == 10 ? $product['product_price'] : $product['product_sku']['product_price'],
                'order_price' => $product['spec_type'] == 10 ? $product['product_price'] : $product['product_sku']['product_price'],
                'coupon_id' => 0,
                'coupon_money' => 0,
                'points_money' => 0,
                'points_num' => 0,
                'pay_price' => 0,
                'delivery_type' => $param['delivery_type'],
                'pay_type' => 20,
                'buyer_remark' => '',
                'order_source' => 70,
                'activity_id' => $param['record_id'],
                'points_bonus' => 0,
                'is_agent' => 0,
                'app_id' => $app_id,
                'pay_status' => 20,
                'pay_time' => time(),
                'pay_source' => 'wx',
                'extract_store_id' => $param['delivery_type'] == 20 ? $extract['store_id'] : 0,
            ];
            // 保存订单记录
            $OrdersModel = new OrdersModel;
            $OrdersModel->save($data);
            $new_order_id = $OrdersModel->order_id;
            if ($param['delivery_type'] == 10) {
                // 记录收货地址
                $this->saveOrderAddress($user['address_default'], $new_order_id, $user['user_id'], $app_id);
            } elseif ($param['delivery_type'] == 20) {
                // 记录自提信息
                $this->saveOrderExtract($extract, $new_order_id, $user['user_id'], $app_id);
            }
            //添加商品
            $this->saveOrderProduct($user, $new_order_id, $product, $app_id);
            //更新为已兑换
            (new Record())->where('record_id', '=', $param['record_id'])->update(['status' => 1]);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 保存订单商品信息
     */
    private function saveOrderProduct($user, $status, $product, $app_id = null)
    {
        is_null($app_id) && $app_id = self::$app_id;
        // 订单商品列表
        $goods = [
            'order_id' => $status,
            'user_id' => $user['user_id'],
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
}