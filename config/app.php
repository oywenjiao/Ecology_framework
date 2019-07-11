<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/5
 * Time: 14:43
 */
return [
    'session'           => 'redis',     // session存放 为空表示用自带的, '' || redis , 具体看Illuminate/Session目录中的文件
    'session_table'     => 'session',   // session 存放在mysql中的表名
    'session_lefttime'  => 3600 * 24,   // session有效时间
    'session_dir'       => RUNTIME_PATH . 'session/',   // session 存放的文件路径
    'session_redis_dir' => 'default',   // session 使用的redis配置
    'debug'             => env('APP_DEBUG', false), // 调试模式
    'view'              => 'smarty',     // 模板 smarty||native
    'smarty'            => [             // smarty配置
        'debug'           => env('SMARTY_DEBUG', false),            // 是否弹出debug窗口
        'force_compile'   => env('SMARTY_FORCE_COMPILE', false),    // 检查模板是否改动,开发时打开,正式 关闭
        'cache'           => env('SMARTY_CACHE', true),             // 是否缓存
        'cache_lifetime'  => 1200,       // 缓存时间
        'cache_dir'       => RUNTIME_PATH . 'smarty/cache/',          // 缓存目录
        'compile_dir'     => RUNTIME_PATH . 'smarty/templates_c/',    // 编译目录
        'left_delimiter'  => '{',      // 左定界符
        'right_delimiter' => '}'       // 右定界符
    ],
    'cache'             => 'file',       // 缓存类型，file||redis
    'cache_expire'      => 1200,         // 缓存时间
    'cache_file_dir'    => RUNTIME_PATH . 'cache/', // 缓存目录
    'cache_redis_dir'   => 'default',   // cache 使用的redis配置
    'sys_log'           => env('SYS_LOG', true),            // 系统日志是否开启
    'sys_log_level'     => env('SYS_LOG_LEVEL', 'debug'),   // 系统日志级别
    'sys_error_log'     => env('SYS_ERROR_LOG', true)       // 系统错误日志是否开启
];