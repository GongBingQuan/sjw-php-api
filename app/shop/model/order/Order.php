<?php

namespace app\shop\model\order;

use app\common\model\order\Order as OrderModel;
use app\common\library\helper;
use app\common\enum\order\OrderTypeEnum;
use app\common\service\message\MessageService;
use app\common\service\order\OrderRefundService;
use app\common\enum\order\OrderPayStatusEnum;
use app\common\service\product\factory\ProductFactory;
use app\common\model\plus\coupon\UserCoupon as UserCouponModel;
use app\common\model\user\User as UserModel;
use app\common\enum\settings\DeliveryTypeEnum;
use app\shop\model\user\Company;
use app\shop\model\user\User;
use app\shop\service\order\ExportService;
use think\facade\Filesystem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\common\model\settings\Express as ExpressModel;
use app\common\service\order\OrderCompleteService;

/**
 * 订单模型
 */
class Order extends OrderModel
{
    /**
     * 订单列表
     */
    public function getList($dataType, $data = null)
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $data);
        // 获取数据列表
        return $model->alias('order')->with(['product' => ['image', 'refund'], 'user','company'])
            ->order(['order.create_time' => 'desc'])
            ->where($this->transferDataType($dataType))
            ->paginate($data);
    }
    /**
     * 订单列表
     */
    public function getOrderApply($dataType, $data = null)
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $data);
        // 获取数据列表
        $order = $model->alias('order')->with(['apply'=>['signLog']])
            ->order(['order.create_time' => 'desc'])
            ->where($this->transferDataType($dataType))
            ->paginate($data);
        return $order;
    }

    /**
     * 获取订单总数
     */
    public function getCount($dataType, $data)
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $data);
        // 获取数据列表
        return $model->alias('order')
            ->where($this->transferDataType($dataType))
            ->count();
    }

    /**
     * 订单列表(全部)
     */
    public function getListAll($dataType, $query = [])
    {
        $model = $this;
        // 检索查询条件
        $model = $model->setWhere($model, $query);
        // 获取数据列表
        return $model->with(['product.image', 'address', 'user', 'extract', 'extract_store'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->where($this->transferDataType($dataType))
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->select();
    }

    /**
     * 订单导出
     */
    public function exportList($dataType, $query)
    {
        // 获取订单列表
        $list = $this->getListAll($dataType, $query);
        // 导出excel文件
        (new Exportservice)->orderList($list);
    }

    /**
     * 设置检索查询条件
     */
    private function setWhere($model, $data)
    {
        //搜索订单号
        if (isset($data['order_no']) && $data['order_no'] != '') {
            $model = $model->where('order.order_no', 'like', '%' . trim($data['order_no']) . '%');
        }
        //搜索产品id
        if (isset($data['product_id']) && $data['product_id'] != '') {
            $orderIds = OrderProduct::where('product_id',$data['product_id'])->column('order_id');
            $model = $model-> where('order_id','in',$orderIds );
        }

        //搜索自提门店
        if (isset($data['store_id']) && $data['store_id'] != '') {
            $model = $model->where('order.extract_store_id', '=', $data['store_id']);
        }
        //搜索配送方式
        if (isset($data['style_id']) && $data['style_id'] != '') {
            $model = $model->where('order.delivery_type', '=', $data['style_id']);
        }
        //搜索时间段
        if (isset($data['create_time']) && $data['create_time'] != '') {
            $sta_time = array_shift($data['create_time']);
            $end_time = array_pop($data['create_time']);
            $model = $model->whereBetweenTime('order.create_time', $sta_time, $end_time);
        }
        return $model;
    }

    /**
     * 转义数据类型条件
     */
    private function transferDataType($dataType)
    {
        $filter = [];
        // 订单数据类型
        switch ($dataType) {
            case 'all':
                break;
            case 'payment';
                $filter['order.pay_status'] = OrderPayStatusEnum::PENDING;
                $filter['order.order_status'] = 10;
                break;
            case 'delivery';
                $filter['order.pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['order.delivery_status'] = 10;
                $filter['order.order_status'] = 10;
                break;
            case 'received';
                $filter['order.pay_status'] = OrderPayStatusEnum::SUCCESS;
                $filter['order.delivery_status'] = 20;
                $filter['order.receipt_status'] = 10;
                $filter['order.order_status'] = 10;
                break;
            case 'comment';
                $filter['order.is_comment'] = 0;
                $filter['order.order_status'] = 30;
                break;
            case 'six';
                $filter['order.is_comment'] = 1;
                $filter['order.order_status'] = 30;
                break;
        }
        return $filter;
    }

    /**
     * 确认发货(单独订单)
     */
    public function delivery($data)
    {
        // 转义为订单列表
        $orderList = [$this];
        // 验证订单是否满足发货条件
        if (!$this->verifyDelivery($orderList)) {
            return false;
        }
        // 整理更新的数据
        $updateList = [[
            'order_id' => $this['order_id'],
            'express_id' => $data['express_id'],
            'express_no' => $data['express_no']
        ]];
        // 更新订单发货状态
        if ($status = $this->updateToDelivery($updateList)) {
            // 获取已发货的订单
            $completed = self::detail($this['order_id'], ['user', 'address', 'product', 'express']);
            // 发送消息通知
            $this->sendDeliveryMessage([$completed]);
        }
        return $status;
    }

    /**
     * 确认发货后发送消息通知
     */
    private function sendDeliveryMessage($orderList)
    {
        // 实例化消息通知服务类
        $Service = new MessageService;
        foreach ($orderList as $item) {
            // 发送消息通知
            $Service->delivery($item, OrderTypeEnum::MASTER);
        }
        return true;
    }

    /**
     * 更新订单发货状态(批量)
     */
    private function updateToDelivery($orderList)
    {
        $data = [];
        foreach ($orderList as $item) {
            $data[] = [
                'data' => [
                    'express_no' => $item['express_no'],
                    'express_id' => $item['express_id'],
                    'delivery_status' => 20,
                    'delivery_time' => time(),
                ],
                'where' => [
                    'order_id' => $item['order_id']
                ],
            ];
        }
        return $this->updateAll($data);
    }

    /**
     * 验证订单是否满足发货条件
     */
    private function verifyDelivery($orderList)
    {
        foreach ($orderList as $order) {
            if (
                $order['pay_status']['value'] != 20
                || $order['delivery_type']['value'] != DeliveryTypeEnum::EXPRESS
                || $order['delivery_status']['value'] != 10
            ) {
                $this->error = "订单号[{$order['order_no']}] 不满足发货条件!";
                return false;
            }
        }
        return true;
    }

    /**
     * 修改订单价格
     */
    public function updatePrice($data)
    {
        if ($this['pay_status']['value'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        if ($this['order_source'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        // 实际付款金额
        $payPrice = bcadd($data['update_price'], $data['update_express_price'], 2);
        if ($payPrice <= 0) {
            $this->error = '订单实付款价格不能为0.00元';
            return false;
        }
        return $this->save([
                'order_no' => $this->orderNo(), // 修改订单号, 否则微信支付提示重复
                'order_price' => $data['update_price'],
                'pay_price' => $payPrice,
                'update_price' => helper::bcsub($data['update_price'], helper::bcsub($this['total_price'], $this['coupon_money'])),
                'express_price' => $data['update_express_price']
            ]) !== false;
    }

    /**
     * 修改订单归属
     */
    public function updateCompany($data)
    {
        return $this->save([
                'company_id' => $data['company_id']
            ]) !== false;
    }
    /**
     * 修改订单基础信息
     */
    public function updateOrderInfo($data)
    {
        return $this->save([
                'name' => $data['name']??'',
                'post' => $data['post']??'',
                'phone' => $data['phone']??'',
                'wx' => $data['wx']??'',
                'email' => $data['email']??'',
                'remark' => $data['remark']??'',
                'apply_name' => $data['apply_name']??'',
                'id_no' => $data['id_no']??'',
                'old_company' => $data['old_company']??'',
            ]) !== false;
    }

    /**
     * 审核：用户取消订单
     */
    public function confirmCancel($data)
    {
        // 判断订单是否有效
        if ($this['pay_status']['value'] != 20) {
            $this->error = '该订单不合法';
            return false;
        }
        // 订单取消事件
        return $this->transaction(function () use ($data) {
            if ($data['is_cancel'] == true) {
                // 执行退款操作
                (new OrderRefundService)->execute($this);
                // 回退商品库存
                ProductFactory::getFactory($this['order_source'])->backProductStock($this['product'], true);
                // 回退用户优惠券
                $this['coupon_id'] > 0 && UserCouponModel::setIsUse($this['coupon_id'], false);
                // 回退用户积分
                $user = UserModel::detail($this['user_id']);
                $describe = "订单取消：{$this['order_no']}";
                $this['points_num'] > 0 && $user->setIncPoints($this['points_num'], $describe);
            }
            // 更新订单状态
            return $this->save(['order_status' => $data['is_cancel'] ? 20 : 10]);
        });
    }

    /**
     * 获取已付款订单总数 (可指定某天)
     */
    public function getOrderData($startDate, $endDate, $type)
    {
        $model = $this;

        !is_null($startDate) && $model = $model->where('pay_time', '>=', strtotime($startDate));

        if (is_null($endDate)) {
            !is_null($startDate) && $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }

        $model = $model->where('is_delete', '=', 0)
            ->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20);


        if ($type == 'order_total') {
            // 订单数量
            return $model->count();
        } else if ($type == 'order_total_price') {
            // 订单总金额
            return $model->sum('pay_price');
        } else if ($type == 'order_user_total') {
            // 支付用户数
            return count($model->distinct(true)->column('user_id'));
        }
        return 0;
    }

    /**
     * 获取待处理订单
     */
    public function getReviewOrderTotal()
    {
        $filter['pay_status'] = OrderPayStatusEnum::SUCCESS;
        $filter['delivery_status'] = 10;
        $filter['order_status'] = 10;
        return $this->where($filter)->count();
    }

    /**
     * 获取某天的总销售额
     * 结束时间不传则查一天
     */
    public function getOrderTotalPrice($startDate = null, $endDate = null)
    {
        $model = $this;
        $model = $model->where('pay_time', '>=', strtotime($startDate));
        if (is_null($endDate)) {
            $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }
        return $model->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->where('is_delete', '=', 0)
            ->sum('pay_price');
    }

    /**
     * 获取某天的客单价
     * 结束时间不传则查一天
     */
    public function getOrderPerPrice($startDate = null, $endDate = null)
    {
        $model = $this;
        $model = $model->where('pay_time', '>=', strtotime($startDate));
        if (is_null($endDate)) {
            $model = $model->where('pay_time', '<', strtotime($startDate) + 86400);
        } else {
            $model = $model->where('pay_time', '<', strtotime($endDate) + 86400);
        }
        return $model->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->where('is_delete', '=', 0)
            ->avg('pay_price');
    }

    /**
     * 获取某天的下单用户数
     */
    public function getPayOrderUserTotal($day)
    {
        $startTime = strtotime($day);
        $userIds = $this->distinct(true)
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->where('is_delete', '=', 0)
            ->column('user_id');
        return count($userIds);
    }

    /**
     * 获取兑换记录
     * @param $param array
     * @return \think\Paginator
     */
    public function getExchange($param)
    {
        $model = $this;
        if (isset($param['order_status']) && $param['order_status'] > -1) {
            $model = $model->where('order.order_status', '=', $param['order_status']);
        }
        if (isset($param['nickName']) && !empty($param['nickName'])) {
            $model = $model->where('user.nickName', 'like', '%' . trim($param['nickName']) . '%');
        }

        return $model->with(['user'])->alias('order')
            ->join('user', 'user.user_id = order.user_id')
            ->where('order.order_source', '=', 20)
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->paginate($param);
    }

    /**
     * 批量发货
     */
    public function batchDelivery($fileInfo)
    {
        try {
            $saveName = Filesystem::disk('public')->putFile('', $fileInfo);
            $savePath = public_path() . "uploads/{$saveName}";
            //载入excel表格
            $inputFileType = IOFactory::identify($savePath); //传入Excel路径
            $reader = IOFactory::createReader($inputFileType);
            $PHPExcel = $reader->load($savePath);

            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
            // 遍历并记录订单信息
            $list = [];
            $orderList = [];
            foreach ($sheet->toArray() as $key => $val) {
                if ($key > 0) {
                    if ($val[19] && $val[20]) {
                        // 查找发货公司是否存在
                        $express = ExpressModel::findByName(trim($val[19]));
                        $order = self::detail(['order_no' => trim($val[0])], ['user', 'address', 'product', 'express']);
                        if ($express && $order) {
                            $list[] = [
                                'data' => [
                                    'express_no' => trim($val[20]),
                                    'express_id' => $express['express_id'],
                                    'delivery_status' => 20,
                                    'delivery_time' => time(),
                                ],
                                'where' => [
                                    'order_id' => $order['order_id']
                                ],
                            ];
                            array_push($orderList, $order);
                        }
                    }
                }
            }
            if (count($list) > 0) {
                $this->updateAll($list);
                // 发送消息通知
                $this->sendDeliveryMessage($orderList);
            }
            unlink($savePath);
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 取消订单
     */
    public function orderCancel($data)
    {
        // 判断订单是否有效
        if ($this['delivery_status']['value'] == 20 || $this['order_status']['value'] != 10 || $this['pay_status']['value'] != 20) {
            $this->error = "订单不允许取消";
            return false;
        }
        // 订单取消事件
        return $this->transaction(function () use ($data) {
            // 执行退款操作
            (new OrderRefundService)->execute($this);
            // 回退商品库存
            ProductFactory::getFactory($this['order_source'])->backProductStock($this['product'], true);
            // 回退用户优惠券
            $this['coupon_id'] > 0 && UserCouponModel::setIsUse($this['coupon_id'], false);
            // 回退用户积分
            $user = UserModel::detail($this['user_id']);
            $describe = "订单取消：{$this['order_no']}";
            $this['points_num'] > 0 && $user->setIncPoints($this['points_num'], $describe);
            $this->setPoints("dec");
            // 更新订单状态
            return $this->save(['order_status' => 20, 'cancel_remark' => $data['cancel_remark']]);
        });
    }
    /**
     * 线下打款确认
     */
    public function remitPay($data)
    {
        // 判断订单是否有效
        if ($this['pay_status']['value'] != 10) {
            $this->error = "订单已付款";
            return false;
        }

        // 订单支付操作
        return $this->transaction(function () use ($data) {
            // 因为是活动 所以支付后直接跳过发货环境
            $this->save(['pay_status' => 20,'pay_time' =>  time(), 'delivery_status' =>20]);
            //发放积分
            $this->setPoints();

            // 更新商品库存销量（订单付款后）
            return ProductFactory::getFactory($this['order_source'])->updateStockSales($this['product']);
        });
    }

    /**
     * 更新积分inc 为增加 dec为减少
     * @param $type inc | dec
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setPoints($type='inc'){
        // 发放积分
        //因为业务修改为统计积分，因此不新建企业，也不发放积分
//        $user = User::where('user_id',$this['user_id'])->find();
//        $company_id=Company::where('name', $user['company'])->value('company_id');
//        if(!$company_id){
//            $companyModel=Company::create(['name'=>$user['company'],'company_type'=>$user['company_type'],'app_id'=>$user['app_id']]);
//            $company_id = $companyModel->company_id;
//        }
//        if($type=="inc"){
//            Company::where('company_id', $company_id)->inc('total_points',$this['pay_price'])->update();
//            Company::where('company_id', $company_id)->inc('points',$this['pay_price'])->update();
//        }else{
//            Company::where('company_id', $company_id)->dec('total_points',$this['pay_price'])->update();
//            Company::where('company_id', $company_id)->dec('points',$this['pay_price'])->update();
//        }

    }

    /**
     * 确认发货（虚拟订单）
     * @param $extractClerkId
     * @return bool|mixed
     */
    public function virtual($data)
    {
        if (
            $this['pay_status']['value'] != 20
            || $this['delivery_type']['value'] != DeliveryTypeEnum::NO_EXPRESS
            || $this['delivery_status']['value'] == 20
            || in_array($this['order_status']['value'], [20, 21])
        ) {
            $this->error = '该订单不满足发货条件';
            return false;
        }
        return $this->transaction(function () use ($data) {
            // 更新订单状态：已发货、已收货
            $status = $this->save([
                'delivery_status' => 20,
                'delivery_time' => time(),
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30,
                'virtual_content' => $data['virtual_content'],
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::MASTER);
            $OrderCompleteService->complete([$this], $this['app_id']);
            return $status;
        });
    }
}