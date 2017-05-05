<?php
namespace Slince\Event\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Event\CallableListener;
use Slince\Event\Dispatcher;
use Slince\Event\Event;
use Slince\Event\SubscriberInterface;

class DispatcherTest extends TestCase
{
    public function testInitialize()
    {
        $dispatcher = new Dispatcher();
        $this->assertEmpty($dispatcher->getListeners());
    }

    public function testAddListener()
    {
        $dispatcher = new Dispatcher();
        $this->assertEmpty($dispatcher->getListeners('foo'));
        $dispatcher->addListener('foo', new FooListener());
        $this->assertCount(1, $dispatcher->getListeners('foo'));
    }

    public function testAddSubscriber()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new Subscriber());
        $this->assertCount(1, $dispatcher->getListeners('foo'));
        $this->assertCount(1, $dispatcher->getListeners('bar'));
    }

    public function tes2tRemoveListener()
    {
        $dispatcher = new Dispatcher();
        $listener = new FooListener();
        $dispatcher->addListener('bar', $listener);
        $this->assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeListener('bar', $listener);
        $this->assertCount(0, $dispatcher->getListeners('bar'));
    }

    public function tes2tRemoveCallableListener()
    {
        $dispatcher = new Dispatcher();
        $callback = function () {
        };
        $dispatcher->addListener('bar', $callback);
        $this->assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeListener('bar', $callback);
        $this->assertCount(0, $dispatcher->getListeners('bar'));
    }

    public function testRemoveSubscriber()
    {
        CallableListener::clearListeners();
        $dispatcher = new Dispatcher();
        $subscriber = new Subscriber();
        $dispatcher->addSubscriber($subscriber);
        $this->assertCount(1, $dispatcher->getListeners('foo'));
        $this->assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeSubscriber($subscriber);
        $this->assertCount(0, $dispatcher->getListeners('foo'));
        $this->assertCount(0, $dispatcher->getListeners('bar'));
    }

    public function testRemoveAll()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new Subscriber());
        $dispatcher->removeAll('foo');
        $this->assertEmpty($dispatcher->getListeners('foo'));
        $this->assertNotEmpty($dispatcher->getListeners('bar'));
    }

    public function testSimpleDispatch()
    {
        $dispatcher = new Dispatcher();
        $counter = 0;
        $dispatcher->addListener('foo', function () use (&$counter) {
            $counter++;
        });
        $dispatcher->addListener('foo', function () use (&$counter) {
            $counter++;
        });
        $dispatcher->dispatch('foo');
        $this->assertEquals(2, $counter);
    }

    public function testDispatchEvent()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('foo', function (Event $event) {
            $this->assertInstanceOf(Event::class, $event);
            $this->assertTrue($event->getSubject() === $this);
            $this->assertEquals('foo', $event->getArgument('data'));
        });
        $dispatcher->dispatch(new Event('foo', $this, [
            'data' => 'foo'
        ]));
    }

    public function testDispatcherWithPriority()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('foo', function (Event $event) {
            $this->assertEquals(10, $event->getArgument('number'));
            $event->setArgument('number', 100);
        }, Dispatcher::PRIORITY_DEFAULT);

        $dispatcher->addListener('foo', function (Event $event) {
            $this->assertEquals(100, $event->getArgument('number'));
        }, Dispatcher::PRIORITY_LOW);

        $dispatcher->addListener('foo', function (Event $event) {
            $this->assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
        }, Dispatcher::PRIORITY_HIGH);

        $dispatcher->dispatch(new Event('foo', $this, [
            'number' => 0
        ]));
    }

    public function testDispatchStopPropagation()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('foo', function (Event $event) {
            $this->assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
            $event->stopPropagation();
        });
        $dispatcher->addListener('foo', function (Event $event) {
            $event->setArgument('number', 100);
        });
        $event = new Event('foo', $this, [
            'number' => 0
        ]);
        $dispatcher->dispatch('foo', $event);
        $this->assertEquals(10, $event->getArgument('number'));
    }
}

class Subscriber implements SubscriberInterface
{
    public function getEvents()
    {
        return [
            'foo' => 'onFoo',
            'bar' => 'onBar',
        ];
    }

    public function onFoo()
    {
        return true;
    }

    public function onBar()
    {
        return true;
    }
}
