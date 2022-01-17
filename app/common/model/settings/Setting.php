<?php

namespace app\common\model\settings;

use app\common\enum\settings\SettingEnum;
use think\facade\Cache;
use app\common\enum\settings\DeliveryTypeEnum;
use app\common\model\BaseModel;
use think\Model;
/**
 * 系统设置模型
 */
class Setting extends BaseModel
{
    protected $name = 'setting';
    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 修改器: 转义成json格式
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取指定项设置
     */
    public static function getItem($key, $app_id = null)
    {
        $data = self::getAll($app_id);
        $data_key = $data[$key];
        if(isset($data_key)){
            $data_key = $data[$key]['values'];
            jsonRecursive($data_key);
        }else{
            $data_key = [];
        }

        return $data_key;
    }

    /**
     * 获取设置项信息
     */
    public static function detail($key)
    {
        return (new static())->where('key', '=', $key)->find();
    }

    /**
     * 全局缓存: 系统设置
     */
    public static function getAll($app_id = null)
    {
        $static = new static;
        is_null($app_id) && $app_id = $static::$app_id;

        if (!$data = Cache::get('setting_' . $app_id)) {
            $setting = $static->where(compact('app_id'))->select();
            $data = empty($setting) ? [] : array_column($static->collection($setting)->toArray(), null, 'key');
            Cache::tag('cache')->set('setting_' . $app_id, $data);
        }
        return $static->getMergeData($data);
    }

    /**
     * 数组转换为数据集对象
     */
    public function collection($resultSet)
    {
        $item = current($resultSet);
        if ($item instanceof Model) {
            return \think\model\Collection::make($resultSet);
        } else {
            return \think\Collection::make($resultSet);
        }
    }


    /**
     * 合并用户设置与默认数据
     */
    private function getMergeData($userData)
    {
        $defaultData = $this->defaultData();
        // 商城设置：配送方式
        if (isset($userData['store']['values']['delivery_type'])) {
            unset($defaultData['store']['values']['delivery_type']);
        }
        return array_merge_multiple($defaultData, $userData);
    }

    /**
     * 默认配置
     */
    public function defaultData($storeName = null)
    {
        return [
            SettingEnum::STORE => [
                'key' => 'store',
                'describe' => '商城设置',
                'values' => [
                    // 商城名称
                    'name' => $storeName ?: '玖玖珈商城',
                    // 配送方式
                    'delivery_type' => array_keys(DeliveryTypeEnum::data()),
                    // 快递100
                    'kuaidi100' => [
                        'customer' => '',
                        'key' => '',
                    ],
                    // 是否记录日志
                    'is_get_log' => true
                ],
            ],
            SettingEnum::MP_SERVICE => [
                'key' => 'mp_service',
                'describe' => '公众号客服设置',
                'values' => [
                    // qq
                    'qq' => '',
                    // 微信
                    'wechat' => '',
                    // 微信公众号图片
                    'mp_image' => '',
                ],
            ],
            SettingEnum::TRADE => [
                'key' => 'trade',
                'describe' => '交易设置',
                'values' => [
                    'order' => [
                        'close_days' => '3',
                        'receive_days' => '10',
                        'refund_days' => '7'
                    ],
                    'freight_rule' => '10',
                ]
            ],
            SettingEnum::STORAGE => [
                'key' => 'storage',
                'describe' => '上传设置',
                'values' => [
                    'default' => 'local',
                    'engine' => [
                        'local' => [],
                        'qiniu' => [
                            'bucket' => '',
                            'access_key' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                        'aliyun' => [
                            'bucket' => '',
                            'access_key_id' => '',
                            'access_key_secret' => '',
                            'domain' => 'http://'
                        ],
                        'qcloud' => [
                            'bucket' => '',
                            'region' => '',
                            'secret_id' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                    ]
                ],
            ],
            SettingEnum::SMS => [
                'key' => 'sms',
                'describe' => '短信通知',
                'values' => [
                    'default' => 'aliyun',
                    'engine' => [
                        'aliyun' => [
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '三勾商城',
                            'accept_phone' => '',
                            'template_code' => ''
                        ],
                    ],
                ],
            ],
            SettingEnum::TPL_MSG => [
                'key' => 'tplMsg',
                'describe' => '模板消息',
                'values' => [
                    'payment' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'delivery' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                    'refund' => [
                        'is_enable' => '0',
                        'template_id' => '',
                    ],
                ],
            ],
            SettingEnum::PRINTER => [
                'key' => 'printer',
                'describe' => '小票打印机设置',
                'values' => [
                    'is_open' => '0',   // 是否开启打印
                    'printer_id' => '', // 打印机id
                    'order_status' => [], // 订单类型 10下单打印 20付款打印 30确认收货打印
                ],
            ],
            SettingEnum::FULL_FREE => [
                'key' => 'full_free',
                'describe' => '满额包邮设置',
                'values' => [
                    'is_open' => '0',   // 是否开启满额包邮
                    'money' => '',      // 单笔订单额度
                ],
            ],
            SettingEnum::POINTS => [
                'key' => 'points',
                'describe' => '积分设置',
                'values' => [
                    'points_name' => '积分',         // 积分名称自定义
                    'is_shopping_gift' => '0',      // 是否开启购物送积分
                    'gift_ratio' => '100',            // 是否开启购物送积分
                    'is_shopping_discount' => '0',    // 是否允许下单使用积分抵扣
                    'discount' => [     // 积分抵扣
                        'discount_ratio' => '0.01',       // 积分抵扣比例
                        'full_order_price' => '100.00',       // 订单满[?]元
                        'max_money_ratio' => '10',             // 最高可抵扣订单额百分比
                    ],
                    // 充值说明
                    'describe' => "a) 积分不可兑现、不可转让,仅可在本平台使用;\n" .
                        "b) 您在本平台参加特定活动也可使用积分,详细使用规则以具体活动时的规则为准;\n" .
                        "c) 积分的数值精确到个位(小数点后全部舍弃,不进行四舍五入)\n" .
                        "d) 买家在完成该笔交易(订单状态为“已签收”)后才能得到此笔交易的相应积分,如购买商品参加店铺其他优惠,则优惠的金额部分不享受积分获取;",
                ],
            ],
            SettingEnum::OFFICIA => [
                'key' => 'officia',
                'describe' => '公众号关注',
                'values' => [
                    'status' => 0
                ],
            ],
            SettingEnum::COLLECTION => [
                'key' => 'collection',
                'describe' => '引导收藏',
                'values' => [
                    'status' => 0
                ],
            ],
            SettingEnum::RECOMMEND => [
                'key' => 'recommend',
                'describe' => '商品推荐',
                'values' => [
                    'is_recommend' => '0',
                    'location' => [],
                    'choice' => '0',
                    'type' => '10',
                    'num' => '20',
                    'product' => []
                ],
            ],
            SettingEnum::HOMEPUSH => [
                'key' => 'homepush',
                'describe' => '首页推送',
                'values' => [
                    // 是否开启
                    'is_open' => 0,
                ]
            ],
            SettingEnum::POINTSMALL => [
                'key' => 'pointsmall',
                'describe' => '积分商城',
                'values' => [
                    // 是否开启
                    'is_open' => false,
                    // 是否使用优惠券
                    'is_coupon' => false,
                    // 是否分销
                    'is_agent' => false,
                ]
            ],
            SettingEnum::BARGAIN => [
                'key' => 'bargain',
                'describe' => '限时砍价',
                'values' => [
                    // 是否使用优惠券
                    'is_coupon' => false,
                    // 是否分销
                    'is_agent' => false,
                    // 是否开启积分
                    'is_point' => false,
                    // 规则
                    'bargain_rules' => ''
                ]
            ],
            SettingEnum::SIGN => [
                'key' => 'sign',
                'describe' => '签到有礼',
                'values' => [
                    // 是否开启
                    'is_open' => false
                ]
            ],
            SettingEnum::SECKILL => [
                'key' => 'seckill',
                'describe' => '限时秒杀',
                'values' => [
                    // 是否开启积分
                    'is_point' => false,
                    // 是否开启分销
                    'is_agent' => false,
                    //未付款订单自动关闭时间,分钟
                    'order_close' => 10,
                    // 是否开启优惠券
                    'is_coupon' => false,
                ]
            ],
            SettingEnum::ASSEMBLE => [
                'key' => 'assemble',
                'describe' => '限时拼团',
                'values' => [
                    // 是否开启
                    'is_open' => false,
                    // 是否开启积分
                    'is_point' => false,
                    // 是否开启分销
                    'is_agent' => false,
                    // 是否使用优惠券
                    'is_coupon' => false,
                ]
            ],
            SettingEnum::GETPHOME => [
                'key' => 'getPhone',
                'describe' => '获取手机号设置',
                'values' => [
                    // 显示区域
                    'area_type' => [],
                    // 不再提示天数
                   'send_day' => 7
                ],
            ],
            SettingEnum::BALANCE => [
                'key' => 'balance',
                'describe' => '充值设置',
                'values' => [
                    // 是否开启
                    'is_open' => 0,
                    // 是否可以自定义
                    'is_plan' => 1,
                    // 最低充值金额
                    'min_money' => 1,
                    // 充值说明
                    'describe' => "a) 账户充值仅限在线方式支付，充值金额实时到账；\n" .
                        "b) 有问题请联系客服;\n" ,
                ]
            ],
            SettingEnum::INVITATION => [
                'key' => 'invitation',
                'describe' => '邀请好友',
                'values' => [
                    // 是否开启
                    'is_open' => false,
                ]
            ],
            SettingEnum::APPSHARE => [
                'key' => 'appshare',
                'describe' => 'app分享',
                'values' => [
                    // 分享类型 1公众号/h5 2小程序 3下载页
                    'type' => 1,
                    // 公众号、h5地址
                    'open_site' => '',
                    // 小程序原始id
                    'gh_id' => '',
                    // 跳转网页
                    'web_url' => '',
                    // 下载页
                    'down_url' => '',
                    // 绑定类型
                    'bind_type' => 1
                ]
            ],
            SettingEnum::H5ALIPAY => [
                'key' => 'h5Alipay',
                'describe' => 'h5支付宝支付',
                'values' => [
                    // 是否开启
                    'is_open' => false,
                    // 支付宝app_id
                    'app_id' => '',
                    // 支付宝公钥
                    'publicKey' => '',
                    // 应用私钥
                    'privateKey' => ''
                ]
            ],
            SettingEnum::TABBAR => [
                'key' => 'tabbar',
                'describe' => '底部导航',
                'values' => [
                    // 选中颜色
                    'color' => '#E2231A',
                    // 未选中颜色
                    'no_color' => '#999999',
                    // 菜单
                    'menus' => [
                        [
                            'index' => 0,
                            'text' => '首页',
                            'iconPath' => self::$base_url .'image/tabbar/home.png',
                            'selectedIconPath' => self::$base_url .'image/tabbar/home_active.png',
                        ],
                        [
                            'index' => 1,
                            'text' => '分类',
                            'iconPath' => self::$base_url .'image/tabbar/category.png',
                            'selectedIconPath' => self::$base_url .'image/tabbar/category_active.png',
                        ],
                        [
                            'index' => 2,
                            'text' => '购物车',
                            'iconPath' => self::$base_url .'image/tabbar/cart.png',
                            'selectedIconPath' => self::$base_url .'image/tabbar/cart_active.png',
                        ],
                        [
                            'index' => 3,
                            'text' => '我的',
                            'iconPath' => self::$base_url .'image/tabbar/user.png',
                            'selectedIconPath' => self::$base_url .'image/tabbar/user_active.png',
                        ],
                    ]
                ]
            ],
            SettingEnum::LIVE => [
                'key' => 'live',
                'describe' => '直播设置',
                'values' => [
                    // 自动同步
                    'auto_syn' => false,
                ],
            ],
        ];
    }
}
