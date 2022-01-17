<?php

namespace app\shop\model\plus\giftpackage;

use app\common\model\plus\giftpackage\Code as CodeModel;

/**
 * 礼包购码
 */
class Code extends CodeModel
{
    /**
     * 生成码,code生成规则，活动id+下标
     */
    public function geneCode($gift_package_id, $code_type, $total_num){
        // 一批一码
        if($code_type == 10){
            $this->save([
                'gift_package_id' => $gift_package_id,
                'code' => $gift_package_id . '-1',
                'app_id' => self::$app_id
            ]);
        }else{
            // 当前码数量
            $count = $this->where('gift_package_id', '=', $gift_package_id)->count();
            $data = [];
            for($i=0;$i<$total_num;$i++){
                $data[] = [
                    'gift_package_id' => $gift_package_id,
                    'code' => $gift_package_id . '-' . ($count + 1 + $i),
                    'app_id' => self::$app_id
                ];
            }
            $this->saveAll($data);
        }
    }
}