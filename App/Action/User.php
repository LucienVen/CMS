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

        $userModel = new UserModel();
        $res = $userModel->registered($pdo, $email, $password);

        if (100 != $res['code']) {
            return $this->error($res['msg'], $res['code']);
        }
        // $data = [
        //     'email' => $email,
        //     'password' => $password,
        // ];
        $data = [
            'msg' => $res['msg'],
            'code' => $res['code'],
        ];
        return $this->success($data, 200);
    }
}
