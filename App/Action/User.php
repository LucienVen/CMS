<?php
namespace App\Action;

use Core\Action;
use App\Model\User as UserModel;

class User extends \Core\Action
{
    

    public function registered()
    {
        $pdo = $this->_container->get('pdo');
        $email = $this->_request->getParam('email');
        $password = $this->_request->getParam('password');
        $password_again = $this->_request->getParam('password_again');

        // 判断两次输入密码相等
        if ($password !== $password_again) {
            // return 
        }

        $userModel = new UserModel($this->_container->get('pdo'));
        $res = $userModel->registered($email, $password);

        if ($res) {
            return $this->success(261);
        }

        return $this->error(461);
        // if (100 != $res['code']) {
        //     return $this->error($res['msg'], $res['code']);
        // }
        // $data = [
        //     'email' => $email,
        //     'password' => $password,
        // ];
        // $data = [
        //     'msg' => $res['msg'],
        //     'code' => $res['code'],
        // ];
        // return $this->success($data, 200);
    }
}
