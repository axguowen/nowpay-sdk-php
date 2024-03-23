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

namespace axguowen;

use axguowen\nowpay\utils\Str;

/**
 * 现在SDK入口文件
 */
class Nowpay
{
    /**
     * 创建的服务集合
     * @var static
     */
    protected static $services = [];

    /**
     * 静态创建服务
     * @access public
     * @param string $name 服务名称
     * @param array $options 配置参数
     * @return \axguowen\nowpay\services\Base
     */
    public static function service($name, array $options = [])
    {
        // 如果服务名称为空
        if(empty($name)){
            throw new \InvalidArgumentException('Invalid service name.');
        }
        // 构造缓存键
        $key = md5($name . serialize($options));
        // 从缓存获取
        if (isset(static::$services[$key])){
            return static::$services[$key];
        }
        // 获取服务类名
        $serviceClass = __NAMESPACE__ . '\\nowpay\\services\\' . Str::studly($name);
        // 如果服务类不存在
        if (!class_exists($serviceClass)){
            throw new \InvalidArgumentException(sprintf('Service "%s" not exists.', $name));
        }
        // 返回
        return static::$services[$key] = new $serviceClass($options);
    }
}