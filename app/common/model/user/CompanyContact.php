<?php
declare (strict_types = 1);

namespace app\common\model\user;

use think\Model;

/**
 * @mixin \think\Model
 */
class CompanyContact extends Model
{
    protected $pk = 'company_contact_id';
    protected $name = 'company_contact';
    public function searchNameAttr($query, $value, $data)
    {
        $query->whereOr('name','like', $value . '%');
    }

    public function searchPhoneAttr($query, $value, $data)
    {
        $query->where('phone','like', $value . '%');
    }

    public function company()
    {
        return $this->hasOne(Company::class,'company_id','company_id');
    }
}
