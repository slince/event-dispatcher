# Event dispatcher component

事件调度组件。

### 安装

在composer.json中添加

    {
        "require": {
            "slince/event": "dev-master@dev"
        }
    }

### 用法

    Class User
    {
        private $_dispatcher;
        function __construct(Dispatcher $dispatcher)
        {
            $this->_dispatcher = $dispatcher;
        }
        function login()
        {
            //如果登录成功，触发事件
            if (true) {
                $this->_dispatcher->dispatch('User.login.success');
            }
        }
    }

    $dispatcher = new Slince\Event\Dispatcher();
    $dispatcher->attach('User.login.success', function ($e) {
        echo '恭喜!!';
    });

    $user = new User($dispatcher);
    $user->login();

监听者应该是一个闭包或者实现ListenerInterface接口的类的实例。
更多特性等待您去探索！
