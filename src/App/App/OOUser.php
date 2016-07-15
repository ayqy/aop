<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2013, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\App;


/**
 * OO style
 */
class OOUser {

    private function isGranted($auth) {
        //...
        return true;
    }

    private function log($info) {
        //...
        echo $info . '<br>';
    }

    private function insertUser($user) {
        //...
        $this->log('user inserted');
    }

    public function add($fields) {
        // check auth
        if (!$this->isGranted('ADD_USER')) {
            throw new Exception("Access Denied");
        }

        // log
        $this->log('creating user');

        // create user
        try {
            $user = array('id' => '003');
            $user['name'] = 'user';
            //...
            
            // save
            $this->insertUser($user);
        } catch(Exception $e) {
            $this->log('user create error: ' + $e);
            // handleError($e);
        }

        // log
        $this->log('user created');
    }
}