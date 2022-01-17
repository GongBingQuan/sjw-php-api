<?php

namespace app\api\controller\order;

use app\api\controller\Controller;
use app\api\model\order\OrderApply as OrderApplyModel;

/**
 * 订单控制器
 */
class OrderApply extends Controller
{

    public  function  getList(){
        $user = $this->getUser();
        $product_id = input('get.product_id',0);
        $list = OrderApplyModel::where('phone',$user['mobile'])
            ->where('product_id',$product_id)
            ->select();
        return $this->renderSuccess('',$list);
    }

    /**
     * 修改报名人信息
     */
    public function updateApply()
    {
        $model = OrderApplyModel::update(input('post.'));
        if ($model) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    /**
     * 批量修改报名人信息
     */
    public function updateAllApply()
    {
        $list =json_decode(input('post.list'),true);
        $model = (new OrderApplyModel)->updateAll($list);
        if ($model) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }


}