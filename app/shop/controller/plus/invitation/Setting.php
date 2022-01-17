<?php

namespace app\shop\controller\plus\invitation;

use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 邀请好友设置
 */
class Setting extends Controller
{
    /**
     *邀请好友设置
     */
    public function getSetting()
    {
        $vars['values'] = SettingModel::getItem('invitation');
        return $this->renderSuccess('', compact('vars'));
    }

    /**
     * 邀请好友设置
     */
    public function index()
    {
        if($this->request->isGet()){
            return $this->getSetting();
        }
        $model = new SettingModel;
        $data = $this->request->param();
        if ($model->edit('invitation', $data)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError('操作失败');
    }
}