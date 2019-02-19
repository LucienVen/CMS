<?php
/**
 * Action 基类
 */

namespace Core;

class Action
{
    /**
     * 请求类
     *
     * @var RequestInterface
     */
    protected $_request;

    /**
     * 响应类
     *
     * @var ResponseInterface
     */
    protected $_response;

    /**
     * 路由参数
     *
     * @var array
     */
    protected $_args;

    /**
     * 容器实例
     *
     * @var \Slim\Container
     */
    protected $_container;

    /**
     * 初始化函数
     */
    public function __construct(\Slim\Container $container)
    {
        $this->_container = $container;
        $this->_request = $container->get('request');
        $this->_response = $container->get('response');
        // 获取路由参数
        $this->_args = $container->get('args');
    }

    /**
     *  请求成功返回函数
     */
    public function success($data, $code=200)
    {
        return $this->_response->withJson($data, $code);
    }

    /**
     * 请求失败返回函数
     * TODO 完善错误信息系统（code => msg）
     */
    public function error($msg, $code = 400)
    {
        $data = [
            'code' => $code,
            'msg' => $msg,
        ];
        return $this->_response->withJson($data, $code);
        
    }
}
