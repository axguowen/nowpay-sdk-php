# Nowpay SDK for PHP

一个基于PHP的Nowpay SDK


## 安装
~~~
composer require axguowen/nowpay-sdk-php
~~~

## 使用
~~~php
use axguowen\Nowpay;

// 配置参数
$config = [
    // appid
    'appid' => 'OP00000003',
    // 商户证书序列号
    'appkey' => '00dfba8194c41b84cf',
];

// 创建微信H5支付服务
$wepayH5 = \axguowen\Nowpay::service('wepayH5', $config);
// 订单创建
$orderCreateResult = $wepayH5->counterOrderSpecialCreate('order10102032033', 1599, '订单标题', [
    'notifyUrl' => 'http://xxx.xxx.com/notify',
    'frontNotifyUrl' => 'http://xxx.xxx.com/notify',
]);
// 打印创建结果
var_dump($orderCreateResult);
~~~