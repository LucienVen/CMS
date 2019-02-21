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
    public function success($code=200, $data = [])
    {
        // return $this->_response->withJson($data, $code);
        $returnData = [
            'code' => $code,
            'msg' => \Core\Config::get('status')['code'],
        ];

        if (!empty($data)) {
            $returnData['data'] = $data;
        }

        return $this->_response->withJson($returnData, 200);
    }

    /**
     * 请求失败返回函数
     * TODO 完善错误信息系统（code => msg）
     */
    public function error($code = 400)
    {
        $returnData = [
            'code' => $code,
            'msg' => \Core\Config::get('status')['code'],
        ];

        return $this->_response->withJson($returnData, 400);
        
    }
}
