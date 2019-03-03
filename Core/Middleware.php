<?php
namespace Core;

/**
 * 自定义 middleware 基类
 */
class Middleware
{
    public function return_msg($code, $msg = null)
    {
        $msg = $msg ? $msg : \Core\Config::get('middleware_msg')[$code];
        $data = [
            'code' => $code,
            'msg' => $msg
        ];
        return $data;
    }
}