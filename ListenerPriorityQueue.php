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
    private $_storage;

    /**
     *
     * @var \SplPriorityQueue
     */
    private $_queue;

    function __construct()
    {
        $this->_storage = new \SplObjectStorage();
        $this->_queue = new \SplPriorityQueue();
    }

    /**
     * 插入监听者
     *
     * @param ListenerInterface $listener            
     * @param int $priority            
     */
    function insert(ListenerInterface $listener, $priority)
    {
        $this->_storage->attach($listener, $priority);
        $this->_queue->insert($listener, $priority);
    }

    /**
     * 移除监听
     *
     * @param ListenerInterface $listener            
     */
    function detach(ListenerInterface $listener)
    {
        if ($this->_storage->contains($listener)) {
            $this->_storage->detach($listener);
            $this->_refreshQueue();
        }
    }

    /**
     * 清空
     */
    function flush()
    {
        $this->_storage = new \SplObjectStorage();
        $this->_queue = new \SplPriorityQueue();
    }

    /**
     * 是否存在某个监听
     *
     * @param ListenerInterface $listener            
     * @return boolean
     */
    function contains(ListenerInterface $listener)
    {
        return $this->_storage->contains($listener);
    }

    /**
     * 刷新监听者队列
     */
    protected function _refreshQueue()
    {
        $this->_queue = new \SplPriorityQueue();
        foreach ($this->_storage as $litener) {
            $priority = $this->_storage->getInfo();
            $this->_queue->insert($value, $priority);
        }
    }

    /**
     * 返回外部迭代器
     *
     * @return SplPriorityQueue
     */
    function getIterator()
    {
        return $this->_queue;
    }

    /**
     * 返回所有监听者
     * 
     * @return array
     */
    function getAll()
    {
        $listeners = [];
        foreach ($this->_queue as $listener) {
            $listeners[] = $listener;
        }
        return $listeners;
    }
}