<?php

namespace app\api\controller\plus\points;

use app\api\controller\Controller;
use app\api\model\plus\points\Product as ProductModel;
use app\common\service\product\BaseProductService;

/**
 * 积分商城控制器
 */
class Product extends Controller
{
    /**
     *积分商品列表
     */
    public function index()
    {
        $model = new ProductModel();
        $list = $model->getList($this->request->param());
        $points = $this->getUser()['points'];
        return $this->renderSuccess('', compact('list', 'points'));
    }

    /**
     *积分商品列表
     */
    public function detail($point_product_id, $url = '')
    {
        $detail = (new ProductModel())->getPointDetail($point_product_id);
        //规格
        $specData = BaseProductService::getSpecData($detail['product']);
        // 微信公众号分享参数
        $share = $this->getShareParams($url, $detail['product']['product_name'], $detail['product']['product_name'], '/pages/plus/assemble/detail/detail', $detail['product']['image'][0]['file_path']);
        return $this->renderSuccess('', compact('detail', 'specData', 'share'));
    }
}