<?php

namespace app\common\model\plus\live;

use app\common\exception\BaseException;
use app\common\library\easywechat\AppWx;
use app\common\library\easywechat\wx\LiveRoom as WxLiveRoom;
use app\common\model\BaseModel;

/**
 * 微信直播模型
 */
class WxLive extends BaseModel
{
    protected $name = 'app_wx_live';
    protected $pk = 'live_id';
    //附加字段
    protected $append = ['start_time_text','end_time_text'];

    /**
     * 有效期-开始时间
     */
    public function getStartTimeTextAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $data['start_time']);
    }

    /**
     * 有效期-开始时间
     * @param $value
     * @return array
     */
    public function getEndTimeTextAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $data['end_time']);
    }

    /**
     * 详情
     */
    public static function detail($live_id)
    {
        return (new static())->find($live_id);
    }

    /**
     * 同步直播间
     */
    public function syn($app_id = null){
        // 小程序配置信息
        $app = AppWx::getApp($app_id);
        // 请求api数据
        $live_room = new WxLiveRoom($app);
        $response = $live_room->syn();
        $isEmpty = false;
        if ($response === false) {
            if($live_room->getError() == 'empty'){
                $isEmpty = true;
            }else{
                throw new BaseException(['msg' => '获取直播房间列表请求失败：' . $live_room->getError()]);
            }
        }
        // 格式化返回的列表数据
        $roomList = [];
        if(!$isEmpty){
            foreach ($response['room_info'] as $item) {
                $roomList[$item['roomid']] = $item;
            }
        }

        $roomIds = array_column($roomList, 'roomid');
        // 本地历史数据
        $hasRoomIds = $this->getRoomIds();
        // 新增数据库没有的
        $this->addRoom($hasRoomIds, $roomIds, $roomList);
        // 删除微信小程序已删除的房间
        $this->deleteRoom($hasRoomIds, $roomIds);
        // 更新本地直播间
        $this->updateRoom($hasRoomIds, $roomIds, $roomList);
        return true;
    }

    /**
     * 获取本地直播间
     */
    private function getRoomIds()
    {
        return $this->where('is_delete', '=', 0)->column('roomid', 'live_id');
    }

    /**
     * 同步新增直播间
     */
    private function addRoom($hasRoomIds , $roomIds, $roomList)
    {
        // 需要新增的直播间ID
        $ids = array_values(array_diff($roomIds, $hasRoomIds));
        if (empty($ids)) return true;

        // 整理新增数据
        $saveData = [];
        foreach ($ids as $roomId) {
            $item = $roomList[$roomId];
            $saveData[] = [
                'roomid' => $roomId,
                'name' => $item['name'],
                'cover_img' => $item['cover_img'],
                'share_img' => $item['share_img'],
                'anchor_name' => $item['anchor_name'],
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'live_status' => $item['live_status'],
                'app_id' => self::$app_id,
            ];
        }
        // 批量新增直播间
        return $this->saveAll($saveData);
    }

    /**
     * 同步删除直播间
     */
    private function deleteRoom($hasRoomIds, $roomIds)
    {
        // 需要删除的直播间ID
        $ids = array_values(array_diff($hasRoomIds, $roomIds));
        if (empty($ids)) return true;
        // 批量删除直播间
        return self::where('roomid', 'in', $ids)->delete();
    }

    /**
     * 修改本地直播间
     */
    private function updateRoom($hasRoomIds, $roomIds, $roomList)
    {
        // 需要新增的直播间ID
        $ids = array_values(array_intersect($roomIds, $hasRoomIds));
        if (empty($ids)) return true;

        // 整理新增数据
        $saveData = [];
        foreach ($ids as $roomId) {
            $item = $roomList[$roomId];
            $saveData[] = [
                'live_id' => array_search($roomId, $hasRoomIds),
                'roomid' => $roomId,
                'name' => $item['name'],
                'cover_img' => $item['cover_img'],
                'share_img' => $item['share_img'],
                'anchor_name' => $item['anchor_name'],
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'live_status' => $item['live_status'],
                'app_id' => self::$app_id,
            ];
        }
        // 批量新增直播间
        return $this->saveAll($saveData);
    }

}
