<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 15:21
 */

namespace App\Controllers;

use App\Models\User;
use Illuminate\Request;

class IndexController extends BaseController
{

    public function index()
    {
        echo 'Hello World!';
    }

    public function handle(Request $request, $id)
    {
        $res = session('user_id', 12);
        dd($res);
        $result = User::create([
            'name'      => "李四",
            'mobile'    => '13433574286',
            'created'   => time(),
            'updated'   => time()
        ]);
        if ($result->id) {
            return $this->jsonSuccess(['user_id' => $result->id], '添加成功');
        }
        return $this->jsonError('数据存储失败');
    }
}