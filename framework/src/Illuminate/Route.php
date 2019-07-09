<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 14:11
 */

namespace Illuminate;

class Route
{
    public static $routes = [];
    public static $patterns = [
        ':any' => '[^/]+',
        ':str' => '[0-9a-zA-Z_]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    ];
    public static $controller_namespace = 'App\\controllers\\';
    public static $error_callback;

    public static function __callstatic($method, $params)
    {
        $uri = '/' . ltrim($params[0], '/');
        $middleware = isset($params[2]) ? $params[1] : null;
        $callback = isset($params[2]) ? $params[2] : $params[1];
        self::$routes[strtoupper($method)][$uri] = [$middleware, $callback];
    }

    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    public static function dispatch()
    {
        // 获取请求URI
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // 获取请求方式
        $method = $_SERVER['REQUEST_METHOD'];
        if (isset(self::$routes['ANY'][$uri])) {
            self::$routes[$method] = array_merge(self::$routes['ANY'], self::$routes[$method]);
        }
        $found = false;
        // 是否直接匹配到路由
        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            $found = true;

            /**
             * $middleware = $route[0] // 中间件处理
             * $controller = $route[1] // 控制器相关
             */
            self::action($route[1], $route[0]);
        }
        // 匹配失败，进行正则匹配
        if ($found === false) {
            $searches = array_keys(static::$patterns);
            $replaces = array_values(static::$patterns);
            if (isset(self::$routes[$method])) {
                foreach (self::$routes[$method] as $route_uri => $route) {
                    // 匹配路由中是否存在 ":" 规则
                    if (strpos($route_uri, ':') !== false) {
                        $route_uri = str_replace($searches, $replaces, $route_uri);
                    }
                    if (preg_match('!^' . $route_uri . '$!', $uri, $matched)) {
                        $found = true;
                        array_shift($matched);
                        self::action($route[1], $route[0], $matched);
                        break;
                    }
                }
            }
        }
        // 未匹配到路由
        if ($found === false) {
            if (!self::$error_callback) {
                self::$error_callback = function () {
                    http_response_code(404);
                    echo '404 Not Found!';
                };
            } else {
                if (is_string(self::$error_callback)) {
                    self::$method($_SERVER['REQUEST_URI'], self::$error_callback);
                    self::$error_callback = null;
                    self::run();
                    return;
                }
            }
            call_user_func(self::$error_callback);
        }
    }


    public static function action($controller, $middleware = null, $matched = [])
    {
        if ($middleware) {
            // 存在中间件进行中间件逻辑处理
        }
        if ($controller instanceof \Closure) {
            // controller 为闭包函数
            if (!empty($matched)) {
                $controller(...$matched);
            } else {
                $controller();
            }
        } else {
            list($controller_class, $controller_method) = explode('@', $controller);
            $controller_class = self::$controller_namespace . $controller_class;
            $controller_obj = new $controller_class();
            if (!empty($matched)) {
                $request = app('Request');
                $controller_obj->$controller_method($request, ...$matched);
            } else {
                $controller_obj->$controller_method();
            }
        }
    }
}