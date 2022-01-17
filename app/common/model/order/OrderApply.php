<?php

namespace app\common\model\order;

use app\common\model\product\Product;
use app\common\model\BaseModel;

/**
 * Class OrderAddress
 */
class OrderApply extends BaseModel
{
    protected $name = 'order_apply';
    protected $pk = 'order_apply_id';

    public function order()
    {
        return $this->hasOne(Order::class, 'order_id','order_id');
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'product_id','product_id');
    }
    /**
     * 打卡记录
     */
    public function signLog()
    {
        return $this->hasMany('app\\common\\model\\product\\SignInLog','apply_id','order_apply_id');
    }

    public function getActivity($userInfo){
          $apply = $this->with(['product','order'])
            ->where('phone',$userInfo['mobile'])
            ->page(1,11)
            ->select();
        $resultArr = array();

        foreach ($apply as $item)
        {
            if ($item['order']==null||$item['product']==null)
            {
                continue;
            }
            else
            {
                $resultArr[] =$item;
            }
        }
        return $resultArr;
    }

}