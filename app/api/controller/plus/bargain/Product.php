<?php

namespace app\api\controller\plus\bargain;

use app\api\controller\Controller;
use app\api\model\settings\Setting as SettingModel;
use app\common\service\product\BaseProductService;
use app\api\model\plus\bargain\Active as ActiveModel;
use app\api\model\plus\bargain\Product as ProductModel;
/**
 * 砍价商品控制器
 */
class Product extends Controller
{
    /**
     * 砍价活动
     */
    public function active()
    {
        $model = new ActiveModel();
        $list = $model->activityList();
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 砍价商品
     */
    public function product($bargain_activity_id)
    {
        $detail = ActiveModel::detailWithTrans($bargain_activity_id);
        $list = (new ProductModel())->getActivityList($bargain_activity_id);
        return $this->renderSuccess('', compact('detail','list'));
    }

    /**
     * 砍价商品详情
     */
    public function detail($bargain_product_id, $url = '')
    {
        $model = new ProductModel();
        //详情
        $detail = $model->getBargainDetail($bargain_product_id);
        //活动
        $active = ActiveModel::detailWithTrans($detail['bargain_activity_id']);
        //规格
        $specData = BaseProductService::getSpecData($detail['product']);
        // 砍价规则
        $setting = SettingModel::getBargain();
        // 微信公众号分享参数
        $share = $this->getShareParams($url, $detail['product']['product_name'], $detail['product']['product_name'], '/pages/plus/assemble/detail/detail', $detail['product']['image'][0]['file_path']);
        return $this->renderSuccess('', compact('detail', 'active', 'specData', 'setting', 'share'));
    }
}