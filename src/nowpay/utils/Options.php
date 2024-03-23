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

namespace axguowen\nowpay\utils;

class Options
{
    const BASE_URL              = 'https://pay.ipaynow.cn';
    const FUNCODE_TRADE         = 'WP001';
    const FUNCODE_QUERY         = 'MQ002';
    const FUNCODE_BACK_NOTIFY   = 'N001';
    const FUNCODE_FRONT_NOTIFY  = 'N002';
    const QUERY_STRING_EQUAL    = '=';
    const QUERY_STRING_SPLIT    = '&';

    const KEY_APPID             = 'appId';
    const KEY_FUNCODE           = 'funcode';
    const KEY_VERSION           = 'version';
    const KEY_ORDER_TYPE        = 'mhtOrderType';
    const KEY_CURRENCY_TYPE     = 'mhtCurrencyType';
    const KEY_CHARSET           = 'mhtCharset';
    const KEY_DEVICE_TYPE       = 'deviceType';
    const KEY_CHANNEL_TYPE      = 'payChannelType';
    const KEY_MHT_SIGN_TYPE     = 'mhtSignType';
    const KEY_MHT_SIGNATURE     = 'mhtSignature';
    const KEY_OUTPUT_TYPE       = 'outputType';
    const KEY_SIGNATURE         = 'signature';
    const KEY_MHT_RESERVED      = 'mhtReserved';

}
