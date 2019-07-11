<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/10
 * Time: 14:08
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    public $timestamps = false;
    public $guarded = [];
}