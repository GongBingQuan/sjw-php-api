<?php

namespace app\shop\model\page;
use app\common\model\page\CenterMenu as CenterMenuModel;
use think\facade\Cache;

/**
 * 模型
 */
class CenterMenu extends CenterMenuModel
{

    /**
     * 获取列表
     */
    public function getList($params)
    {
        $list = $this->order(['sort' => 'asc'])
            ->paginate($params);
        foreach ($list as $menus){
            if(strpos($menus['icon'], 'http') !== 0){
                $menus['icon'] = self::$base_url . $menus['icon'];
            }
        }
        return $list;
    }
    /**
     * 添加新记录
     */
    public function add($data)
    {
        $data['app_id'] = self::$app_id;
        $this->deleteCache();
        return $this->save($data);
    }

    /**
     * 编辑记录
     */
    public function edit($data)
    {
        $this->deleteCache();
        return $this->save($data);
    }

    /**
     * 删除记录
     */
    public function remove()
    {
        $this->deleteCache();
        return $this->delete();
    }

    /**
     * 删除缓存
     */
    private function deleteCache()
    {
        return Cache::delete('center_menu_' . static::$app_id);
    }
}