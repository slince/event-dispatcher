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

use Slince\EventDispatcher\Exception\InvalidArgumentException;

class Dispatcher implements DispatcherInterface
{
    /**
     * Array of listeners.
     *
     * @var ListenerPriorityQueue[]
     */
    protected $listeners = [];

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, EventInterface $event = null)
    {
        if ($eventName instanceof EventInterface) {
            $event = $eventName;
        } elseif (is_null($event)) {
            $event = new Event($eventName, null);
        }
        if (isset($this->listeners[$event->getName()])) {
            foreach ($this->listeners[$event->getName()] as $listener) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                call_user_func([$listener, 'handle'], $event);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, $listener, $priority = self::PRIORITY_DEFAULT)
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerPriorityQueue();
        }
        if (is_callable($listener)) {
            $listener = CallableListener::createFromCallable($listener);
        }
        if (!$listener instanceof ListenerInterface) {
            throw new InvalidArgumentException('The listener should be the implementation of the listenerInterface or callable');
        }
        $this->listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $action) {
            $this->addListener($eventName, [$subscriber, $action]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($eventName, $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return;
        }
        if (is_callable($listener) && false === ($listener = CallableListener::findByCallable($listener))) {
            return;
        }
        $this->listeners[$eventName]->detach($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $action) {
            $this->removeListener($eventName, [$subscriber, $action]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListeners($eventName = null)
    {
        if (!is_null($eventName) && isset($this->listeners[$eventName])) {
            $this->listeners[$eventName]->clear();
        } else {
            foreach ($this->listeners as $queue) {
                $queue->clear();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasListener($eventName, $listener)
    {
        if (!isset($this->listeners[$eventName])) {
            return false;
        }
        if (is_callable($listener)) {
            $listener = CallableListener::findByCallable($listener);
        }

        return $this->listeners[$eventName]->contains($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($eventName = null)
    {
        if (!is_null($eventName)) {
            return isset($this->listeners[$eventName]) ?
                $this->listeners[$eventName]->all() : [];
        } else {
            $listeners = [];
            foreach ($this->listeners as $queue) {
                $listeners = array_merge($listeners, $queue->all());
            }

            return $listeners;
        }
    }
}
