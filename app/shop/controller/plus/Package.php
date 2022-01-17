<?php

namespace app\shop\controller\plus;

use app\shop\controller\Controller;
use app\shop\model\plus\giftpackage\GiftPackage as GiftPackageModel;
use app\shop\model\plus\giftpackage\Order;
use app\common\service\qrcode\PackageService;

/**
 * 礼包购控制器
 */
class Package extends Controller
{
    /*
     * 礼包列表
     */
    public function index()
    {
        $model = new GiftPackageModel();
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 获取数据
     * @param null $id
     */
    public function getGiftPackage($id)
    {
        $model = new GiftPackageModel();
        $data = $model->getGiftPackage($id);
        return $this->renderSuccess('', compact('data'));
    }

    /**
     *保存礼包
     */
    public function add()
    {
        $model = new GiftPackageModel();
        if ($model->saveGift($this->postData())) {
            return $this->renderSuccess('保存成功');
        }
        return $this->renderError($model->getError() ?:'保存失败');
    }

    /**
     *修改
     */
    public function edit($gift_package_id)
    {
        $model = GiftPackageModel::detail($gift_package_id);
        if ($model->edit($this->postData())) {
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError($model->getError() ?:'修改失败');
    }

    /**
     * 发布
     */
    public function send($id)
    {
        $model = new GiftPackageModel();
        if ($model->send($id)) {
            return $this->renderSuccess('发布成功');
        }
        return $this->renderError('发布失败');
    }

    /**
     * 终止
     */
    public function end($id)
    {
        $model = new GiftPackageModel();
        if ($model->end($id)) {
            return $this->renderSuccess('终止成功');
        }
        return $this->renderError('终止失败');
    }

    /**
     * 删除
     */
    public function delete($id)
    {
        $model = new GiftPackageModel();
        if ($model->del($id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

    /**
     * 购买记录
     */
    public function orderlist()
    {
        $model = new Order();
        $list = $model->getList($this->postData());
        return $this->renderSuccess('', compact('list'));
    }

    /**
     * 获取推广二维码
     */
    public function qrcode($id, $source)
    {
        $Qrcode = new PackageService($id, $source);
        $Qrcode->getImage();
    }
}