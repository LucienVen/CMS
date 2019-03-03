<?php
namespace App\Action;

use Core\Action;
use App\Model\Auth as AuthModel;

use \Firebase\JWT\JWT;

class Auth extends \Core\Action
{
    protected $jwt;
    protected $token;

    public function login()
    {
        try {
            // 接收post请求参数
            $email = $this->_request->getParam('email');
            $password = $this->_request->getParam('password');

            // 从数据库中获取加密后的密码
            $AuthModel = new AuthModel($this->_container->get('pdo'));
            $userInfo = $AuthModel->getUserInfo($email);
            
            // TODO do...while... 判断数据库数据真实唯一
            $secret = uniqid($userInfo['email'] . '.');
            
            // return $this->success($userInfo, 200);
            if (password_verify($password, $userInfo['password'])) {
                unset($userInfo['password']);
                $userInfo['secret'] = $secret;
                
                // 把token添加到请求头上
                $this->_response = $this->_response->withAddedHeader('Authorization', $this->getToken($userInfo));
                if ($this->_response->hasHeader('Authorization')) {
                    // 更新 secret
                    if ($AuthModel->setSecret($secret, $userInfo['id'])) {
                        return $this->success(251);    
                    }
                }
                return $this->error(452);
            }else {
                // $data = [
                //     'code' => 400,
                //     'msg' => 'login error',
                // ];
                return $this->error(452);
            }
        } catch (\Exception $e) {
            // print_r($e);
            return false;
        }
    }

    /**
     * get Token 生成token
     */
    protected function getToken($userData)
    {
        $jwt_setting = \Core\Config::get('JWT');
        $this->jwt = array(
            'iss' => $jwt_setting['iss'],
            'aud' => $jwt_setting['aud'],
            'exp' => time() + 3600 * 24,
            // 'uid' => $userData['id'],
            // 'email' => $userData['email']
            'data' => $userData,
        );

        // $secret = uniqid($userData['email'] . '.');
        return JWT::encode($this->jwt, $jwt_setting['jwt_key']);
        // return JWT::encode($this->jwt, $secret);
    }
}
