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
    protected $_listeners = [];

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::dispatch()
     */
    function dispatch($eventName, EventInterface $event = null)
    {
        if (is_null($event)) {
            $event = new Event($eventName, null, $this);
        }
        if (! empty($this->_listeners[$eventName])) {
            foreach ($this->_listeners[$eventName] as $listener) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                call_user_func(array(
                    $listener,
                    'handle'
                ), $event);
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
        if (! is_callable($callable)) {
            return;
        }
        $listener = CallbackListener::newFromCallable($callable);
        return $this->addListener($eventName, $listener, $priority);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::addListener()
     */
    function addListener($eventName, ListenerInterface $listener, $priority = self::PRIORITY_DEFAULT)
    {
        if (empty($this->_listeners[$eventName])) {
            $this->_listeners[$eventName] = new ListenerPriorityQueue();
        }
        $this->_listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::addSubscriber()
     */
    function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $method) {
            $this->bind($eventName, array($subscriber, $method));
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
        if (! is_null($listener)) {
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
        if (empty($this->_listeners[$eventName])) {
            return;
        }
        $this->_listeners[$eventName]->detach($listener);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::removeSubscriber()
     */
    function removeSubscriber($eventName, SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $callback) {
            $this->unbind($eventName, array($subscriber, $method));
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::removeAll()
     */
    function removeAll($eventName = null)
    {
        if (! is_null($eventName)) {
            if (! empty($this->_listeners[$eventName])) {
                $this->_listeners[$eventName]->flush();
            }
        } else {
            foreach ($this->_listeners as $queue) {
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
        if (empty($this->_listeners[$eventName])) {
            return false;
        }
        if (is_callable($listener)) {
            $listener = CallbackListener::newFromCallable($listener);
        }
        return $this->_listeners[$eventName]->contains($listener);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::getListeners()
     */
    function getListeners($eventName = null)
    {
        if (! is_null($eventName)) {
            if (empty($this->_listeners[$eventName])) {
                return [];
            }
            return $this->_listeners[$eventName]->getAll();
        } else {
            $listeners = [];
            foreach ($this->_listeners as $queue) {
                $listeners = array_merge($listeners, $queue->getAll());
            }
            return $listeners;
        }
    }
}