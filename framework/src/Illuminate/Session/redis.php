<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/11
 * Time: 10:43
 */

namespace Illuminate\Session;

class redis extends \SessionHandler
{
    /** @var \Redis $client */
    protected $client;
    protected $left_time;

    public function __construct()
    {
        $this->left_time = config('app.session_lefttime');
    }

    protected function client()
    {
        if (!$this->client) {
            $name = !empty(config('app.session_redis_dir')) ? config('app.session_redis_dir') : 'default';
            $this->client = \Illuminate\Redis::_instance($name);
        }
        return $this->client;
    }

    public function close()
    {
        return true;
    }

    public function read($session_id)
    {
        return $this->client()->get($session_id) ?: '';
    }

    public function write($session_id, $data)
    {
        $this->client()->setex($session_id, $this->left_time, $data);
        return true;
    }

    public function destroy($session_id)
    {
        $this->client()->del($session_id);
    }

}