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
}
