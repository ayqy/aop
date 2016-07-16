#AOP(Aspect-Oriented Programming)

###写在前面

时不时地总会在各种地方看到AOP，wiki也查了不止一次，但每次都对那一堆陌生术语望而却步，这次总算下决心要尝试AOP了

最后发现，AOP类似于设计模式，不同于[策略模式](http://www.ayqy.net/blog/%E8%AE%BE%E8%AE%A1%E6%A8%A1%E5%BC%8F%E4%B9%8B%E7%AD%96%E7%95%A5%E6%A8%A1%E5%BC%8F%EF%BC%88strategy-pattern%EF%BC%89/)、[模板方法模式](http://www.ayqy.net/blog/%E8%AE%BE%E8%AE%A1%E6%A8%A1%E5%BC%8F%E4%B9%8B%E6%A8%A1%E7%89%88%E6%96%B9%E6%B3%95%E6%A8%A1%E5%BC%8F%EF%BC%88template-method-pattern%EF%BC%89/)、[装饰者模式](http://www.ayqy.net/blog/%E8%AE%BE%E8%AE%A1%E6%A8%A1%E5%BC%8F%E4%B9%8B%E8%A3%85%E9%A5%B0%E8%80%85%E6%A8%A1%E5%BC%8F%EF%BC%88decorator-pattern%EF%BC%89/)。AOP的近亲是[代理模式](http://www.ayqy.net/blog/%E8%AE%BE%E8%AE%A1%E6%A8%A1%E5%BC%8F%E4%B9%8B%E4%BB%A3%E7%90%86%E6%A8%A1%E5%BC%8F%EF%BC%88proxy-pattern%EF%BC%89_%E8%BF%9C%E7%A8%8B%E4%BB%A3%E7%90%86%E8%A7%A3%E6%9E%90/)，同样能够分离逻辑，核心也是拦截与细节隐藏

P.S.搬出来这么多名词其实不是故意的，因为在理解AOP的过程中确实有对比思考过这几个模式。然后，发现设计模式这种东西，嗯，怎么说呢，有用吗？没有用吗？额

##一.术语（Glossary）

-  切面（Aspect）

  在AOP中表示为“在哪里做和做什么的集合”

  横切关注点的模块化，比如上边提到的日志组件。可以认为是增强、引入和切入点的组合。例如日志、缓存、传输管理

-  Join point（连接点）

  在AOP中表示为“在哪里做”

  表示需要在程序中插入横切关注点的扩展点，连接点可能是类初始化、方法执行、方法调用、字段调用或处理异常等等。表示执行期的一个点，例如方法执行或者属性访问

-  增强（Advice）

  在AOP中表示为“做什么”

  或称为增强在连接点上执行的行为，增强提供了在AOP中需要在切入点所选择的连接点处进行扩展现有行为的手段。包括前置增强（before advice）、后置增强 (after advice)、环绕增强 （around advice）。表示切面在特定连接点处的动作

-  切入点（Pointcut）

  在AOP中表示为“在哪里做的集合”

  选择一组相关连接点的模式，即可以认为连接点的集合，Spring支持perl5正则表达式和AspectJ切入点模式，Spring默认使用AspectJ语法。用来匹配连接点的正则表达式，增强都有相关的切入点表达式，在任何与之匹配的连接点处执行，例如，某个特定名称的方法的执行

-  引入（Introduction）

  在AOP中表示为“做什么（新增什么）”

  也称为内部类型声明（inter-type declaration），为已有的类添加额外新的字段或方法

-  Weaving（织入）

  把切面和其它应用程序类型或者对象链接起来，以创建增强对象

  织入是一个过程，是将切面应用到目标对象从而创建出AOP代理对象的过程，织入可以在编译期、类装载期、运行期进行

-  目标对象（Target Object）

  在AOP中表示为“对谁做”

  需要被织入横切关注点的对象，即该对象是切入点选择的对象，需要被增强的对象，从而也可称为“被增强对象”

-  AOP代理（AOP Proxy）

  AOP框架使用代理模式创建的对象，从而实现在连接点处插入增强（即应用切面），就是通过代理来对目标对象应用切面

术语比较多，简单分类：

    抽象概念：切面、引入、织入、目标对象、AOP代理

    具体概念：连接点、增强、切入点

    关系：切入点是连接点形成的集合，两者都表示需要插入逻辑的目标位置，增强表示需要插入的具体动作

使用AOP时需要关注的是连接点和切入点，前者是“想在哪个位置插入逻辑”，后者是“想在哪块区域插入逻辑（区域由位置组成）”，再切入并注册advice，添加前置后置逻辑

###Advice类型

-  前置增强（Before advice）

  在某连接点之前执行的增强，但这个增强不能阻止连接点前的执行（除非它抛出一个异常）

-  后置返回增强（After returning advice）

  在某连接点正常完成后执行的增强：例如，一个方法没有抛出任何异常，正常返回

-  后置异常增强（After throwing advice）

  在方法抛出异常退出时执行的增强

-  后置最终增强（After (finally) advice）

  当某连接点退出的时候执行的增强（不论是正常返回还是异常退出）

-  环绕增强（Around Advice）

  围绕一个连接点的增强，如方法调用。这是最强大的一种增强类型。环绕增强可以在方法调用前后完成自定义的行为。它也负责选择是继续执行连接点，还是直接返回它们自己的返回值或者抛出异常来结束执行

*需要注意*的是AfterThrowing与AroundAdvice的区别，业务逻辑发生异常后，会触发前者，但拿不到异常对象，只知道关注的方法发生异常了，意义不大。而后者是把业务逻辑完全包裹起来，所以可以捕获异常信息（暂不讨论异步回调异常）。其它几种Advice都是字面意思，很容易理解

##二.作用

AOP能够将那些与业务无关，却为业务模块所共同调用的逻辑或责任（例如事务处理、日志管理、权限控制等）封装起来，便于减少系统的重复代码，降低模块间的耦合度，并有利于未来的可操作性和可维护性

感受一个例子，面向对象代码很容易长成这样：

    /**
     * OO style
     */
    class OOUser {

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

缓存，日志，异常处理，权限检查等逻辑分散穿插于项目代码各处（不止User类），无法分离出来。存在很多问题：

-  无法复用

-  难以理解类的最初职能，逻辑杂乱

-  很容易出错，如果忘记写这些样板（biolerplate）代码的话

-  有违DRY原则，每个逻辑块都穿插着这些面熟的代码

尤其是维护老项目，看到一块块的脸熟代码很难受，想改又抽不出来，或者费了很大劲最后只是缓解了一点表面症状（比如，考虑其它封装方式，精简了几行业务代码）

AOP专门解决这个问题，它可以横向切入对象内部进行内科手术，剥离核心业务逻辑，我们就可以专注于*真正有用的*那几行代码

##三.PHP AOP示例

发现了一个比较好用的PHP AOP框架：[Go! AOP](https://github.com/goaop/framework)

P.S.因为较好的AOP框架涉及反射与注解，以笔者目前的PHP能力不足完成，所以放弃了手动实现AOP机制的想法

考虑之前的OO代码，对逻辑块进行分类：

    public function add() {

    //=before advice
        // check auth
        if (!isGranted('ADD_USER')) {
            throw new Exception("Access Denied");
        }

    //=before advice
        // log
        log('creating user');

    //=business logic
        // create user
        try {
            $user = array('id' => '003');
            $user['name'] = 'user';
            //...

            // save
            insertUser($user);
        } catch(Exception $e) {
    //=after throwing advice
            log('user create error: ' + $e);
            // handleError($e);
        }

    //=after advice
        // log
        log('user created');
    }

发现业务逻辑只有几行，但是，被其它*不很关键*的代码深深地包起来了，更新维护时就将面对“在一大片代码中修改某一小块”的问题，定位到关键部分再小心翼翼地修改，然后还是很容易出错（尤其是异常处理）

然后抽离业务逻辑，新的User类是这样的：

    /**
     * AOP style
     */
    class AOPUser {

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
        //...其它无法共享的依赖方法
    }

我们把业务逻辑分离出来了，可共享的依赖方法（比如，`log()`, `isGranted()`等）都被抽出来成为共享`lib`，其它无法共享的依赖方法仍然作为类成员存在，此时User类的职责相对单一，不和谐的代码都滚出去了，逻辑很清晰

接下来需要装配（类似于装饰者模式，但实现方式上差异较大），把滚出去的相关代码再装上，AOP会帮我们动态组装，我们只需要声明关联，告诉AOP*在哪里 装什么*（也就是术语“切面”的含义）

    /**
     * User aspect
     */
    class UserAspect implements Aspect {

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
            if (!$isGranted('ADD_USER', $user->caller)) {
                throw new Exception("Access Denied");
            }
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
            $log('user create error, handle error here');
            // handleError();
            
            //!!! avoid reporting error
            set_exception_handler(function($e) {
                echo "!!!Global Exception Handler: " . $e->getMessage();
            });
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
            $log('user created');
        }

        //...其它Advice
    }

通过注解声明增强（Advice）与目标对象的联系，告诉AOP在哪里插入什么逻辑，消除逻辑粘连

*注意*`handleErrorAfterThrowing()`方法，为了避免全局异常报错，我们使用了`set_exception_handler()`，这样做是因为AfterThrowing增强在切点发生异常时会触发，但我们拿不到异常对象，也无法吃掉它，所以通过全局异常拦截来吃掉这个异常

如果需要精确操作某过程中的异常，应该使用Around增强，把目标过程完全包裹起来，再`try-catch`即可，如下：

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

P.S.此处不讨论异步回调中的异常，PHP一般不关注这种情况，JS的话，也不考虑注解方式实现AOP（应该采用高阶函数、binding之类的方式进行逻辑注入），以后再说

P.S.GO! AOP相当强大，也提供了切入系统方法及工具函数的方式，包括参数截获，属性访问拦截等等

##四.Demo

在线Demo：<>

源码地址：<>

##五.总结

AOP是对OOP的补充，横向切入对象并进行逻辑注入，确保类的职责单一

更贴切地说，AOP是一种设计模式，也有比较激烈的看法：

>  AOP是OOP的补丁，纵向OOP建立对象体系，继承封装多态；横向AOP切入，纵横合璧，天下无敌...

也没错，只是存在侵入程度的争议，比如，如果想要AOP切入整个OO体系，势必侵入程度很大（考虑继承）。个人更喜欢侵入程度小的方案，灵活但不方便

怎么说，学习AOP算是获得了一种设计思路，类似于设计原则（复习一下）：

-  封装变化（把易于发生变化的部分抽出来，以减少其变化对其它部分的影响）

-  多用组合，少用继承（组合比继承更有弹性）

-  针对接口编程，不针对实现编程（使用接口可以避免直接依赖具体类）

-  为交互对象之间的松耦合设计而努力（更松的耦合意味着更多的弹性）

-  类应该对扩展开放，对修改关闭（open-close原则）

-  依赖抽象，不要依赖具体类（减少对具体类的直接依赖）

-  只和朋友交谈（密友原则）

-  别找我，我会找你（Don’t call me, I will call you back.安卓开发的大原则）

-  类应该只有一个改变的理由（单一责任原则）

-  横向逻辑注入（AOP）

考虑问题时多一种选择，仅此而已。在构建大型系统时AOP应该是必要的内置功能，但就应用场景而言，AOP并不是万能钥匙，但AOP的思想（*横向逻辑注入*）适用于任何场景

###参考资料

- [Weaving aspects in PHP with the help of Go! AOP library](http://www.slideshare.net/slideshow/embed_code/15487433)：GO! AOP作者的PPT，适合入门（感性认知）

- [Introduction to aspect-oriented programming](http://go.aopphp.com/docs/introduction/)：对AOP术语的解释

- [我对AOP的理解](http://www.iteye.com/topic/1122401)：后半部分挺好的