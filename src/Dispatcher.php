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
        
        if (isset($this->_listeners[$eventName])) {
            foreach ($this->_listeners[$eventName] as $listener) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                call_user_func(array($listener, 'handle'), $event);
            }
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\DispatcherInterface::bind()
     */
    function bind($eventName, \Closure $callable, $priority = self::PRIORITY_DEFAULT)
    {
        $listener = CallbackListener::newFromCallable($callable);
        $this->addListener($eventName, $listener, $priority);
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
        foreach ($subscriber->getEvents() as $eventName => $callback) {
            $this->bind($eventName, $callback);
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Event\DispatcherInterface::unbind()
     */
    function unbind($eventName, \Closure $callable)
    {
        $listener = CallbackListener::newFromCallable($callable);
        $this->removeListener($eventName, $listener);
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Event\DispatcherInterface::removeListener()
     */
    function removeListener($eventName, ListenerInterface $listener)
    {
        if (empty($this->_listeners[$eventName])) {
            return false;
        }
        $this->_listeners[$eventName]->detach($listener);
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Event\DispatcherInterface::removeSubscriber()
     */
    function removeSubscriber($eventName, SubscriberInterface $listener)
    {
        foreach ($subscriber->getEvents() as $eventName => $callback) {
            $this->unbind($eventName, $callback);
        }
    }
    
    /**
     * (non-PHPdoc)
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
     * 获取监听者
     * @param string $eventName
     * @return array
     */
    function getListeners($eventName)
    {
        if (empty($this->_listeners[$eventName])) {
            return [];
        } 
        return $this->_listeners[$eventName]->getAll();
    }
}