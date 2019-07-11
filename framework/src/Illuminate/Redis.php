<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/10
 * Time: 10:38
 */

namespace Illuminate;


class Redis
{
    protected $config;
    protected $client;

    public static function _instance($name = 'default')
    {
        $name = $name ?: 'default';
        static $store;  // 定义存储空间
        if (!isset($store[$name]) || !$store[$name]) {
            // 不存在知道名称的存储空间
            $store[$name] = new static($name);
        }
        return $store[$name];
    }

    public function __construct($name)
    {
        $this->config = config("redis.{$name}");
    }

    /**
     * redis 链接操作
     * @return $this
     */
    public function connect()
    {
        if (!$this->client) {
            $config = $this->config;
            $this->client = new \Redis();
            $this->client->connect($config['host'], $config['port']);
            // 配置文件中设置了密码 则进行密码验证
            if (!empty($config['password'])) {
                $this->client->auth($config['password']);
            }
            // 配置文件中设置了redis库 则进行库选择
            if (!empty($config['database'])) {
                $this->client->select($config['database']);
            }
            // 配置文件中设置了表前缀 则进行表前缀配置
            if (!empty($config['prefix'])) {
                $this->client->setOption(\Redis::OPT_PREFIX, $config['prefix']);
            }
        }
        return $this;
    }

    public function disConnect()
    {
        if ($this->client) {
            $this->client = null;
        }
    }

    /**
     * 调用不存在的方法时进行重载处理
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->connect()->client->$name(...$arguments);
    }

    /**
     * 调用本类不存在的静态方法时进行重载处理
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::_instance()->$name(...$arguments);
    }

}