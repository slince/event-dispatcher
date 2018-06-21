<?php

namespace Slince\EventDispatcher\Tests;

use Slince\EventDispatcher\Dispatcher;
use Slince\EventDispatcher\ListenerPriorityQueue;
use PHPUnit\Framework\TestCase;

class ListenerPriorityQueueTest extends TestCase
{
    public function testInsert()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $this->assertFalse($queue->contains($listener));
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $this->assertTrue($queue->contains($listener));
    }

    public function testContains()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $this->assertFalse($queue->contains($listener));
    }

    public function testDetach()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $this->assertTrue($queue->contains($listener));
        $queue->insert(new FooListener(), Dispatcher::PRIORITY_DEFAULT);
        $queue->detach($listener);
        $this->assertFalse($queue->contains($listener));
    }

    public function testClear()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $queue->clear();
        $this->assertFalse($queue->contains($listener));
    }

    public function testAll()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $this->assertEquals([$listener], $queue->all());
    }
}
