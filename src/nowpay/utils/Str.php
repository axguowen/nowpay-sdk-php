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

class Str
{
    /**
     * 反序列化鉴权信息
     * @access public
     * @param string $str
     * @return array
     */
    public static function unserializeParams($str): array
    {
        // 要存返回的数据
        $resultData = [];
        // 分隔为数组
        $items = explode(Options::QUERY_STRING_SPLIT, $str);
        // 遍历
        foreach($items as $item) {
            // 如果为空
            if(empty($item)){
                continue;
            }
            // 继续分隔
            $param = explode(Options::QUERY_STRING_EQUAL, $item);
            // 如果为空
            if(empty($param[0]) || !isset($param[1])){
                continue;
            }
            // 存储参数
            $resultData[$param[0]] = $param[1];
        }
        // 返回
        return $resultData;
    }

    /**
     * 请求参数转换为字符串
     * @access public
     * @param string $key
     * @param array $params
     * @return string
     */
	public static function serializeParams(string $key, array $params = []): string
	{
		// 解析参数
		$data = static::filterParams($params);
		// 构造签名
		$signature = static::createSignature($key, $params);
		// 返回的结果
		$str = '';
		// 遍历
		foreach($data as $k => $v) {
			// 不为空
			if($v !== '') {
				// 拼接
				$str .= $k . Options::QUERY_STRING_EQUAL . urlencode($v) . Options::QUERY_STRING_SPLIT;
			}
		}
		$str .= Options::KEY_MHT_SIGNATURE . Options::QUERY_STRING_EQUAL . $signature;
		// 返回
		return $str;
	}

    /**
     * 过滤参数
     * @access public
     * @param array $params
     * @return array
     */
	public static function filterParams(array $params = []): array
	{
		// 要返回的结果
		$result = [];
		// 获取参数中的功能码
		$funcode = $params[Options::KEY_FUNCODE];
		// 遍历参数
		foreach($params as $key => $value) {
			// 如果是支付
			if($funcode == Options::FUNCODE_TRADE){
				// 如果是签名
				if($key == Options::KEY_MHT_SIGNATURE || $key == Options::KEY_SIGNATURE){
					continue;
				}
				$result[$key] = $value;
				continue;
			}
			// 如果是通知
            if(($funcode == Options::FUNCODE_BACK_NOTIFY || $funcode == Options::FUNCODE_FRONT_NOTIFY)){
				// 如果是签名
				if($key == Options::KEY_SIGNATURE){
					continue;
				}
                $result[$key] = $value;
                continue;
            }
			// 如果是查询
            if($funcode == Options::FUNCODE_QUERY){
				// 如果是签名
				if($key == Options::KEY_MHT_SIGNATURE || $key == Options::KEY_SIGNATURE){
					continue;
				}
                $result[$key] = $value;
                continue;
			}
		}
		return $result;
	}
	
    /**
     * 构造签名
     * @access public
     * @param string $key
     * @param array $params
     * @return string
     */
	public static function createSignature($key, $params): string
	{
		// 解析参数
		$data = static::filterParams($params);
		// 排序
		ksort($data);
		// 要返回的结果
		$str = '';
		// 遍历参数
		foreach($data as $k => $v) {
			// 不为空
			if($v !== '') {
				$str .= $k . Options::QUERY_STRING_EQUAL . $v . Options::QUERY_STRING_SPLIT;
			}
		}
		// 拼接密钥
		$str .= strtolower(md5($key));
		// 返回签名
		return strtolower(md5($str));
	}

    /**
     * 下划线转驼峰(首字母大写)
     * @access public
     * @param string $value
     * @return string
     */
    public static function studly(string $value): string
    {
        // 把下划线转驼峰
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        // 过滤空格
        return str_replace(' ', '', $value);
    }
}