<?php
namespace App\Model;

use Core\Model;

class Test extends \Core\Model
{
    public function getUserByEmail($email)
    {
        // $this->_pdo
        $sql = 'select * from user where email = :email';
        $sth = $this->_pdo->prepare($sql);
        $res = $sth->execute(array('email' => $email));
        return $res;
    }
}