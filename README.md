# Event Dispatcher Component

[![Build Status](https://travis-ci.org/slince/event.svg?branch=master)](https://travis-ci.org/slince/event)
[![Latest Stable Version](https://poser.pugx.org/slince/event/v/stable)](https://packagist.org/packages/slince/event)
[![Total Downloads](https://poser.pugx.org/slince/event/downloads)](https://packagist.org/packages/slince/event)
[![Latest Unstable Version](https://poser.pugx.org/slince/event/v/unstable)](https://packagist.org/packages/slince/event)
[![License](https://poser.pugx.org/slince/event/license)](https://packagist.org/packages/slince/event)

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
    function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    function login()
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
