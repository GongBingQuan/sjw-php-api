<?php

namespace app\shop\model\page;

use app\common\model\page\Page as PageModel;
use app\shop\model\app\App;

/**
 * 微信小程序diy页面模型
 */
class Page extends PageModel
{
    /**
     * 获取列表
     */
    public function getList($params)
    {
        return $this->where(['is_delete' => 0])
            ->where(['page_type' => 20])
            ->order(['create_time' => 'desc'])
            ->hidden(['page_data'])
            ->paginate($params);
    }

    /**
     * 获取所有自定义页面
     */
    public function getLists()
    {
        return $this->where(['is_delete' => 0])
            ->where(['page_type' => 20])
            ->hidden(['page_data'])
            ->order(['create_time' => 'desc'])
            ->select();
    }

    /**
     * 新增页面
     */
    public function add($data)
    {
        // 删除app缓存
        App::deleteCache();
        return $this->save([
            'page_type' => 20,
            'page_name' => $data['page']['params']['name'],
            'page_data' => $data,
            'app_id' => self::$app_id
        ]);
    }

    /**
     * 更新页面
     */
    public function edit($data)
    {
        // 删除app缓存
        App::deleteCache();
        // 保存数据
        return $this->save([
                'page_name' => $data['page']['params']['name'],
                'page_data' => $data
            ]) !== false;
    }

    public function setDelete(){
        return $this->save(['is_delete' => 1]);
    }
}
