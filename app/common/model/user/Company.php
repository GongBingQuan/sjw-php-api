<?php


namespace app\common\model\user;

use app\common\model\BaseModel;

/**
 * 用户模型
 */
class Company extends BaseModel
{
    protected $pk = 'company_id';
    protected $name = 'company';

    public function searchNameAttr($query, $value, $data)
    {
        $query->where('name','like', $value . '%');
    }
    public function searchCompanyNameAttr($query, $value, $data)
    {
        $query->where('company_name','like', $value . '%');
    }




}