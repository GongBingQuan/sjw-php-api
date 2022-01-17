<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
/**
 * 活动打卡模型
 */
class ProductSite extends BaseModel
{
    protected $name = 'product_site';
    protected $pk = 'site_id';

    /**
     * 打卡记录表
     * @return \think\model\relation\HasOne
     */
    public function siteLog()
    {
        return $this->hasOne('app\\common\\model\\product\\SignInLog','site_id','site_id');
    }
}
