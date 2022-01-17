<?php

namespace app\common\model\plus\giftpackage;

use app\common\model\BaseModel;

/**
 * 礼包购码模型
 */
class Code extends BaseModel
{
    protected $name = 'gift_code';
    protected $pk = 'gift_code_id';

    public static function detail($gift_package_id){
        return (new static())->where('gift_package_id', '=', $gift_package_id)->find();
    }

    public static function getList($id){
        return (new static())->where('gift_package_id', '=', $id)->select();
    }
}