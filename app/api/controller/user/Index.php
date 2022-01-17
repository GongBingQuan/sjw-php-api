<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\plus\agent\Setting;
use app\api\model\user\User as UserModel;
use app\api\model\order\Order as OrderModel;
use app\api\model\settings\Setting as SettingModel;
use app\api\model\plus\coupon\UserCoupon as UserCouponModel;
use app\common\enum\settings\GetPhoneTypeEnum;
use think\facade\Cache;
use app\api\model\plus\invitationgift\InvitationGift as InvitationGiftModel;

/**
 * 个人中心主页
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     */
    public function detail($source = 'wx')
    {
        // 当前用户信息
        $user = $this->getUser();
        $coupon_model = new UserCouponModel();
        $coupon = $coupon_model->getCount($user['user_id']);
        // 订单总数
        $model = new OrderModel;

        // 分销商基本设置
        $setting = Setting::getItem('basic');
        // 是否开启分销功能
        $agent_open = $setting['is_open'];
        $invitation = InvitationGiftModel::getShow();
        return $this->renderSuccess('', [
            'coupon' => $coupon,
            'userInfo' => $user,
            'orderCount' => [
                'payment' => $model->getCount($user, 'payment'),
                'delivery' => $model->getCount($user, 'delivery'),
                'received' => $model->getCount($user, 'received'),
                'comment' => $model->getCount($user, 'comment'),
            ],
            'setting' => [
                'points_name' => SettingModel::getPointsName(),
                'agent_open' => $agent_open
            ],
            'sign' => SettingModel::getItem('sign'),
            'getPhone' => $this->isGetPhone(),
            'menus' => UserModel::getMenus($source, $user['user_id']),   // 个人中心菜单列表
            'invitation' => $invitation
        ]);
    }

    /**
     * 当前用户设置
     */
    public function setting()
    {
        // 当前用户信息
        $user = $this->getUser();

        return $this->renderSuccess('', [
            'userInfo' => $user
        ]);
    }
    public function  setUser(){
        $user = input('post.');
        if(!$user['user_id']||!$user['value']){
            return $this->renderError('请填写完再提交');
        }
        $data = [$user['field']=>$user['value']];
        if($user['field']=='company'){
            $value = json_decode($user['value'],true);
            if(!$value['company_name']){
                return $this->renderError('请填写完再提交');
            }
            if($value['company_id']){
                $data['company_id'] = $value['company_id'];
            }
            $data['company'] = $value['company_name'];

        }
        $res = UserModel::where('user_id',$user['user_id'])->save($data);
        return $this->renderSuccess('', [
            'change' => $res
        ]);
    }
    public function  setUserInfo(){
        $post = input('post.');
        $user = $this->getUser();
        if(empty($user)
            ||empty($post['company'])
            ||empty($post['company_type'])
            ||empty($post['real_name'])
            ||empty($post['position'])
        ){
            return $this->renderError('请填写完再提交');
        }
        $value = json_decode($post['value'],true);
        if(!$value['company_name']){
            return $this->renderError('请填写完再提交');
        }
        $post['company'] = $value['company_name'];
        if($value['company_id']){
            $post['company_id'] = $value['company_id'];
        }

        $res = $user->save($post);
        return $this->renderSuccess('', [
            'change' => $res
        ]);
    }
    private function isGetPhone()
    {
        $user = $this->getUser();
        if ($user['mobile'] != '') {
            return false;
        }
        $settings = SettingModel::getItem('getPhone');
        if (in_array(GetPhoneTypeEnum::USER, $settings['area_type'])) {
            // 缓存时间
            $key = 'get_phone_' . $user['user_id'];
            if (!$data = Cache::get($key)) {
                $settings['send_day'] > 0 && Cache::set($key, '1', 86400 * $settings['send_day']);
                return true;
            }
        }
        return false;
    }
}