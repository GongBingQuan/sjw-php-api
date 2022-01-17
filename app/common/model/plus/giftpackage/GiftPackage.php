<?php

namespace app\common\model\plus\giftpackage;

use app\common\model\BaseModel;

/**
 * Class GiftPackage
 * 礼包购模型
 * @package app\common\model\plus\giftpackage
 */
class GiftPackage extends BaseModel
{
    protected $name = 'gift_package';
    protected $pk = 'gift_package_id';

    /**
     * 礼包详情
     */
    public static function detail($gift_package_id)
    {
        return (new static())->with(['image'])->find($gift_package_id);
    }
    /**
     * 开始时间
     */
    public function getStartTimeAttr($value)
    {
        return ['text' => date('Y-m-d H:i:s', $value), 'value' => $value];
    }

    /**
     * 有效期-结束时间
     */
    public function getEndTimeAttr($value)
    {
        return ['text' => date('Y-m-d H:i:s', $value), 'value' => $value];
    }

    /**
     * 状态
     */
    public function getStatusAttr($value, $data)
    {
        $text = '';
        if($value == 1){
            $text = '未生效';
        }else{
            if ($data['start_time'] > time()) {
                $text = '未开始';
            }
            if ($data['end_time'] < time()) {
                $text = '已结束';
            }
            if ($data['start_time'] < time() && $data['end_time'] > time()) {
                $text = '进行中';
            }
        }
        return ['text' => $text, 'value' => $value];
    }
    /**
     * 关联文件库
     */
    public function image()
    {
        return $this->belongsTo('app\\common\\model\\file\\UploadFile', 'image_id', 'file_id')
            ->bind(['file_path']);
    }
}