<?php
namespace App\Model;
use Core\Model;
class User extends \Core\Model
{
    public function registered($email, $password)
    {
        $sql = 'insert into user(email, password, status) value (:email, :password, :status)';
        $sth = $this->_pdo->prepare($sql);
        $result = $sth->execute(array(
            ':email' => $email,
            ':password' => $this->setPasswordHash($password),
            ':status' => 1,
        ));
        // return $sth->fetchAll();
        if (!$result) {
            $data = [
                'code' => 101,
                'msg' => $this->_code['101'],
            ];
            return $data;
        }

        return ['code' => 100, 'msg' => $this->_code['100']];
    }

    // 创建密码的哈希值
    protected function setPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }
}
