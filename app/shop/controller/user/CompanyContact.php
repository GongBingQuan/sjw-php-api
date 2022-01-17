<?php



namespace app\shop\controller\user;

use app\shop\controller\Controller;
use app\shop\model\user\CompanyContact as CompanyContactModel;

/**
 * 企业数据
 */
class CompanyContact extends Controller
{
   function index($page,$list_rows){
       $company = new CompanyContactModel();
       $list = $company->page($page,$list_rows)->with(['company'])->order('company_contact_id desc')->select();
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
    function search($keyword,$company_id=0){
        $company = new CompanyContactModel();
        $model = $company
            ->whereNotNull("name")
            ->order('company_contact_id desc')
            ->limit(50);
        if($company_id){
            $model=$model->where('company_id',$company_id);
        }
        if($keyword!=="全部"){
            $model=$model->withSearch(['name','phone'], [
                'name'			=>	$keyword,
                'phone'	=>	$keyword
            ]);
        }
        $list = $model->select();
        return $this->renderSuccess('',[
            "list"=>[
                "data"=>$list,
                "total"=>$model->count()
            ]
        ]);
    }

    /**
     * 新增记录
     */
    public function add()
    {
        $data= input('post.');
        $company = new CompanyContactModel();
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
        $company = new CompanyContactModel();
        $company->update($data);
        return $this->renderSuccess('更新成功');
    }


    /**
     * 软删除
     */
    public function delete()
    {
        // 删除关联
        $company = new CompanyContactModel();
        $company->where('company_contact_id', '=',  input('post.company_contact_id'))->delete();
        return $this->renderSuccess('删除成功');
    }

}