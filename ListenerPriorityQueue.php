<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class ListenerPriorityQueue implements \IteratorAggregate
{

    /**
     *
     * @var \SplObjectStorage
     */
    public $storage;

    /**
     *
     * @var \SplPriorityQueue
     */
    private $queue;

    function __construct()
    {
        $this->storage = new \SplObjectStorage();
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * 插入监听者
     *
     * @param ListenerInterface $listener
     * @param int $priority
     */
    function insert(ListenerInterface $listener, $priority)
    {
        $this->storage->attach($listener, $priority);
        $this->queue->insert($listener, $priority);
    }

    /**
     * 移除监听
     *
     * @param ListenerInterface $listener
     */
    function detach(ListenerInterface $listener)
    {
        if ($this->storage->contains($listener)) {
            $this->storage->detach($listener);
            $this->refreshQueue();
        }
    }

    /**
     * 清空
     */
    function flush()
    {
        $this->storage = new \SplObjectStorage();
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * 是否存在某个监听
     *
     * @param ListenerInterface $listener
     * @return boolean
     */
    function contains(ListenerInterface $listener)
    {
        return $this->storage->contains($listener);
    }

    /**
     * 刷新监听者队列
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

    /**
     * 返回外部迭代器
     *
     * @return SplPriorityQueue
     */
    function getIterator()
    {
        $queue = clone $this->queue;
        if (!$queue->isEmpty()) {
            $queue->top();
        }
        return $queue;
    }

    /**
     * 返回所有监听者
     *
     * @return array
     */
    function getAll()
    {
        $listeners = [];
        foreach ($this->getIterator() as $listener) {
            $listeners[] = $listener;
        }
        return $listeners;
    }
}