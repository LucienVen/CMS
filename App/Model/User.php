<?php
namespace App\Model;
use Core\Model;
class User extends \Core\Model
{
    public function registered($dbh, $email, $password)
    {
        $sql = 'insert into user(email, password, status) value (:email, :password, :status)';
        $sth = $dbh->prepare($sql);
        $result = $sth->execute(array(
            ':email' => $email,
            ':password' => $password,
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
}
