<?php
namespace App\Model;

use Core\Model;

class Auth extends \Core\Model
{
    // 获取用户信息
    public function getUserInfo($email)
    {
        $sql = 'select id, email, password from user where email = :email and status = 1';
        $sth = $this->_pdo->prepare($sql);
        $sth->execute(array(':email' => $email));
        
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    // 更新secret
    public function setSecret($secret, $uid)
    {
        try {
            $sql = 'update user set secret = :secret where id = :uid';
            $sth = $this->_pdo->prepare($sql);
            $res = $sth->execute(array(':secret' => $secret, ':uid' => $uid));
            if ($res) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            print_r($e->getMessage());
        }
    }
}