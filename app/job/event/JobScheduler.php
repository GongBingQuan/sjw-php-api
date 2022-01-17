<?php

namespace app\job\event;


use app\common\model\app\App as AppModel;
use think\facade\Cache;

/**
 * 订单事件管理
 */
class JobScheduler
{

    /**
     * 执行函数
     */
    public function handle()
    {
        // 查找所有appid
        $appList = AppModel::getAll();
        // 涉及到应用单独配置的，循环执行
        foreach ($appList as $app){
            // 订单任务
            event('Order', $app['app_id']);
            // 同步直播
            event('Live', $app['app_id'], count($appList));
        }
        // 拼团任务
        event('AssembleBill');
        // 砍价任务
        event('BargainTask');
        // 用户优惠券
        event('UserCoupon');
        // 分销商订单
        event('AgentOrder');
        return true;
    }

}
