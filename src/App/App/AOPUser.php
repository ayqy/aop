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

use Exception;

use App\Annotation\Loggable;

/**
 * AOP style
 */
class AOPUser {

    public function isGranted($auth) {
        //...
        return true;
    }

    /**
     * Log
     *
     * @Loggable
     * 
     * @param  Array $info info
     */
    public function log($info) {
        //...
    }

    /**
     * Insert user to database
     *
     * @Loggable
     * 
     * @param  Array $info Info
     */
    public function insertUser($user) {
        //...
        $this->log('user inserted');
    }

    /*
    public function add() {

=before advice
        // check auth
        if (!isGranted('ADD_USER')) {
            throw new Exception("Access Denied");
        }

=before advice
        // log
        log('creating user');

=business logic
        // create user
        try {
            $user = array('id' => '003');
            $user['name'] = 'user';
            //...

            // save
            insertUser($user);
        } catch(Exception $e) {
=after throwing advice
            log('user create error: ' + $e);
            // handleError($e);
        }

=after advice
        // log
        log('user created');
    }
    */
    

    public function add($fields) {
//=business logic
        // create user
        $user = array('id' => '003');
        $user['name'] = 'user';
        //...

        // save
        $this->insertUser($user);

        // throw error
        $this->badMethod();
        throw new Exception("A Stange Error");
    }

    // throwing for exception catch test
    public function badMethod() {
        throw new Exception("A Bad Error");
    }
}
