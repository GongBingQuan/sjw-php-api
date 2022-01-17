<?php

namespace app\shop\controller\order;

use app\shop\controller\Controller;
use app\shop\model\order\OrderApply as OrderApplyModel;

/**
 * 订单控制器
 */
class OrderApply extends Controller
{



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


}