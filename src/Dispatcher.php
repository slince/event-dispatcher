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
     * @see \Slince\Event\DispatcherInterface::attach()
     */
    function attach($eventName, \Closure $callable, $priority = self::PRIORITY_DEFAULT)
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
     * @see \Slince\Event\DispatcherInterface::addSubscriber()
     */
    function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $eventName => $callback) {
            $this->attach($eventName, $callback);
        }
    }
    
    function removeListener($eventName, ListenerInterface $listener)
    {
        
    }

    /**
     * 移除一个绑定的监听者
     *
     * @param string $eventName            
     * @param mixed $listener            
     */
    function detach($eventName, $listener)
    {
        if (isset($this->_listeners[$eventName])) {
            if (($key = array_search($listener, $this->_listeners[$eventName])) !== false) {
                unset($this->_listeners[$eventName][$key]);
            }
        }
    }

    /**
     * 调度事件
     *
     * @param string $eventName            
     * @param AbstractEvent $event            
     */
    function dispatch($eventName, AbstractEvent $event = null)
    {
        if (is_null($event)) {
            $event = new Event($eventName);
            $event->setDispatcher($this);
        }
        if (isset($this->_listeners[$eventName])) {
            foreach ($this->_listeners[$eventName] as $listener) {
                if ($event->cancelBubble) {
                    break;
                }
                if (is_callable($listener)) {
                    call_user_func($listener, $event);
                } elseif ($listener instanceof ListenerInterface) {
                    $listener->handle($event);
                }
            }
        }
    }
}