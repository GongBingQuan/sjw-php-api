<?php
declare (strict_types = 1);

namespace app\common\model\user;

use think\Model;

/**
 * @mixin \think\Model
 */
class CompanyPoint extends Model
{
    protected $pk = 'company_point_id';
    protected $name = 'company_point';

    public function company()
    {
        return $this->hasOne(Company::class,'company_id','company_id');
    }
    public function contact()
    {
        return $this->hasOne(CompanyContact::class,'company_contact_id','company_contact_id');
    }
}
