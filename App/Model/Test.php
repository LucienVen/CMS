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

    public function insertExcel($data)
    {
        $sql_pre = 'insert into subject_review(department, teacher, title, result, mark, reviewer) values ';

        $sql_value = '';
        foreach($data as $value){
            $str = "('" . $value['department'] . "','" . $value['teacher'] . "','" . $value['title'] . "','" . $value['result'] . "','" . $value['mark'] . "','" . $value['reviewer'] . "'),";
            $sql_value .= $str;
        }

        
        $sql = $sql_pre . rtrim($sql_value, ',');
        $res = $this->_pdo->exec($sql);
        return $res;
    }
}