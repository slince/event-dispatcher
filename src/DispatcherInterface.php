<?php
/**
 * slince event dispatcher component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface DispatcherInterface
{

    /**
     * 低优先级
     *
     * @var int
     */
    const PRIORITY_LOW = - 100;

    /**
     * 默认优先级
     *
     * @var int
     */
    const PRIORITY_DEFAULT = 100;

    /**
     * 高优先级
     *
     * @var int
     */
    const PRIORITY_HIGH = 100;

    /**
     * 触发事件
     *
     * @param string $eventName            
     * @param EventInterface $event            
     */
    function dispatch($eventName, EventInterface $event = null);

    /**
     * 绑定回调监听
     *
     * @param string $eventName            
     * @param \Closure $callable            
     * @param int $priority            
     */
    function bind($eventName, \Closure $callable, $priority = self::PRIORITY_DEFAULT);

    /**
     * 绑定监听器
     *
     * @param string $eventName            
     * @param ListenerInterface $listener            
     * @param int $priority            
     */
    function addListener($eventName, ListenerInterface $listener, $priority = self::PRIORITY_DEFAULT);

    /**
     * 绑定订阅者
     *
     * @param SubscriberInterface $subscriber            
     */
    function addSubscriber(SubscriberInterface $subscriber);

    /**
     * 解绑回调监听
     *
     * @param string $eventName            
     * @param \Closure $callable            
     */
    function unbind($eventName, \Closure $callable);

    /**
     * 解绑监听器
     *
     * @param string $eventName            
     * @param ListenerInterface $listener            
     */
    function removeListener($eventName, ListenerInterface $listener);

    /**
     * 解绑订阅者
     *
     * @param SubscriberInterface $subscriber            
     */
    function removeSubscriber($eventName, SubscriberInterface $listener);
}