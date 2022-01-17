<?php

namespace app\api\model\plus\giftpackage;

use app\common\model\plus\giftpackage\GiftPackage as GiftPackageModel;
use app\api\model\plus\coupon\Coupon;
use app\api\model\product\Product;
use app\common\enum\settings\DeliveryTypeEnum;
use app\common\model\store\Store as StoreModel;
use app\common\model\settings\Setting as SettingModel;
use app\api\model\plus\giftpackage\Order as OrderModel;

/**
 * 礼包购模型
 */
class GiftPackage extends GiftPackageModel
{

    /**
     * 获取数据
     */
    public function getGiftPackage($id, $params, $user = false)
    {
        $data = self::detail($id);
        $data = $data->toArray();
        if ($data['is_coupon'] == '1') {
            $data['is_coupon'] = true;
        }
        if ($data['is_point'] == '1') {
            $data['is_point'] = true;
        }

        $this->buildData($data);
        return $data;
    }

    /**
     * 获取数据
     */
    public function checkGiftPackage($id, $params, $user)
    {
        $data = self::detail($id);
        $data = $data->toArray();
        $data['value1'][] = $data['start_time']['text'];
        $data['value1'][] = $data['end_time']['text'];
        $data['grade_ids'] = explode(',', $data['grade_ids']);
        if ($data['status'] == 1) {
            $this->error = '活动已终止';
            return false;
        }
        if ($data['is_coupon'] == '1') {
            $data['is_coupon'] = true;
        }
        if ($data['is_point'] == '1') {
            $data['is_point'] = true;
        }
        if ($data['start_time']['value'] > time()) {
            $this->error = '活动还未开始';
            return false;
        }
        if ($data['end_time']['value'] < time()) {
            $this->error = '活动已结束';
            return false;
        }
        if ($data['is_times'] == 1) {
            $where = [
                'user_id' => $user['user_id'],
                'pay_status' => 20,
                'gift_package_id' => $id,
            ];
            //统计购买数量
            $count = (new OrderModel())->where($where)->count('order_id');
            //判断购买次数
            if ($count >= $data['times']) {
                $this->error = '已超过限购数量';
                return false;
            }
        }
        //二维码类型10，一码，20，不同码
        if ($data['code_type'] == 10) {
            //统计已购买数量
            $totalcount = (new OrderModel())->where('gift_package_id', '=', $id)
                ->where('pay_status', '=', 20)
                ->where('order_status', '<>', 20)
                ->count();
            if ($totalcount >= $data['total_num']) {
                $this->error = '礼包数量不足';
                return false;
            }
            else if($data['code_type'] == 20){
                //查询码是否使用
                $code = $this->where('gift_package_id', '=', $id)
                    ->where('code', '=', $params['code'])
                    ->count();
                if ($code > 0) {
                    $this->error = '优惠码已使用';
                    return false;
                }
            }
        }
        //判断是否符合等级
        if ($data['is_grade'] == 1 && !in_array($user['grade_id'], $data['grade_ids'])) {
            $this->error = '会员等级不符合';
            return false;
        }
        $this->buildData($data);
        // 设置默认配送方式
        if (!$params['delivery_type']) {
            $params['delivery_type'] = current(SettingModel::getItem('store')['delivery_type']);
        }
        $data['delivery_type'] = $params['delivery_type'];
        // 处理配送方式
        if ($params['delivery_type'] == DeliveryTypeEnum::EXPRESS && $user) {
            $data['address'] = $user['address_default'];
            $data['exist_address'] = $user['address_id'] > 0;
        } elseif ($params['delivery_type'] == DeliveryTypeEnum::EXTRACT && $params['store_id'] > 0 && $user) {
            $data['extract_store'] = StoreModel::detail($params['store_id']);
        }
        return $data;
    }

    private function buildData(&$data){
        if ($data['coupon_ids'] != '') {
            $model = new Coupon();
            $coupon = json_decode($data['coupon_ids'], true);
            foreach ($coupon as $key => &$value) {
                $couponInfo = $model->getCouponInfo($value['coupon_id']);
                $value['name'] = $couponInfo['name'];
                $value['reduce_price'] = $couponInfo['reduce_price'];
                $value['expire_type'] = $couponInfo['expire_type'];
                $value['expire_day'] = $couponInfo['expire_day'];
                $value['start_time'] = $couponInfo['start_time'];
                $value['end_time'] = $couponInfo['end_time'];
            }
            $data['coupon_list'] = $coupon;
            $data['coupon'] = explode(',', $data['coupon_ids']);
        }
        if ($data['product_ids'] != '') {
            $ProductModel = new Product();
            $product = $ProductModel->getProduct($data['product_ids']);
            $data['product_list'] = $product->toArray();
            $data['product'] = explode(',', $data['product_ids']);
        }
    }
}