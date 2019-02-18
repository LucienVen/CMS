<?php
/**
 * test action
 */
namespace App\Action;

use Core\Action;

class Test extends \Core\Action
{
    /**
     * 测试获取参数与json响应放回
     * @router \hello\{name}
     */
    public function base()
    {
        $name = $this->_args['name'];
        $data = ['name' => $name];
        return $this->_response->withJson($data, 200);
    }

    /**
     * 测试PDO连接，已经容器使用（PDO以及Monolog）
     */
    public function link()
    {
        $pdo = $this->_container->get('pdo');
        // test logger
        $logger = $this->_container->get('logger');
        $logger->addInfo("test");

        // 测试pdo链接
        if (!$pdo) {
            return $this->_response->withJson(['msg: db connect error!'], 500);
        }

        $sql = 'select * from user';
        $stmt = $pdo->query($sql);
        // print_r($stmt->fetchAll());
        $data['pdo'] = $stmt->fetchAll();
        return $this->_response->withJson($data, 200);
    }

    /**
     * 测试自定义中间件使用
     */
    public function middle_test()
    {
        return $this->_response->write('Hello, World!');
    }

}