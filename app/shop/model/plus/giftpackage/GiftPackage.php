<?php

namespace app\shop\model\plus\giftpackage;

use app\common\model\plus\giftpackage\GiftPackage as GiftPackageModel;
use app\shop\model\plus\coupon\Coupon;
use app\shop\model\product\Product;
use app\shop\model\plus\giftpackage\Code as CodeModel;
/**
 * 礼包购模型
 */
class GiftPackage extends GiftPackageModel
{
    /**
     * 礼包列表
     * @param $data
     */
    public function getList($data)
    {
        $model = $this;
        //检索活动名称
        if ($data['search'] != '') {
            $model = $model->where('name', 'like', '%' . trim($data['search']) . '%');
        }
       return $model->where('is_delete', '=', 0)
            ->order('create_time', 'desc')
            ->paginate($data);
    }
    /**
     * 获取未结束的数据列表
     * 单码
     */
    public function getDatas()
    {
        return $this->where('status', '=', 0)
            ->where('code_type', '=', 10)
            ->where('end_time', '>', time())
            ->select();
    }
    /**
     * 获取数据
     * @param $id
     */
    public function getGiftPackage($id)
    {
        $data = $this->where('gift_package_id', '=', $id)->with(['image'])->find();
        $data = $data->toArray();
        $data['value1'][] = $data['start_time']['text'];
        $data['value1'][] = $data['end_time']['text'];
        $data['grade_ids'] = explode(',', $data['grade_ids']);
        if ($data['is_coupon'] == '1') {
            $data['is_coupon'] = true;
        }
        if ($data['is_product'] == '1') {
            $data['is_product'] = true;
        }
        if ($data['is_point'] == '1') {
            $data['is_point'] = true;
        }
        if ($data['coupon_ids'] != '') {
            $model = new Coupon();
            $coupon = json_decode($data['coupon_ids'],true);
            foreach ($coupon as $key => &$value) {
                $couponInfo = $model->getCouponInfo($value['coupon_id']);
                $value['name'] = $couponInfo['name'];
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
        return $data;
    }

    /**
     * 保存数据
     * @param $data
     */
    public function saveGift($data)
    {
        $this->startTrans();
        try {
            $this->buildData($data);
            $data['app_id'] = self::$app_id;
            // 保存主表
            $this->save($data);
            // 保存码
            (new CodeModel())->geneCode($this['gift_package_id'], $this['code_type'], $this['total_num']);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 修改
     * @param $value
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            $this->buildData($data);
            // 一批一码
            if($this['code_type'] == 10){
                $data['total_num'] = $this['total_num'] + $data['add_num'];
            }
            // 保存主表
            $this->save($data);
            // 保存码
            if($this['code_type'] == 20) {
                (new CodeModel())->geneCode($this['gift_package_id'], $this['code_type'], $this['add_num']);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 构造数据
     */
    private function buildData(&$data){
        // 优惠券
        if ($data['is_coupon'] == 'true') {
            $data['is_coupon'] = 1;
            $data['coupon_ids'] = $data['coupon'];
        } else {
            $data['is_coupon'] = 0;
            $data['coupon_ids'] = '';
        }
        // 商品
        if ($data['is_product'] == 'true') {
            $data['is_product'] = 1;
            $data['product_ids'] = implode(',', array_unique($data['product']));
        } else {
            $data['is_product'] = 0;
            $data['product_ids'] = '';
        }
        // 积分
        if ($data['is_point'] == 'true') {
            $data['is_point'] = 1;
        } else {
            $data['is_point'] = 0;
        }
        // 等级限制
        if ($data['is_grade'] == 1) {
            $data['grade_ids'] = implode(',', $data['grade_ids']);
        } else {
            $data['grade_ids'] = '';
        }
        // 购买次数
        if ($data['is_times'] == 0) {
            $data['times'] = 0;
        }
        $data['start_time'] = strtotime(array_shift($data['value1']));
        $data['end_time'] = strtotime(array_pop($data['value1']));
    }


    /**
     * 发布
     */
    public function send($id)
    {
        return $this->where('gift_package_id', '=', $id)->update(['status' => 0]);
    }

    /**
     * 终止
     */
    public function end($id)
    {
        return $this->where('gift_package_id', '=', $id)->update(['status' => 1]);
    }

    /**
     * 删除
     * @param $id
     */
    public function del($id)
    {
        return $this->where('gift_package_id', '=', $id)->update(['is_delete' => 1]);
    }
}