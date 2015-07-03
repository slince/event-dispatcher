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
    function dispatch($eventName, AbstractEvent $event = null)
    {
        if (is_null($event)) {
            $event = new Event($eventName);
            $event->setDispatcher($this);
        }
        if (isset($this->_listeners[$eventName])) {
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
     * @see \Slince\Event\DispatcherInterface::attach()
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
            $this->_listeners[$eventName] = new \SplPriorityQueue();
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
            $this->attach($eventName, $callback);
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Event\DispatcherInterface::unbind()
     */
    function unbind($eventName, $listener)
    {
        if (isset($this->_listeners[$eventName])) {
            if (($key = array_search($listener, $this->_listeners[$eventName])) !== false) {
                unset($this->_listeners[$eventName][$key]);
            }
        }
    }

    function removeListener($eventName, ListenerInterface $listener)
    {}

    function removeSubscriber($eventName, SubscriberInterface $listener)
    {}
}