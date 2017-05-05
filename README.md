# Event Dispatcher Component

[![Build Status](https://img.shields.io/travis/slince/event/master.svg?style=flat-square)](https://travis-ci.org/slince/event)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/event.svg?style=flat-square)](https://codecov.io/github/slince/event)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/event.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/event)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/event.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/event/?branch=master)

事件调度组件。

### 安装

在composer.json中添加
```
{
    "require": {
        "slince/event": "*"
    }
}

```
### 用法

基本用法：

```
use Slince\Event\Dispatcher;
$dispatcher = new Dispatcher();
$dispatcher->bind('User.loginSuccess', function ($event) {
    echo '恭喜!!';
});
$dispatcher->dispatch('User.loginSuccess');
```
监听者应该是一个闭包或者实现ListenerInterface接口的类的实例。

事件冒泡的应用：
```
Class User
{
    private $dispatcher;
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    public function login()
    {
        //如果登录成功，触发事件
        if (true) {
            $this->_dispatcher->dispatch('User.loginSuccess');
        }
    }
}

$dispatcher = new Slince\Event\Dispatcher();
$dispatcher->bind('User.loginSuccess', function ($e) {
    echo '恭喜!!';
    //如果需要阻止接下来的日志记录操作
    $e->stopPropagation();
});
$dispatcher->bind('User.loginSuccess', function ($e) {
    //登录日志记录操作
});

//优先级
$dispatcher->bind('User.loginSuccess', function ($e) {
    //登录日志记录操作
}, Dispatcher::PRIORITY_HIGH);

$user = new User($dispatcher);
$user->login();
```
更多...
