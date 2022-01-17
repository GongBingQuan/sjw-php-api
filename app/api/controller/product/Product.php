<?php

namespace app\api\controller\product;

use app\api\controller\order\OrderApply;
use app\api\model\product\Product as ProductModel;
use app\api\model\order\Cart as CartModel;
use app\api\controller\Controller;
use app\api\model\product\SignInLog;
use app\api\model\settings\Setting as SettingModel;
use app\api\service\common\RecommendService;
use app\common\service\qrcode\ProductService;
use app\common\model\user\Favorite as FavoriteModel;
/**
 * 商品控制器
 */
class Product extends Controller
{
    /**
     * 商品列表
     */
    public function lists()
    {
        // 整理请求的参数
        $param = array_merge($this->postData(), [
            'product_status' => 10
        ]);

        // 获取列表数据
        $model = new ProductModel;
        $list = $model->getList($param, $this->getUser(false));
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 商品列表
     */
    public function mylists()
    {
        $user = $this->getUser();
        // 整理请求的参数
        $param = array_merge($this->postData(), [
            'type' => 'all',
            'apply_phone'=>$user['mobile']
        ]);
        // 获取列表数据
        $model = new ProductModel;
        $list = $model->getList($param,$user );
        return $this->renderSuccess('', compact('list'));
    }
    /**
     * 推荐产品
     */
    public function recommendProduct($location)
    {
        $recommend = SettingModel::getItem('recommend');
        $model = new ProductModel;
        $is_recommend = RecommendService::checkRecommend($recommend, $location);
        $list = [];
        if($is_recommend){
            $list = $model->getRecommendProduct($recommend);
        }
        return $this->renderSuccess('', compact('list', 'recommend', 'is_recommend'));
    }

    /**
     * 获取商品详情
     */
    public function detail($product_id, $url = '')
    {
        // 用户信息
        $user = $this->getUser(false);
        // 商品详情
        $model = new ProductModel;
        $product = $model->getDetails($product_id, $this->getUser(false));
        if ($product === false) {
            return $this->renderError($model->getError() ?: '商品信息不存在');
        }
        // 多规格商品sku信息
        $specData = $product['spec_type'] == 20 ? $model->getManySpecData($product['spec_rel'], $product['sku']) : null;

        return $this->renderSuccess('', [
            // 商品详情
            'detail' => $product,
            // 购物车商品总数量
            'cart_total_num' => $user ? (new CartModel($user))->getProductNum() : 0,
            // 多规格商品sku信息
            'specData' => $specData,
            // 是否收藏
            'is_fav' => $user ? FavoriteModel::isFav($product_id, $user['user_id']) : false,
            // 微信公众号分享参数
            'share' => $this->getShareParams($url, $product['product_name'], $product['product_name'], '/pages/product/detail/detail', $product['image'][0]['file_path']),
        ]);
    }

    /**
     * 生成商品海报
     */
    public function poster($product_id, $source)
    {
        // 商品详情
        $detail = ProductModel::detail($product_id);
        $Qrcode = new ProductService($detail, $this->getUser(false), $source);
        return $this->renderSuccess('', [
            'qrcode' => $Qrcode->getImage(),
        ]);
    }
    /**
     * 获取签到列表
     */
    public  function  getSignList($product_id){
        $model = new ProductModel;
        $list= $model->getSite($product_id);
        return $this->renderSuccess('', $list);
    }



    /**
     * 设置签到时间
     */
    public  function setSiteTime($site_id , $time){
        $model = new ProductModel;
        $res = $model->setSite($site_id,$time);
        if($res){
            return $this->renderSuccess("操作成功");
        }
        return  $this->renderError('操作失败');
    }

    /**
     * 打卡日志
     */
    public  function  getSignlog($site_id){
        $model = new ProductModel;
        $list = $model->getSignlog($site_id);
        return $this->renderSuccess('', $list);
    }
    /**
     * 签到打卡
     */
    public function sign($code,$site_id){
        $user= $this->getUser();
        $model = new ProductModel;
        $res = $model->sign($code,$user['user_id'],$site_id);
        if($res){
            return $this->renderSuccess("操作成功");
        }
        return  $this->renderError('操作失败');

    }

}