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
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::dispatch()
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
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::bind()
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
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::addListener()
     */
    function addListener($eventName, ListenerInterface $listener, $priority = self::PRIORITY_DEFAULT)
    {
        if (empty($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerPriorityQueue();
        }
        $this->listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::addSubscriber()
     */
    function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $method) {
            $this->bind($eventName, [$subscriber, $method]);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::unbind()
     */
    function unbind($eventName, $callable)
    {
        $listener = CallbackListener::getFromCallable($callable);
        if (!is_null($listener)) {
            $this->removeListener($eventName, $listener);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::removeListener()
     */
    function removeListener($eventName, ListenerInterface $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return false;
        }
        $this->listeners[$eventName]->detach($listener);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::removeSubscriber()
     */
    function removeSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $method) {
            $this->unbind($eventName, [$subscriber, $method]);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::removeAll()
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
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::hasListener()
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
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::getListeners()
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