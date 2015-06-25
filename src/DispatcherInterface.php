<?php
/**
 * slince event dispatcher component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface DispatcherInterface
{

    const PRIORITY_LOW = - 100;

    const PRIORITY_DEFAULT = 100;

    const PRIORITY_HIGH = 100;

    function dispatch($eventName, EventInterface $event = null);

    function attach($eventName, \Closure $callable, $priority = self::PRIORITY_DEFAULT);

    function addListener($eventName, ListenerInterface $listener, $priority = self::PRIORITY_DEFAULT);

    function addSubscriber(SubscriberInterface $subscriber);

    function detach($eventName, \Closure $callable);
    
    function removeListener($eventName, ListenerInterface $listener);
    
    function removeSubscriber($eventName, ListenerInterface $listener);
}