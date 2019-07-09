<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 15:21
 */

namespace App\controllers;

use Illuminate\Request;

class IndexController extends BaseController
{

    public function index()
    {
        echo 'Hello World!';
    }

    public function handle(Request $request, $id)
    {
        return $this->jsonSuccess(['id' => $id]);
    }
}