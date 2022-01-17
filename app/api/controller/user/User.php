<?php

namespace app\api\controller\user;

use app\api\model\order\OrderApply;
use app\api\model\user\User as UserModel;
use app\api\controller\Controller;
use app\common\library\easywechat\AppWx;

/**
 * 用户管理模型
 */
class User extends Controller
{
    /**
     * 用户自动登录,默认微信小程序
     */
    public function login()
    {
        $model = new UserModel;
        $user_id = $model->login($this->request->post());
        $user  = $model->getUser($model->getToken());
        return $this->renderSuccess('',[
            'user_id' => $user_id,
            'isSetPhone'=>!empty($user['mobile']),
            'isSetRealName'=>!empty($user['real_name']),
            'token' => $model->getToken()
        ]);
    }

    /**
     * 当前用户详情
     */
    public function detail()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        return $this->renderSuccess('',compact('userInfo'));
    }

    public function getSession($code){
        // 微信登录 获取session_key
        $app = AppWx::getApp();
        $session_key = AppWx::sessionKey($app, $code)['session_key'];
        return $this->renderSuccess('',compact('session_key'));
    }

    /**
     * 绑定手机号
     */
    public function bindMobile()
    {
        $model = $this->getUser();
        $mobile = $model->bindMobile($this->request->post());
        $isSetRealName=!empty($model['real_name']);
        if($mobile){
            return $this->renderSuccess('', compact('mobile','isSetRealName'));
        }
        return $this->renderError('绑定失败');
    }
}