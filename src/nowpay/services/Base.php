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

use axguowen\HttpClient;
use axguowen\nowpay\utils\Str;
use axguowen\nowpay\utils\Options;

abstract class Base
{
    /**
     * 配置参数
     * @var string
     */
    protected $options = [
        // 商户appid
        'appid' => '',
        // 商户appkey
        'appkey' => '',
        // 接口版本
        'version' => '1.0.0',
        // 设备类型
        'device_type' => '0601',
        // 渠道类型 银联：11 支付宝：12 微信：13
        'pay_channel_type' => '13',
        // 交易类型 普通消费:01 代理消费:05
        'mht_order_type' => '01',
        // 商户币种类型
        'mht_currency_type' => '156',
        // 商户字符编码
        'mht_charset' => 'UTF-8',
        // 商户签名方法
        'mht_sign_type' => 'MD5',
    ];

    /**
     * 构造方法
     * @access public
     * @param array $options 配置参数
     * @return void
     */
    public function __construct($options)
    {
        // 合并配置
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 订单查询
     * @access public
     * @param string $mhtOrderNo 商户订单号
     * @param array $extraData 额外参数
     * @return array
     * @link https://mch.ipaynow.cn/h5Pay
     */
    public function orderQuery($mhtOrderNo, array $extraData = [])
    {
        // 配置参数
        $configParams = [
            Options::KEY_APPID => $this->options['appid'],
            Options::KEY_FUNCODE => Options::FUNCODE_QUERY,
            Options::KEY_VERSION => $this->options['version'],
            Options::KEY_DEVICE_TYPE => $this->options['device_type'],
            Options::KEY_CHARSET => $this->options['mht_charset'],
            Options::KEY_MHT_SIGN_TYPE => $this->options['mht_sign_type'],
        ];
        // 请求包体
        $reqData = array_merge($configParams, [
            'mhtOrderNo' => $mhtOrderNo,
        ], $extraData);
        // 转化为字符串
        $reqStr = Str::serializeParams($this->options['appkey'], $reqData);
        // 返回请求结果
        return $this->post('', $reqStr);
    }

    /**
     * 获取通知数据
     * @access public
     * @param string $body 请求体
     * @return array
     * @link https://mch.ipaynow.cn/h5Pay
     */
    public function getNotifyData($body)
    {
        // 校验签名
        if(true !== $this->signatureVerification($body)){
            return [null, new \Exception('签名校验未通过')];
        }
        // 解析请求数据并返回
        return [Str::unserializeParams($body), null];
    }

    /**
     * 支付通知验签
     * @access protected
     * @param string $body 请求体
     * @return bool
     * @link https://mch.ipaynow.cn/h5Pay
     */
    protected function signatureVerification($body)
    {
        // 如果为空
        if(empty($body)){
            return false;
        }

        // 解析请求体
        $params = Str::unserializeParams($body);
        // 排序
        ksort($params);
        // 签名字符串
        $signature = '';
        // 遍历
        foreach($params as $key => $value) {
            if($value === '' || $key == Options::KEY_SIGNATURE) {
                continue;
            }
            $signature .= $key .  Options::QUERY_STRING_EQUAL . urldecode($value) . Options::QUERY_STRING_SPLIT;
        }
        // 拼接key
        $signature .= md5($this->options['appkey']);
        // 返回验证结果
        return $params[Options::KEY_SIGNATURE] == md5($signature);
    }

    /**
     * 发送POST请求
     * @access protected
     * @param string $path 请求接口
     * @param string $body 请求参数
     * @return array
     */
    protected function post(string $path, string $body = '')
    {
        // 发送请求
        $ret = HttpClient::post(Options::BASE_URL . $path, $body);
        if (!$ret->ok()) {
            return [null, new \Exception($ret->error, $ret->statusCode)];
        }
        // 如果响应体不为空
        if(!is_null($ret->body)){
            return [Str::unserializeParams($ret->body), null];
        }
        return [null, new \Exception('响应体为空', 400)];
    }
}
