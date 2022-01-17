<?php

namespace app\api\controller;

use app\api\model\product\Product as ProductModel;
use app\api\model\page\Page as AppPage;
use app\api\model\settings\Setting as SettingModel;
use app\common\enum\settings\SettingEnum;
use app\common\model\app\AppUpdate as AppUpdateModel;
/**
 * 页面控制器
 */
class Index extends Controller
{
    /**
     * 首页
     */
    public function index($page_id = null, $url = '')
    {
        // 页面元素
        $data = AppPage::getPageData($this->getUser(false), $page_id);
        $model = new ProductModel;
        $data['setting'] = array(
            'collection' => SettingModel::getItem('collection'),
            'officia' => SettingModel::getItem('officia'),
            'homepush' => SettingModel::getItem('homepush'),
        );
        $data['benner'] = $model->getList(['type'=>'sell','ad'=>1], $this->getUser(false));
        // 扫一扫参数
        $data['signPackage'] = $this->getScanParams($url)['signPackage'];
        // 微信公众号分享参数
        $data['share'] = $this->getShareParams($url, $data['page']['params']['share_title'], $data['page']['params']['share_title'], '/pages/index/index', $data['page']['params']['share_img']);
        return $this->renderSuccess('', $data);
    }

    // 公众号客服
    public function mpService()
    {
        $mp_service = SettingModel::getItem('mp_service');
        return $this->renderSuccess('', compact('mp_service'));
    }

    // app更新
    public function update($name, $version, $platform){
        $result = [
            'update' => false,
            'wgtUrl' => '',
            'pkgUrl' => '',
        ];
        try {
            $model = AppUpdateModel::getLast();
            // 这里简单判定下，不相等就是有更新。
            if($model && $version != $model['version']){
                $currentVersions = explode('.', $version);
                $resultVersions = explode('.', $model['version']);

                if ($currentVersions[0] < $resultVersions[0]) {
                    // 说明有大版本更新
                    $result['update'] = true;
                    $result['pkgUrl'] = $platform == 'android'?$model['pkg_url_android']:$model['pkg_url_ios'];
                } else {
                    // 其它情况均认为是小版本更新
                    $result['update'] = true;
                    $result['wgtUrl'] = $model['wgt_url'];
                }
            }
        } catch (\Exception $e) {

        }
        return $this->renderSuccess('', compact('result'));
    }

    public function nav(){
        $vars = SettingModel::getItem(SettingEnum::TABBAR);
        return $this->renderSuccess('', compact('vars'));
    }
}
