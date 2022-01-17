<?php

namespace app\shop\controller\page;

use app\common\enum\settings\SettingEnum;
use app\shop\controller\Controller;
use app\shop\model\settings\Setting as SettingModel;

/**
 * 交易设置控制器
 */
class Tabbar extends Controller
{
    /**
     * 交易设置
     */
    public function index()
    {
        $vars = SettingModel::getItem(SettingEnum::TABBAR);
        return $this->renderSuccess('', compact('vars'));
    }

    /**
     * 获取交易设置
     */
    public function edit()
    {
        $model = new SettingModel;
        $data = $this->postData();
        $vars = SettingModel::getItem(SettingEnum::TABBAR);
        if($data['type'] == 'image'){
            $vars['menus'][$data['index']] = [
                'index' => $data['index'],
                'text' => $data['text'],
                'iconPath' => $data['iconPath'],
                'selectedIconPath' => $data['selectedIconPath'],
            ];
        } else if($data['type'] == 'color'){
            $vars['color'] = $data['color'];
            $vars['no_color'] = $data['no_color'];
        }
        if ($model->edit(SettingEnum::TABBAR, $vars)) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }
}
