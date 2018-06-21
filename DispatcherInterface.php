<?php

/*
 * This file is part of the slince/event-dispatcher package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\EventDispatcher;

interface DispatcherInterface
{
    /**
     * Low priority.
     *
     * @var int
     */
    const PRIORITY_LOW = -100;

    /**
     * Default priority.
     *
     * @var int
     */
    const PRIORITY_DEFAULT = 0;

    /**
     * High priority.
     *
     * @var int
     */
    const PRIORITY_HIGH = 100;

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param string|EventInterface $eventName
     * @param EventInterface        $event
     */
    public function dispatch($eventName, EventInterface $event = null);

    /**
     * Registries a listener for the event.
     *
     * @param string                     $eventName
     * @param ListenerInterface|callable $listener
     * @param int                        $priority
     */
    public function addListener($eventName, $listener, $priority = self::PRIORITY_DEFAULT);

    /**
     * Registries a subscriber.
     *
     * @param SubscriberInterface $subscriber
     */
    public function addSubscriber(SubscriberInterface $subscriber);

    /**
     * Removes a listener from the specified event.
     *
     * @param string                     $eventName
     * @param ListenerInterface|callable $listener
     */
    public function removeListener($eventName, $listener);

    /**
     * Removes a subscriber.
     *
     * @param SubscriberInterface $subscriber
     */
    public function removeSubscriber(SubscriberInterface $subscriber);

    /**
     * Removes all listeners from the specified event.
     *
     * @param string $eventName
     */
    public function removeAllListeners($eventName = null);

    /**
     * Checks whether the listener is existed for the event.
     *
     * @param string                     $eventName
     * @param ListenerInterface|callable $listener
     *
     * @return boolean
     */
    public function hasListener($eventName, $listener);

    /**
     * Gets all listeners of the event or all registered listeners.
     *
     * @param string $eventName
     *
     * @return array
     */
    public function getListeners($eventName = null);
}
