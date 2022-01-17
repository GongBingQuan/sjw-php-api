<?php

namespace app\shop\service\order;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
/**
 * 订单导出服务类
 */
class ExportService
{
    /**
     * 订单导出
     */
    public function orderList($list)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //列宽
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('P')->setWidth(30);

        //设置工作表标题名称
        $sheet->setTitle('订单明细');

        $sheet->setCellValue('A1', '订单号');
        $sheet->setCellValue('B1', '商品信息');
        $sheet->setCellValue('C1', '订单总额');
        $sheet->setCellValue('D1', '优惠券抵扣');
        $sheet->setCellValue('E1', '积分抵扣');
        $sheet->setCellValue('F1', '运费金额');
        $sheet->setCellValue('G1', '后台改价');
        $sheet->setCellValue('H1', '实付款金额');
        $sheet->setCellValue('I1', '支付方式');
        $sheet->setCellValue('J1', '下单时间');
        $sheet->setCellValue('K1', '买家');
        $sheet->setCellValue('L1', '买家留言');
        $sheet->setCellValue('M1', '配送方式');
        $sheet->setCellValue('N1', '自提门店名称');
        $sheet->setCellValue('O1', '自提联系人');
        $sheet->setCellValue('P1', '自提联系电话');
        $sheet->setCellValue('Q1', '收货人姓名');
        $sheet->setCellValue('R1', '联系电话');
        $sheet->setCellValue('S1', '收货人地址');
        $sheet->setCellValue('T1', '物流公司');
        $sheet->setCellValue('U1', '物流单号');
        $sheet->setCellValue('V1', '付款状态');
        $sheet->setCellValue('W1', '付款时间');
        $sheet->setCellValue('X1', '发货状态');
        $sheet->setCellValue('Y1', '发货时间');
        $sheet->setCellValue('Z1', '收货状态');
        $sheet->setCellValue('AA1', '收货时间');
        $sheet->setCellValue('AB1', '订单状态');
        $sheet->setCellValue('AC1', '微信支付交易号');
        $sheet->setCellValue('AD1', '是否已评价');
        //填充数据
        $index = 0;
        foreach ($list as $order) {
            $address = $order['address'];
            $sheet->setCellValue('A'.($index + 2), "\t".$order['order_no']."\t");
            $sheet->setCellValue('B'.($index + 2), $this->filterProductInfo($order));
            $sheet->setCellValue('C'.($index + 2), $order['total_price']);
            $sheet->setCellValue('D'.($index + 2), $order['coupon_money']);
            $sheet->setCellValue('E'.($index + 2), $order['points_money']);
            $sheet->setCellValue('F'.($index + 2), $order['express_price']);
            $sheet->setCellValue('G'.($index + 2), "{$order['update_price']['symbol']}{$order['update_price']['value']}");
            $sheet->setCellValue('H'.($index + 2), $order['pay_price']);
            $sheet->setCellValue('I'.($index + 2), $order['pay_type']['text']);
            $sheet->setCellValue('J'.($index + 2), $order['create_time']);
            $sheet->setCellValue('K'.($index + 2), $order['user']['nickName']);
            $sheet->setCellValue('L'.($index + 2), $order['buyer_remark']);
            $sheet->setCellValue('M'.($index + 2), $order['delivery_type']['text']);
            $sheet->setCellValue('N'.($index + 2), !empty($order['extract_store']) ? $order['extract_store']['shop_name'] : '');
            $sheet->setCellValue('O'.($index + 2), !empty($order['extract']) ? $order['extract']['linkman'] : '');
            $sheet->setCellValue('P'.($index + 2), !empty($order['extract']) ? $order['extract']['phone'] : '');
            $sheet->setCellValue('Q'.($index + 2), $order['address']['name']);
            $sheet->setCellValue('R'.($index + 2), $order['address']['phone']);
            $sheet->setCellValue('S'.($index + 2), $address ? $address->getFullAddress() : '');
            $sheet->setCellValue('T'.($index + 2), $order['express']['express_name']);
            $sheet->setCellValue('U'.($index + 2), $order['express_no']);
            $sheet->setCellValue('V'.($index + 2), $order['pay_status']['text']);
            $sheet->setCellValue('W'.($index + 2), $this->filterTime($order['pay_time']));
            $sheet->setCellValue('X'.($index + 2), $order['delivery_status']['text']);
            $sheet->setCellValue('Y'.($index + 2), $this->filterTime($order['delivery_time']));
            $sheet->setCellValue('Z'.($index + 2), $order['receipt_status']['text']);
            $sheet->setCellValue('AA'.($index + 2), $this->filterTime($order['receipt_time']));
            $sheet->setCellValue('AB'.($index + 2), $order['order_status']['text']);
            $sheet->setCellValue('AC'.($index + 2), $order['transaction_id']);
            $sheet->setCellValue('AD'.($index + 2), $order['is_comment'] ? '是' : '否');
            $index ++;
        }

        //保存文件
        $filename = iconv("UTF-8","GB2312//IGNORE", '订单'). '-' . date('YmdHis') . '.xlsx';


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * 格式化商品信息
     */
    private function filterProductInfo($order)
    {
        $content = '';
        foreach ($order['product'] as $key => $product) {
            $content .= ($key + 1) . ".商品名称：{$product['product_name']}\n";
            !empty($product['product_attr']) && $content .= "　商品规格：{$product['product_attr']}\n";
            $content .= "　购买数量：{$product['total_num']}\n";
            $content .= "　商品总价：{$product['total_price']}元\n\n";
        }
        return $content;
    }


    /**
     * 日期值过滤
     */
    private function filterTime($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

}