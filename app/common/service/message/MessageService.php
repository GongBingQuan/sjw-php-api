<?php

namespace app\common\service\message;

use app\common\model\settings\Setting as SettingModel;
use app\common\model\user\User as UserModel;
use app\common\enum\order\OrderTypeEnum;
use app\common\model\settings\MessageSettings as MessageSettingsModel;
use app\common\model\settings\Message as MessageModel;
use app\common\enum\order\OrderPayTypeEnum;

/**
 * 消息通知服务
 */
class MessageService
{
    /**
     * 订单支付成功后通知
     */
    public function payment($order, $orderType = OrderTypeEnum::MASTER)
    {
        $message = MessageModel::detailByEname('order_pay_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id'], $order['app_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 订单编号
            'order_no' => $order['order_no'],
            // 商品名称
            'product_name' => $this->formatProductName($order['product']),
            // 订单金额
            'pay_price' => $order['pay_price'],
            // 支付方式
            'pay_type' => OrderPayTypeEnum::data()[$order['pay_type']['value']]['name'],
            // 支付时间
            'pay_time' => date('Y-m-d H:i:s', $order['pay_time'])
        ];

        //发送公众号消息
        if ($settings['mp_status'] == 1 && $order['user']['mpopen_id'] != '') {
            MpMessageService::send($data, $settings['mp_template'], $order['user']['mpopen_id'], $order['app_id']);
        }
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $order['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $order['user']['open_id'], $order['app_id']);
        }
        //发送短信消息
        if ($settings['sms_status'] == 1 && $order['user']['mobile'] != '') {
            SmsMessageService::send($data, $settings['sms_template'], $order['user']['mobile'], $order['app_id']);
        }

        // 商家短信通知
        $this->newOrder($order, $data, $orderType);
    }

    /**
     * 商家短信通知
     */
    private function newOrder($order, $data, $orderType = OrderTypeEnum::MASTER)
    {
        $message = MessageModel::detailByEname('order_pay_store');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id']);
        if (!$settings || $settings['sms_status'] == 0) {
            return;
        }
        // 商家短信通知
        $smsConfig = SettingModel::getItem('sms', $order['app_id']);
        $phone = $smsConfig['engine']['aliyun']['accept_phone'];

        if(empty($phone)){
            return;
        }

        SmsMessageService::send($data, $settings['sms_template'], $phone, $order['app_id']);
    }

    /**
     * 后台发货通知
     */
    public function delivery($order, $orderType = OrderTypeEnum::MASTER)
    {
        $message = MessageModel::detailByEname('order_delivery_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 订单编号
            'order_no' => $order['order_no'],
            // 商品信息
            'product_name' => $this->formatProductName($order['product']),
            //收货人
            'name' => $order['address']['name'],
            // 收货地址
            'address' => implode('', $order['address']['region']) . $order['address']['detail'],
            // 物流公司
            'express_name' => $order['express']['express_name'],
            // 物流单号
            'express_no' => $order['express_no'],
            // 发货时间
            'express_time' => date('Y-m-d H:i:s', $order['delivery_time']),
        ];

        //发送公众号消息
        if ($settings['mp_status'] == 1 && $order['user']['mpopen_id'] != '') {
            MpMessageService::send($data, $settings['mp_template'], $order['user']['mpopen_id'], $order['app_id']);
        }
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $order['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $order['user']['open_id'], $order['app_id']);
        }
        //发送短信消息
        if ($settings['sms_status'] == 1 && $order['user']['mobile'] != '') {
            SmsMessageService::send($data, $settings['sms_template'], $order['user']['mobile'], $order['app_id']);
        }
    }

    /**
     * 后台售后单状态通知
     * $sence场景，audit 审核  receipt 确认退款
     */
    public function refund($refund, $order_no, $sence = 'audit')
    {
        $message = MessageModel::detailByEname('order_refund_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id']);
        if (!$settings) {
            return;
        }
        $data = [
            // 订单编号
            'order_no' => $order_no,
            // 商品名称
            'product_name' => $refund['order_product']['product_name'],
            // 售后类型
            'type' => $refund['type']['text'],
            // 处理结果
            'status' => $sence == 'audit'? $refund['is_agree']['text'] : $refund['status']['text'],
            // 处理时间
            'process_time' => date('Y-m-d H:i:s', time()),
            // 拒绝原因
            'refuse_desc' => $refund['refuse_desc']?: '无',
        ];

        //发送公众号消息
        if ($settings['mp_status'] == 1 && $refund['user']['mpopen_id'] != '') {
            MpMessageService::send($data, $settings['mp_template'], $refund['user']['mpopen_id'], $refund['app_id']);
        }
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $refund['user']['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $refund['user']['open_id'], $refund['app_id']);
        }
        //发送短信消息
        if ($settings['sms_status'] == 1 && $refund['user']['mobile'] != '') {
            SmsMessageService::send($data, $settings['sms_template'], $refund['user']['mobile'], $refund['app_id']);
        }
    }


    /**
     * 分销商入驻审核通知
     */
    public function agent($agent)
    {
        $message = MessageModel::detailByEname('agent_apply_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id']);
        if (!$settings) {
            return;
        }

        // 发送模板消息
        $reason = '';
        if ($agent['apply_status'] == 30) {
            $reason = "驳回原因：" . $agent['reject_reason'];
        }

        $data = [
            // 申请时间
            'apply_time' => $agent['apply_time'],
            //审核状态
            'apply_status' => $agent['apply_status']['text'],
            // 审核时间
            'audit_time' => $agent['audit_time'],
            // 拒绝原因
            'reason' => $reason?:'无',
        ];

        // 获取用户信息
        $user = UserModel::detail($agent['user_id']);

        //发送公众号消息
        if ($settings['mp_status'] == 1 && $user['mpopen_id'] != '') {
            MpMessageService::send($data, $settings['mp_template'], $user['mpopen_id'], $user['app_id']);
        }
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $user['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $user['open_id'], $user['app_id']);
        }
        //发送短信消息
        if ($settings['sms_status'] == 1 && $user['mobile'] != '') {
            SmsMessageService::send($data, $settings['sms_template'], $user['mobile'], $user['app_id']);
        }
    }

    /**
     * 分销商提现审核通知
     */
    public function cash($cash)
    {
        $message = MessageModel::detailByEname('agent_cash_user');
        $settings = MessageSettingsModel::detailByMessageId($message['message_id']);
        if (!$settings) {
            return;
        }

        // 发送模板消息
        $reason = '无';
        if ($cash['apply_status'] == 30) {
            $reason = $cash['reject_reason'];
        }

        $data = [
            // 提现时间
            'create_time' => $cash['create_time'],
            //提现方式
            'pay_type' => $cash['pay_type']['text'],
            // 提现金额
            'money' => $cash['money'],
            // 提现状态
            'apply_status' => $cash['apply_status']['text'],
            // 拒绝原因
            'reason' => $reason,
        ];

        // 获取用户信息
        $user = UserModel::detail($cash['user_id']);

        //发送公众号消息
        if ($settings['mp_status'] == 1 && $user['mpopen_id'] != '') {
            MpMessageService::send($data, $settings['mp_template'], $user['mpopen_id'], $user['app_id']);
        }
        //发送小程序订阅消息
        if ($settings['wx_status'] == 1 && $user['open_id'] != '') {
            WxMessageService::send($data, $settings['wx_template'], $user['open_id'], $user['app_id']);
        }
        //发送短信消息
        if ($settings['sms_status'] == 1 && $user['mobile'] != '') {
            SmsMessageService::send($data, $settings['sms_template'], $user['mobile'], $user['app_id']);
        }
    }

    /**
     * 格式化商品名称
     */
    private function formatProductName($productData)
    {
        $str = '';
        foreach ($productData as $product) {
            $str .= $product['product_name'] . ' ';
        }
        return $str;
    }

}