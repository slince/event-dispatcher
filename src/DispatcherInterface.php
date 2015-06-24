<?php
/**
 * slince event dispatcher component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class DispatcherInterface
{

    function dispatch($eventName, EventInterface $event = null);

    function attach($eventName, \Closure $callable, $priority = );

    function addListener($eventName, ListenerInterface $listener);

    function addSubscriber(SubscriberInterface $subscriber);
    
    function detach($eventName, );
}