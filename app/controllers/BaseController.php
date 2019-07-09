<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/9
 * Time: 15:59
 */

namespace App\controllers;


class BaseController
{

    public function jsonSuccess($data = [], $msg = 'success', $code = 0)
    {
        return app('Response')->json(compact('code', 'msg', 'data'));
    }

    public function jsonError($code = '201', $msg = '操作失败!')
    {
        return app('Response')->json(compact('code', 'msg'));
    }
}