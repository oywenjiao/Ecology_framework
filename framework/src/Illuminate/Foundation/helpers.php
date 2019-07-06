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