<?php

namespace app\common\model\product;

use app\common\model\BaseModel;
/**
 * 活动打卡模型
 */
class SignInLog extends BaseModel
{
    protected $name = 'sign_in_log';
    protected $pk = 'sign_in_log_id';

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->hasOne('app\\common\\model\\user\\User', 'user_id', 'user_id');
    }

    /**
     * 关联产品
     */
    public function product()
    {
        return $this->hasOne('app\\common\\model\\product\\Product', 'product_id', 'product_id');
    }
    /**
     * 关联产品打卡表
     */
    public function site()
    {
        return $this->hasOne('app\\common\\model\\product\\ProductSite', 'product_site_id', 'product_site_id');
    }
    /**
     * 关联报名信息
     */
    public function apply(){
        return $this->hasOne('app\\common\\model\\order\\OrderApply', 'order_apply_id', 'apply_id');
    }

}
