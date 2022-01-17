<?php

namespace app\shop\model\product;

use app\common\model\product\ProductSite as SiteModel;

/**
 * 打卡列表模型
 */
class ProductSite extends SiteModel
{
    /**
     * 根据规格组名称查询规格id
     */
    public function removeAll($product_id)
    {
        return self::where('product_id','=', $product_id)->delete();
    }

    public function getList($product_id){
        return self::where("product_id",$product_id)->select();
    }
}
