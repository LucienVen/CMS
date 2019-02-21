<?php
namespace App\Model;

use Core\Model;

class Auth extends \Core\Model
{
    // 获取用户信息
    public function getUserInfo($email)
    {
        $sql = 'select * from user where email = :email';
        $sth = $this->_pdo->prepare($sql);
        $sth->execute(array(':email' => $email));
        
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }
}