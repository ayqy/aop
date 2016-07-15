<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2012, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Aspect;

use Exception;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Pointcut;
use Go\Lang\Annotation\AfterThrowing;
use Go\Lang\Annotation\Around;

/**
 * User aspect
 */
class UserAspect implements Aspect {

    private function showMethodInfo($invocation) {
        $obj = $invocation->getThis();
        echo 'Calling Before Interceptor for method: ',
            is_object($obj) ? get_class($obj) : $obj,
            $invocation->getMethod()->isStatic() ? '::' : '->',
            $invocation->getMethod()->getName(),
            '()',
            ' with arguments: ',
            json_encode($invocation->getArguments()),
            "<br>\n";
    }

    /**
     * Pointcut for add method
     *
     * @Pointcut("execution(public App\App\AOPUser->add(*))")
     */
    protected function UserAdd() {}
    // 执行$aopuser->add()时切入

    /**
     * Check anth before add user
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("$this->UserAdd")
     */
    protected function checkAuthBeforeAdd(MethodInvocation $invocation) {
        /** @var $user \App\App\AOPUser */
        $user = $invocation->getThis();
        // check auth
        if (!$user->isGranted('ADD_USER')) {
            throw new Exception("Access Denied");
        }

        $this->showMethodInfo($invocation);
    }

    /**
     * Log before add user
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("$this->UserAdd")
     */
    protected function logBeforeAdd(MethodInvocation $invocation) {
        /** @var $user \App\App\AOPUser */
        $user = $invocation->getThis();
        // log
        $user->log('creating user');

        $this->showMethodInfo($invocation);
    }

    /**
     * Handle Error after throwing
     *
     * @param MethodInvocation $invocation Invocation
     * @AfterThrowing("$this->UserAdd")
     */
    protected function handleErrorAfterThrowing(MethodInvocation $invocation) {
        /** @var $user \App\App\AOPUser */
        $user = $invocation->getThis();
        // =after throwing advice
        $user->log('user create error, handle error here');
        // handleError();
        
        //!!! avoid to report error
        set_exception_handler(function($e) {
            echo "!!!Global Exception Handler: " . $e->getMessage();
        });

        $this->showMethodInfo($invocation);
    }

    /**
     * Log after add user
     *
     * @param MethodInvocation $invocation Invocation
     * @After("$this->UserAdd")
     */
    protected function logAfterAdd(MethodInvocation $invocation) {
        /** @var $user \App\App\AOPUser */
        $user = $invocation->getThis();
        // log
        $user->log('user created');

        $this->showMethodInfo($invocation);
    }


    /**
     * Intercept before loggable method
     *
     * @param MethodInvocation $invocation Invocation
     * @Before("@execution(App\Annotation\Loggable)")
     */
    protected function interceptBeforeLog(MethodInvocation $invocation) {
        $this->showMethodInfo($invocation);
    }

    /**
     * Around advice to catch exception
     * @param  MethodInvocation $invocation Invocation
     * @Around("execution(public App\App\AOPUser->badMethod(*))")
     */
    protected function aroundBadMethod(MethodInvocation $invocation) {
        try {
            $invocation->proceed();
        } catch (Exception $e) {
            echo '!!!Around Advice Error Handler: ' . $e->getMessage() . "<br>\n";
        }
    }
}