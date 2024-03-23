<?php
// +----------------------------------------------------------------------
// | Nowpay SDK [Nowpay SDK for PHP]
// +----------------------------------------------------------------------
// | Nowpay SDK
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace axguowen\nowpay\services;

use axguowen\nowpay\utils\Options;
use axguowen\nowpay\utils\Str;

class WepayH5 extends Base
{
    /**
     * 订单创建
     * @access public
     * @param string $mhtOrderNo 商户订单号
     * @param int $mhtOrderAmt 订单金额，单位：分
     * @param string $mhtOrderName 订单名称
     * @param array $extraData 额外参数
     * @return array
     * @link https://mch.ipaynow.cn/h5Pay
     */
    public function orderCreate($mhtOrderNo, $mhtOrderAmt, $mhtOrderName, array $extraData = [])
    {
        // 配置参数
        $configParams = [
            Options::KEY_APPID => $this->options['appid'],
            Options::KEY_FUNCODE => Options::FUNCODE_TRADE,
            Options::KEY_VERSION => $this->options['version'],
            Options::KEY_ORDER_TYPE => $this->options['mht_order_type'],
            Options::KEY_CURRENCY_TYPE => $this->options['mht_currency_type'],
            Options::KEY_CHARSET => $this->options['mht_charset'],
            Options::KEY_DEVICE_TYPE => $this->options['device_type'],
            Options::KEY_CHANNEL_TYPE => '13',
            Options::KEY_MHT_SIGN_TYPE => $this->options['mht_sign_type'],
            Options::KEY_OUTPUT_TYPE => '1',
        ];
        // 如果指定了子商户编号
        if(isset($this->options['mch_bank_id'])){
            $configParams[Options::KEY_MHT_RESERVED] = 'mchBankId' . Options::QUERY_STRING_EQUAL . $this->options['mch_bank_id'] . Options::QUERY_STRING_SPLIT;
        }
        // 请求包体
        $reqData = array_merge($configParams, [
            'mhtOrderNo' => $mhtOrderNo,
            'mhtOrderAmt' => $mhtOrderAmt,
            'mhtOrderName' => $mhtOrderName,
            'mhtOrderDetail' => $mhtOrderName,
            'mhtOrderStartTime' => date('YmdHis'),
            'consumerCreateIp' => $_SERVER['REMOTE_ADDR'],
        ], $extraData);
        // 转化为字符串
        $reqStr = Str::serializeParams($this->options['appkey'], $reqData);
        // 返回请求结果
        return $this->post('', $reqStr);
    }
}