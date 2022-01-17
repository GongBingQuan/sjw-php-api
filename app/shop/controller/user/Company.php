<?php



namespace app\shop\controller\user;

use app\shop\controller\Controller;
use app\shop\model\order\Order;
use app\shop\model\user\Company as CompanyModel;
use \app\shop\model\user\CompanyPoint;

/**
 * 企业数据
 */
class Company extends Controller
{
   function index($page,$list_rows){
       $company = new CompanyModel();
       $list = $company->page($page,$list_rows)->order('company_id desc')->select();
       foreach ($list as $key => &$value){
           //总消费积分
           $value['total_point']=CompanyPoint::where('company_id',$value['company_id'])->sum('num');
           //消费单数
           $value['total_count']=CompanyPoint::where('company_id',$value['company_id'])->count();
           //订单消费总额
           $value['total_order_price']= Order::where('company_id',$value['company_id'])->sum('pay_price');
           //订单数量
           $value['total_order_count']= Order::where('company_id',$value['company_id'])->count();
           $value['usable_point'] =  $value['total_order_price'] - $value['total_point'];
       }
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
        $company = new CompanyModel();
        $model = $company->field('name,company_id,company_name')
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
        $company = new CompanyModel();
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
        $company = new CompanyModel();
        $company->update($data);
        return $this->renderSuccess('更新成功');
    }


    /**
     * 软删除
     */
    public function delete()
    {
        // 删除关联
        $company = new CompanyModel();
        $company->where('company_id', '=',  input('post.company_id'))->delete();
        return $this->renderSuccess('删除成功');
    }

}