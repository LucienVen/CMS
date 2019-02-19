<?php
/**
 * Model 基类
 */

 namespace Core;

 class Model
 {
     protected $_code;

     public function __construct()
     {
         $this->_code = \Core\Config::get('PDO');
     }

 }