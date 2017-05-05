<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface DispatcherInterface
{
    /**
     * Low priority
     * @var int
     */
    const PRIORITY_LOW = -100;

    /**
     * Default priority
     * @var int
     */
    const PRIORITY_DEFAULT = 0;

    /**
     * High priority
     * @var int
     */
    const PRIORITY_HIGH = 100;

    /**
     * Dispatches an event to all registered listeners.
     * @param string $eventName
     * @param Event $event
     */
    public function dispatch($eventName, Event $event = null);

    /**
     * Registries a callable-listener for the event
     * @param string $eventName
     * @param ListenerInterface|callable $listener
     * @param int $priority
     * @deprecated Use addListener instead
     */
    public function bind($eventName, $listener, $priority = self::PRIORITY_DEFAULT);

    /**
     * Registries a listener for the event
     * @param string $eventName
     * @param ListenerInterface|callable $listener
     * @param int $priority
     */
    public function addListener($eventName, $listener, $priority = self::PRIORITY_DEFAULT);

    /**
     * Registries a subscriber
     * @param SubscriberInterface $subscriber
     */
    public function addSubscriber(SubscriberInterface $subscriber);

    /**
     * Removes a callable-listener from the specified event
     * @param string $eventName
     * @param ListenerInterface|callable $listener
     * @deprecated Use removeListener instead
     */
    public function unbind($eventName, $listener);

    /**
     * Removes a listener from the specified event
     * @param string $eventName
     * @param ListenerInterface|callable $listener
     */
    public function removeListener($eventName, $listener);

    /**
     * Removes a subscriber
     * @param SubscriberInterface $subscriber
     */
    public function removeSubscriber(SubscriberInterface $subscriber);

    /**
     * Removes all listeners from the specified event
     * @param string $eventName
     */
    public function removeAll($eventName = null);

    /**
     * Checks whether the listener is existed for the event
     * @param string $eventName
     * @param mixed $listener
     * @return boolean
     */
    public function hasListener($eventName, $listener);

    /**
     * Gets all listeners of the event or all registered listeners
     * @param string $eventName
     * @return array
     */
    public function getListeners($eventName = null);
}
