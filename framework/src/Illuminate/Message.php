<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/12
 * Time: 10:02
 */

namespace Illuminate;


class Message
{
    const KEY = 'JTSelyjNqYseR';

    public static function encode($msg)
    {
        return self::urlSafeBase64(openssl_encrypt($msg, 'des-ecb', self::KEY));
    }

    public static function decode($data)
    {
        return openssl_decrypt(self::urlSafeBase64Decode($data), 'des-ecb', self::KEY);
    }

    public static function urlSafeBase64($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function urlSafeBase64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}