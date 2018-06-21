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

class ListenerPriorityQueue implements \IteratorAggregate
{
    /**
     * @var \SplObjectStorage
     */
    protected $storage;

    /**
     * @var \SplPriorityQueue
     */
    protected $queue;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * Insert an listener to the queue.
     *
     * @param ListenerInterface $listener
     * @param int               $priority
     */
    public function insert(ListenerInterface $listener, $priority)
    {
        $this->storage->attach($listener, $priority);
        $this->queue->insert($listener, $priority);
    }

    /**
     * Removes an listener from the queue.
     *
     * @param ListenerInterface $listener
     */
    public function detach(ListenerInterface $listener)
    {
        if ($this->storage->contains($listener)) {
            $this->storage->detach($listener);
            $this->refreshQueue();
        }
    }

    /**
     * Clears the queue.
     */
    public function clear()
    {
        $this->storage = new \SplObjectStorage();
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * Clears the queue.
     *
     * @deprecated use clear instead
     */
    public function flush()
    {
        $this->clear();
    }

    /**
     * Checks whether the queue contains the listener.
     *
     * @param ListenerInterface $listener
     *
     * @return boolean
     */
    public function contains(ListenerInterface $listener)
    {
        return $this->storage->contains($listener);
    }

    /**
     * Gets all listeners.
     *
     * @return ListenerInterface[]
     */
    public function all()
    {
        $listeners = [];
        foreach ($this->getIterator() as $listener) {
            $listeners[] = $listener;
        }

        return $listeners;
    }

    /**
     * Gets all listeners.
     *
     * @deprecated use all instead
     */
    public function getAll()
    {
        return $this->all();
    }

    /**
     * Clones and returns a iterator.
     *
     * @return \SplPriorityQueue
     */
    public function getIterator()
    {
        $queue = clone $this->queue;
        if (!$queue->isEmpty()) {
            $queue->top();
        }

        return $queue;
    }

    /**
     * Refreshes the status of the queue.
     */
    protected function refreshQueue()
    {
        $this->storage->rewind();
        $this->queue = new \SplPriorityQueue();
        foreach ($this->storage as $listener) {
            $priority = $this->storage->getInfo();
            $this->queue->insert($listener, $priority);
        }
    }
}
