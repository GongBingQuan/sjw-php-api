<?php

namespace app\shop\controller\link;

use app\shop\controller\Controller;
use app\shop\model\plus\giftpackage\GiftPackage;
use app\shop\model\page\Page as PageModel;
use app\shop\model\plus\invitationgift\InvitationGift as InvitationModel;
use app\shop\model\plus\table\Table as TableModel;
/**
 * 超链接控制器
 */
class Link extends Controller
{
    /**
     *获取数据
     */
    public function index()
    {
        // 礼包购
        $model = new GiftPackage();
        $list = $model->getDatas();
        $packageList = [];
        foreach ($list as $item) {
            $packageList[] = [
                'id' => $item['gift_package_id'],
                'url' => 'pages/plus/giftpackage/giftpackage?package_id=' . $item['gift_package_id'],
                'name' => $item['name'],
                'type' => '营销'
            ];
        }
        // 邀请有礼
        $list = (new InvitationModel())->getLinkDatas();
        $invitationList = [];
        foreach ($list as $item) {
            $invitationList[] = [
                'id' => $item['invitation_gift_id'],
                'url' => 'pages/user/invite/invite?invitation_gift_id=' . $item['invitation_gift_id'],
                'name' => $item['name'],
                'type' => '营销'
            ];
        }
        // 万能表单
        $list = (new TableModel())->getAll();
        $tableList = [];
        foreach ($list as $item) {
            $tableList[] = [
                'id' => $item['table_id'],
                'url' => 'pages/plus/table/table?table_id=' . $item['table_id'],
                'name' => $item['name'],
                'type' => '表单'
            ];
        }
        return $this->renderSuccess('', compact('packageList', 'invitationList', 'tableList'));
    }

    /**
     * 获取自定义页面
     */
    public function getPageList()
    {
        $model = new PageModel;
        $list = $model->getLists();
        return $this->renderSuccess('', compact('list'));
    }
}
