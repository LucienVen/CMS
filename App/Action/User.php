<?php
namespace App\Action;

use Core\Action;

class User extends \Core\Action
{

    /**
     * test action
     *
     * @route \hello\[name]
     */
    public function test()
    {
        // $data = [
        //     '_request' => $this->_request,
        //     '_response' => $this->_response,
        //     '_args' => $this->_args,
        // ];

        $name = $this->_args['name'];
        $data = ['name' => $name];

        return $this->_response->withJson($data, 200);
    }

    // public function linkMysql()
    // {
    //     $data['id'] = $this->_args['id'];
    //     return $this->_response->withJson($data, 200);
    // }
}
