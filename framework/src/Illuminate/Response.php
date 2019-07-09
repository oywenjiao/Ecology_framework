<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 9:24
 */

namespace Illuminate;

class Response
{
    protected $status = null;
    protected $header = [];
    protected $options = [];
    protected $type = '';
    protected $jsonp_callback = 'callback';
    protected $sended = false;

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
        $this->setContentType('text/html');
    }


    /**
     * 设置状态
     * @param $status
     * @return $this
     */
    public function status($status = 200)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 设置contentType
     * @param $content_type
     * @param string $charset
     */
    public function setContentType($content_type, $charset = 'utf-8')
    {
        $this->setHeader('Content-Type', $content_type . '; charset=' . $charset);
    }

    /**
     * 设置头信息
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        if (is_array($name)) {
            $this->header = array_merge($this->header, $name);
        } else {
            $this->header[$name] = $value;
        }
        return $this;
    }

    /**
     * 发送头信息
     */
    public function header()
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->header as $key => $value) {
                header($key . ':' . $value);
            }
        }
    }


    /**设置输出参数
     * @param array $param
     * @param mixed $value
     * @return $this
     */
    public function with($param = [], $value = null)
    {
        if (is_array($param)) {
            $this->options = array_merge($this->options, $param);
        } elseif (is_string($param)) {
            $this->options[$param] = $value;
        }
        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function json($data = [], $status = null)
    {
        $this->type = 'json';
        if ($status) {
            $this->status($status);
        }
        $this->setContentType('application/json');
        $this->with($data);
        return $this;
    }

    public function jsonp($data = [], $callback = 'callback', $status = null)
    {
        $this->type = 'jsonp';
        if ($status) {
            $this->status($status);
        }
        $this->setContentType('application/json');
        $this->with($data);
        $this->jsonp_callback = $callback;
        return $this;
    }

    /**
     * view
     * @param $tpl
     * @param null $status
     * @return $this
     * @throws \Exception
     */
    public function view($tpl, $status = null)
    {
        if (!$tpl) {
            throw new \Exception('need template');
        }
        $this->type = 'view';
        if ($status) {
            $this->status($status);
        }
        $this->setContentType('text/html');
        $view = app('view');
        if (!empty($this->options)) {
            if (is_array($this->options)) {
                foreach ($this->options as $key => $val) {
                    $view->with($key, $val);
                }
            }

        }
        $view->setTpl($tpl);
        return $this;
    }

    /**
     * 响应
     */
    public function send()
    {
        if ($this->sended) {
            return;
        }
        if (!$this->status) {
            $this->status();
        }
        $this->header();

        switch ($this->type) {
            case 'json' :
                echo json_encode($this->options);
                break;
            case 'jsonp' :
                echo($this->jsonp_callback . '(' . json_encode($this->options) . ');');
                break;
            case 'view' :
                app('view')->display();
                break;
            default :
                break;
        }
        if (function_exists('fastcgi_finish_request')) {
            // FASTCGI下提高页面响应
            fastcgi_finish_request();
        }
        $this->sended = true;
    }

    /**
     * 重定向
     * @param $url
     * @param string $msg
     * @param int $time
     * @param int $status
     */
    public function redirect($url, $msg = '', $time = 0, $status = 302)
    {
        $this->status = $status;
        $this->header();
        if (empty($msg)) {
            $msg = "redirect to  {$url} after {$time} s!";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo $msg;
            }
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
            echo $str;
        }
        $this->sended = true;
    }
}