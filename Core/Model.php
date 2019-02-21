<?php
/**
 * Model 基类
 */

 namespace Core;

 class Model
 {
     protected $_code;
     protected $_pdo;

     public function __construct($pdo)
     {
         $this->_pdo = $pdo;
         $this->_code = \Core\Config::get('PDO');
     }

 }