<?php



namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\user\Company as CompanyModel;

/**
 * 企业控制器
 */
class Company extends Controller
{
   function index($page,$list_rows){
       $company = new CompanyModel();
       $list = $company->getLastSql()->page($page,$list_rows)->select();
       $this->renderSuccess('',[
           "list"=>[
               "data"=>$list,
               "total"=>$company->count()
           ]
       ]);
   }

   function add(){

   }
    /**
     * 搜索
     */
    function all(){
        $company = new CompanyModel();
        $model = $company->field('company_id,company_name')
            ->whereNotNull("company_name")
            ->order('company_id desc');
        $list = $model->select();
        return $this->renderSuccess('',[
            "list"=>[
                "data"=>$list
            ]
        ]);
    }


}