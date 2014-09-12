<?php
/**
 * slince event dispatcher component
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Event;

class Dispatcher
{

    /**
     * 监听者
     *
     * @var array
     */
    private $_listeners = [];

    /**
     * 绑定一个监听者
     *
     * @param string $eventName            
     * @param mixed $listener            
     */
    function attach($eventName, $listener)
    {
        $this->_listeners[$eventName][] = $listener;
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
            $event = new Event($eventName, $this);
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