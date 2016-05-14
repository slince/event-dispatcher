<?php
namespace Slince\Event\Tests;

use Slince\Event\Dispatcher;
use Slince\Event\ListenerInterface;
use Slince\Event\ListenerPriorityQueue;
use Slince\Event\Event;

class ListenerPriorityQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ListenerPriorityQueue
     */
    protected $queue;

    function setUp()
    {
        $this->queue = new ListenerPriorityQueue();
    }

    function testInsert()
    {
        $listener = new Listener2();
        $this->assertFalse($this->queue->contains($listener));
        $this->queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $this->assertTrue($this->queue->contains($listener));
        $this->queue->insert($listener, Dispatcher::PRIORITY_LOW);
        $this->queue->insert($listener, Dispatcher::PRIORITY_HIGH);
    }

    function testFlush()
    {
        $listener = new Listener2();
        $this->queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $this->assertTrue($this->queue->contains($listener));
        $this->queue->flush();
        $this->assertFalse($this->queue->contains($listener));
    }
}

class Listener2 implements ListenerInterface
{
    function handle(Event $event)
    {
        return true;
    }
}