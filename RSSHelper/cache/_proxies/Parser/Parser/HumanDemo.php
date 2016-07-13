<?php
namespace Parser\Parser;

/**
 * Human class example
 */
class HumanDemo extends HumanDemo__AopProxied implements \Go\Aop\Proxy
{

    /**
     * Property was created automatically, do not change it manually
     */
    private static $__joinPoints = [];
    
    /**
     * Eat something
     */
    public function eat()
    {
        return self::$__joinPoints['method:eat']->__invoke($this);
    }
    
    /**
     * Go to sleep
     */
    public function sleep()
    {
        return self::$__joinPoints['method:sleep']->__invoke($this);
    }
    
}
\Go\Proxy\ClassProxy::injectJoinPoints('Parser\Parser\HumanDemo',array (
  'method' => 
  array (
    'eat' => 
    array (
      0 => 'advisor.Parser\\Aspect\\HealthyLiveAspect->washUpBeforeEat',
      1 => 'advisor.Parser\\Aspect\\HealthyLiveAspect->cleanTeethBeforeEat',
      2 => 'advisor.Parser\\Aspect\\HealthyLiveAspect->cleanTeethAfterEat',
    ),
    'sleep' => 
    array (
      0 => 'advisor.Parser\\Aspect\\HealthyLiveAspect->cleanTeethBeforeSleep',
      1 => 'advisor.Parser\\Aspect\\HealthyLiveAspect->wakeupAfterSleep',
    ),
  ),
));