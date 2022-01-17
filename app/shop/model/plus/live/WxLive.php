<?php

namespace app\shop\model\plus\live;

use app\common\model\plus\live\WxLive as WxLiveModel;
/**
 * 砍价模型
 */
class WxLive extends WxLiveModel
{
    /**
     *列表
     */
    public function getList($params)
    {
        $model = $this;
        if(isset($params['search']) && !empty($params['search'])){
            $model = $model->where('name|anchor_name', 'like', "%{$params['search']}%");
        }
        return $model->order([
            'is_top' => 'desc',
            'live_status' => 'asc',
            'create_time' => 'desc'
        ])->paginate($params);
    }


    /**
     * 设置直播间置顶状态
     */
    public function setTop($params)
    {
        return $this->save(['is_top' => (int)$params['is_top']]);
    }
}
