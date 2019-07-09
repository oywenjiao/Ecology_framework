<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/6
 * Time: 11:50
 */

use Illuminate\Route;

Route::get('handle/(:num)', 'IndexController@handle');
Route::get('', 'IndexController@index');

Route::error(function () {
    app('Response')->status('404');
    echo "<div style='font-size: 36px;color: #636b6f;font-family: serif, sans-serif;text-align: center;padding: 100px;margin-top: 10%;'>Sorry, the page you are looking for could not be found.</div>";
});
Route::dispatch();