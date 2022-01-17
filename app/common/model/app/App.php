<?php

namespace app\common\model\app;

use app\common\exception\BaseException;
use think\facade\Cache;
use app\common\model\BaseModel;

/**
 * 应用模型
 */
class App extends BaseModel
{
    protected $name = 'app';
    protected $pk = 'app_id';

    /**
     * 追加字段
     */
    protected $append = [
        'expire_time_text'
    ];

    /**
     *  过期时间
     */
    public function getExpireTimeTextAttr($value, $data){
        // 发货状态
        if ($data['expire_time'] == 0) {
            return '永不过期';
        }
        return date('Y-m-d', $data['expire_time']);
    }
    /**
     * 获取应用信息
     */
    public static function detail($app_id)
    {
        return (new static())->find($app_id);
    }

    /**
     * 从缓存中获取app信息
     */
    public static function getAppCache($app_id = null)
    {
        if (is_null($app_id)) {
            $self = new static();
            $app_id = $self::$app_id;
        }
        if (!$data = Cache::get('app_' . $app_id)) {
            $data = self::detail($app_id);
            if (empty($data)) throw new BaseException(['msg' => '未找到当前应用信息']);
            Cache::tag('cache')->set('app_' . $app_id, $data);
        }
        return $data;
    }

    /**
     * 启用商城
     * @return bool
     */
    public function updateStatus()
    {
        return $this->save([
            'is_recycle' => !$this['is_recycle'],
        ]);
    }

    /**
     * 所有商城
     */
    public static function getAll(){
        return (new self())->where('is_delete', '=', 0)
            ->select();
    }
}
