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
    public static $controller_namespace = 'App\\controllers\\';

    public static function __callstatic($method, $params)
    {
        $uri = '/' . ltrim($params[0], '/');
        $middleware = isset($params[2]) ? $params[1] : null;
        $callback = isset($params[2]) ? $params[2] : $params[1];
        self::$routes[strtoupper($method)][$uri] = [$middleware, $callback];
    }

    public static function dispatch()
    {
        // 获取请求URI
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // 获取请求方式
        $method = $_SERVER['REQUEST_METHOD'];
        $found = false;
        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            $found = true;

            /**
             * $middleware = $route[0] // 中间件处理
             * $controller = $route[1] // 控制器相关
             */
            self::action($route[1], $route[0]);
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
                $controller_obj->$controller_method(...$matched);
            } else {
                $controller_obj->$controller_method();
            }
        }
    }
}