<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/11
 * Time: 15:06
 */

namespace Illuminate;


class View
{
    protected $smarty;

    public static function _instance()
    {
        static $self;
        if (!$self) {
            $self = new self();
        }
        return $self;
    }

    public function __construct()
    {
        if (!$this->smarty) {
            $smarty = new \Smarty();
            $smarty->setTemplateDir(APP_PATH . 'app/Views');     // 设置模板存放目录
            $smarty->setCompileDir(config('app.smarty.compile_dir'));       // 设置编译文件目录
            $smarty->setCacheDir(config('app.smarty.cache_dir'));           // 设置缓存文件目录
            $smarty->setLeftDelimiter(config('app.smarty.left_delimiter')); // 设置左边界符
            $smarty->setRightDelimiter(config('app.smarty.right_delimiter'));// 设置右边界符
            $smarty->force_compile = config('app.smarty.force_compile');    // 设置是否每次都重新编译
            $smarty->debugging = config('app.smarty.debug');    // 对调用的模板进行调试
            $smarty->caching = config('app.smarty.cache');      // 是否开启缓存
            $smarty->cache_lifetime = config('app.smarty.cache_lifetime');  // 设置缓存时间
            $this->smarty = $smarty;
        }
        return $this;
    }

    public function with($key, $value)
    {
        $this->smarty->assign($key, $value);
        return $this;
    }

    public function display($tpl)
    {
        $view = $tpl . '.tpl';
        $this->smarty->display($view);
        return $this;
    }
}