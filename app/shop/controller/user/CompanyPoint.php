<?php



namespace app\shop\controller\user;

use app\shop\controller\Controller;
use app\shop\model\user\CompanyPoint as CompanyPointModel;

/**
 * 企业数据
 */
class CompanyPoint extends Controller
{
   function index($page,$list_rows){
       $company = new CompanyPointModel();
       $list = $company->with(['company','contact'])->page($page,$list_rows)->order('company_point_id desc')->select();
       return $this->renderSuccess('',[
           "list"=>[
               "data"=>$list,
               "total"=>$company->count()
           ]
       ]);
   }

    /**
     * 搜索
     */
    function search($keyword){
        $company = new CompanyPointModel();
        $model = $company->field('name,company_point_id,company_name')
            ->whereNotNull("company_name")
            ->order('company_id desc')
            ->limit(50);
        if($keyword!=="全部"){
            $model=$model->withSearch(['name','create_time'], [
                'name'			=>	$keyword,
                'company_name'	=>	$keyword
            ]);
        }
        $list = $model->select();
        return $this->renderSuccess('',[
            "list"=>[
                "data"=>$list,
                "total"=>$company->count()
            ]
        ]);
    }

    /**
     * 新增记录
     */
    public function add()
    {
        $data= input('post.');
        $company = new CompanyPointModel();
        $company['app_id']=$this->store['app']['app_id'];
        $company->save($data);
        return $this->renderSuccess("添加成功");
    }

    /**
     * 编辑记录
     */
    public function edit()
    {
        $data= input('post.');
        unset($data['create_time']);
        unset($data['update_time']);
        $company = new CompanyPointModel();
        $company->update($data);
        return $this->renderSuccess('更新成功');
    }


    /**
     * 软删除
     */
    public function delete()
    {
        // 删除关联
        $company = new CompanyPointModel();
        $company->where('company_point_id', '=',  input('post.company_point_id'))->delete();
        return $this->renderSuccess('删除成功');
    }

}