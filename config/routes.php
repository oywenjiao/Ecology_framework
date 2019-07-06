<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 11:50
 */

use Illuminate\Route;

Route::get('', 'IndexController@index');
Route::get('test', 'IndexController@test');

Route::dispatch();