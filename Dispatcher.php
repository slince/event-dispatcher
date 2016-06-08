<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class Dispatcher implements DispatcherInterface
{
    /**
     * 监听者
     * @var array
     */
    protected $listeners = [];

    /**
     * 触发事件
     * @param string $eventName
     * @param Event $event
     */
    function dispatch($eventName, Event $event = null)
    {
        if (is_null($event)) {
            $event = new Event($eventName, null);
        }
        if (!empty($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                call_user_func([$listener, 'handle'], $event);
            }
        }
    }

    /**
     * 绑定回调监听
     * @param string $eventName
     * @param mixed $callable
     * @param int $priority
     */
    function bind($eventName, $callable, $priority = self::PRIORITY_DEFAULT)
    {
        if (!is_callable($callable)) {
            return;
        }
        $listener = CallbackListener::createFromCallable($callable);
        return $this->addListener($eventName, $listener, $priority);
    }

    /**
     * 绑定监听器
     * @param string $eventName
     * @param ListenerInterface $listener
     * @param int $priority
     */
    function addListener($eventName, ListenerInterface $listener, $priority = self::PRIORITY_DEFAULT)
    {
        if (empty($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerPriorityQueue();
        }
        $this->listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * 绑定订阅者
     * @param SubscriberInterface $subscriber
     */
    function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $method) {
            $this->bind($eventName, [$subscriber, $method]);
        }
    }

    /**
     * 解绑回调监听
     * @param string $eventName
     * @param mixed $callable
     */
    function unbind($eventName, $callable)
    {
        $listener = CallbackListener::getFromCallable($callable);
        if (!is_null($listener)) {
            $this->removeListener($eventName, $listener);
        }
    }

    /**
     * 解绑监听器
     * @param string $eventName
     * @param ListenerInterface $listener
     */
    function removeListener($eventName, ListenerInterface $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return false;
        }
        $this->listeners[$eventName]->detach($listener);
    }

    /**
     * 解绑订阅者
     * @param SubscriberInterface $subscriber
     */
    function removeSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $method) {
            $this->unbind($eventName, [$subscriber, $method]);
        }
    }

    /**
     * 解绑所有监听者
     */
    function removeAll($eventName = null)
    {
        if (!is_null($eventName)) {
            if (!empty($this->listeners[$eventName])) {
                $this->listeners[$eventName]->flush();
            }
        } else {
            foreach ($this->listeners as $queue) {
                $queue->flush();
            }
        }
    }

    /**
     * 判断是否存在监听者
     * @param string $eventName
     * @param mixed $listener
     * @return boolean
     */
    function hasListener($eventName, $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return false;
        }
        if (is_callable($listener)) {
            $listener = CallbackListener::newFromCallable($listener);
        }
        return $this->listeners[$eventName]->contains($listener);
    }

    /**
     * 获取监听者
     * @param string $eventName
     * @return array
     */
    function getListeners($eventName = null)
    {
        if (!is_null($eventName)) {
            return empty($this->listeners[$eventName]) ? [] : $this->listeners[$eventName]->getAll();
        } else {
            $listeners = [];
            foreach ($this->listeners as $queue) {
                $listeners += $queue->getAll();
            }
            return $listeners;
        }
    }
}