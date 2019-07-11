<?php

/**获取配置参数
 * @param $key
 * @param null $value
 * @return null
 * @throws Exception
 */
if (!function_exists('config')) {
    function &config($key, $value = null)
    {
        static $config;
        if ($key == '') {
            return $config;
        }
        $key_arr = explode('.', $key);
        $key_len = count($key_arr);
        if (!isset($config[$key_arr[0]]) || !$config[$key_arr[0]]) {
            if (file_exists(APP_PATH . 'config/' . $key_arr[0] . '.php')) {
                $config[$key_arr[0]] = require APP_PATH . 'config/' . $key_arr[0] . '.php';
            } else {
                return null;
            }
        }
        if ($value !== null) {
            switch ($key_len) {
                case 1:
                    $config[$key_arr[0]] = $value;
                    return true;
                    break;
                case 2:
                    $config[$key_arr[0]][$key_arr[1]] = $value;
                    return true;
                    break;
                case 3:
                    $config[$key_arr[0]][$key_arr[1]][$key_arr[2]] = $value;
                    return true;
                    break;
                default:
                    return false;
                    break;
            }
        }
        switch ($key_len) {
            case 1:
                return $config[$key_arr[0]];
                break;
            case 2:
                return $config[$key_arr[0]][$key_arr[1]];
                break;
            case 3:
                return $config[$key_arr[0]][$key_arr[1]][$key_arr[2]];
                break;
            default:
                return null;
                break;
        }
    }
}

/**单例实例化
 * @param $name
 * @return mixed
 */
if (!function_exists('app')) {
    function app($name, $conf = null)
    {
        $class = 'Illuminate\\' . $name;
        if ($conf) {
            return $class::_instance($conf);
        }
        return $class::_instance();
    }
}

if (!function_exists('redis')) {
    /**
     * @param null $name
     * @return \Redis
     */
    function redis($name = null)
    {
        return \Illuminate\Redis::_instance($name);
    }
}

if (!function_exists('session')) {
    /**
     * 设置、获取session
     * @param $name string 键名
     * @param null $value string 值
     * @return mixed
     */
    function session($name, $value = null)
    {
        if (config('app.session_dir') && !config('app.session')) {
            is_dir(config('app.session_dir')) OR mkdir(config('app.session_dir'), 0777, true);
            session_save_path(config('app.session_dir'));   // 设置session存储路径
        }
        session_start();
        if ($value !== null) {
            if (empty($value)) {
                unset($_SESSION[$name]);
            } else {
                $_SESSION[$name] = $value;
            }
        }
        session_write_close();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
}


function debugLog($message = '', $data = [])
{
    appLog('debug', $message, $data);
}

function infoLog($message = '', $data = [])
{
    appLog('info', $message, $data);
}

function errorLog($message = '', $data = [])
{
    appLog('error', $message, $data);
}

function appLog($level, $message = '', $data = [])
{
    $info_log = new \Monolog\Logger('APP_LOG');
    $info_log->pushHandler(new \Monolog\Handler\StreamHandler(RUNTIME_PATH . 'log/app/' . date('Y-m-d') . '.log', $level));
    $info_log->$level($message, $data);
}