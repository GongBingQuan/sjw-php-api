<?php

namespace app\api\controller\plus\lottery;

use app\api\controller\Controller;
use app\api\model\plus\lottery\Order as OrderModel;
use app\api\model\product\Product as ProductModel;
use app\api\model\plus\lottery\Record as RecordModel;

/**
 *
 * 转盘商品订单控制器
 *
 */
class Order extends Controller
{
    /**
     * 记录详情
     */
    public function buy($record_id)
    {
        $detail = RecordModel::detail($record_id);
        $params = $this->request->param();
        $user = $this->getUser();
        $model = new OrderModel();
        if ($this->request->isGet()) {
            if ($detail['prize_type'] != 3) {
                return $this->renderError('礼品类型错误');
            }
            if ($detail['status'] == 1) {
                return $this->renderError('礼品已兑换');
            }
            $data = $model->getOrderData($params, $user);
            $data['product'] = (new ProductModel)->getProduct([$detail['award_id']]);
            return $this->renderSuccess('', compact('data'));
        }
        if ($model->addOrder($params, $this->getUser())) {
            return $this->renderSuccess('兑换成功');
        }
        return $this->renderError($model->getError() ?: '兑换失败');

    }
}