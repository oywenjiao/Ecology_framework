<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/5
 * Time: 14:43
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class bootstrap
 * 项目入口文件
 * 其完成的工作内容有以下几点
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架
 */
class bootstrap
{

    public static function start()
    {
        define('APP_START', microtime(true));
        // 系统初始化
        self::init();
        // 环境配置
        self::env();
        // session系统
        self::session();
        // 数据库载入
        self::database();
        // 错误提示
        self::exception();
        //cli模式不载入路由
        IS_CLI OR (require_once APP_PATH . '/config/routes.php');
        //响应
        app('Response')->send();
        // 日志
        self::log();
        define('APP_END', microtime(true));
    }

    /**
     * 定义相关配置常量
     */
    public static function init()
    {
        //默认时区定义
        date_default_timezone_set('Asia/Shanghai');
        //设置默认区域
        setlocale(LC_ALL, "zh_CN.utf-8");
        //设置根路径
        defined('APP_PATH') or define('APP_PATH', __DIR__ . '/../');
        //设置web根路径
        defined('WEB_ROOT') or define('WEB_ROOT', APP_PATH . 'public/');
        //设置runtime路径
        defined('RUNTIME_PATH') or define('RUNTIME_PATH', APP_PATH . 'runtime/');
        //系统日志路径
        defined('SYSTEM_LOG_PATH') or define('SYSTEM_LOG_PATH', APP_PATH . 'runtime/log/system/');
        //是否是命令行模式
        defined('IS_CLI') or define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
    }

    //载入配置
    public static function env()
    {
        if ($env = get_cfg_var("custom_env")) {
            $dotenv = new Dotenv\Dotenv(APP_PATH, '.env.' . $env);
        } else {
            $dotenv = new Dotenv\Dotenv(APP_PATH);
        }
        $dotenv->load();
    }

    public static function session()
    {
        $session_driver = config('app.session');
        if ($session_driver) {
            $class = 'Illuminate\\Session\\' . $session_driver;
            $handler = new $class;
            session_set_save_handler(
                array(&$handler, "open"),
                array(&$handler, "close"),
                array(&$handler, "read"),
                array(&$handler, "write"),
                array(&$handler, "destroy"),
                array(&$handler, "gc")
            );
        } else {
            ini_set('session.gc_maxlifetime', config('app.session_lefttime'));
        }
        register_shutdown_function('session_write_close');
    }

    //Eloquent ORM 模型配置
    public static function database()
    {
        $capsule = new Capsule;
        // 创建链接
        $capsule->addConnection(require APP_PATH . '/config/database.php');
        // 设置全局静态可访问
        $capsule->setAsGlobal();
        // 启动Eloquent
        $capsule->bootEloquent();
    }

    // 错误提示
    public static function exception()
    {
        $whoops = new \Whoops\Run;

        //错误信息,调试模式打开则显示,否则只记录到日志
        if (config('app.debug')) {
            $whoops_handler = new \Whoops\Handler\PrettyPageHandler;
            $whoops->pushHandler($whoops_handler);
        }

        if (config('app.sys_error_log')) {
            $error_log = new Logger('SYS_ERROR');
            $error_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . 'error.log', Logger::ERROR));

            $whoops_log_handler = new \Whoops\Handler\PlainTextHandler($error_log);
            $whoops_log_handler->loggerOnly(true);
            $whoops->pushHandler($whoops_log_handler);
        }
        $whoops->register();
    }

    // 系统日志
    public static function log()
    {
        if (config('app.sys_log')) {
            $info_log = new Logger('SYS_LOG');
            $level = config('app.sys_log_level');
            $info_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . date('Y-m') . '/' . date('d') . '.log', $level));
            $request = app('Request');
            $info_log->$level('request_info:', [
                'ip'     => $request->getClientIp(),
                'method' => $request->method(),
                'uri'    => $request->uri()
            ]);
        }
    }
}