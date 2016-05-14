<?php
namespace Slince\Event\Tests;

use Slince\Event\Dispatcher;
use Slince\Event\Event;
use Slince\Event\ListenerInterface;
use Slince\Event\SubscriberInterface;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{

    const EVENT_FOOL1 = 'fool1';

    const EVENT_FOOL2 = 'fool2';

    const EVENT_FOOL3 = 'fool3';

    /**
     * @var Dispatcher
     */
    protected $dispatcher;


    protected $callback;

    protected $listener;

    protected $subscriber;

    function setUp()
    {
        $this->dispatcher = new Dispatcher();
        $this->callback = function () {
            return true;
        };
    }

    function testInitialize()
    {
        $this->assertEmpty($this->dispatcher->getListeners());
    }

    function testBind()
    {
        $this->assertEmpty($this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->dispatcher->bind(self::EVENT_FOOL1, function () {
            return true;
        });
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL1));
    }

    function testAddListener()
    {
        $this->assertEmpty($this->dispatcher->getListeners(self::EVENT_FOOL2));
        $this->dispatcher->addListener(self::EVENT_FOOL2, new Listener());
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL2));
    }

    function testAddSubscriber()
    {
        $this->dispatcher->removeAll();
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL2));
        $this->dispatcher->addSubscriber(new Subscriber());
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL2));
    }

    function testUnBind()
    {
        $this->dispatcher->removeAll();
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->dispatcher->bind(self::EVENT_FOOL1, function () {
            return true;
        });
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->dispatcher->unbind(self::EVENT_FOOL1, function () {
            return true;
        });
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL1));
    }

    function testRemoveListener()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->addListener(self::EVENT_FOOL2, new Listener());
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL2));
        $this->dispatcher->removeListener(self::EVENT_FOOL2, new Listener());
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL2));
    }

    function testRemoveScriber()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->addSubscriber(new Subscriber());
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->assertCount(1, $this->dispatcher->getListeners(self::EVENT_FOOL2));
        $this->dispatcher->removeSubscriber(new Subscriber());
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->assertCount(0, $this->dispatcher->getListeners(self::EVENT_FOOL2));
    }

    function testRemoveAll()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->addSubscriber(new Subscriber());
        $this->dispatcher->removeAll(self::EVENT_FOOL1);
        $this->assertEmpty($this->dispatcher->getListeners(self::EVENT_FOOL1));
        $this->assertNotEmpty($this->dispatcher->getListeners(self::EVENT_FOOL2));
    }

    function testSimpleDispatch()
    {
        $this->dispatcher->removeAll();
        $this->counter = 0;
        $this->dispatcher->bind(self::EVENT_FOOL3, function () {
            $this->counter++;
        });
        $this->dispatcher->bind(self::EVENT_FOOL3, function () {
            $this->counter++;
        });
        $this->dispatcher->dispatch(self::EVENT_FOOL3);
        $this->assertEquals(2, $this->counter);
    }

    function testDispatchWithEvent()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $this->assertInstanceOf('Slince\Event\Event', $event);
            $this->assertInstanceOf('Slince\Event\Tests\DispatcherTest', $event->getSubject());
            $this->assertEquals(self::EVENT_FOOL3, $event->getName());
            $this->assertEquals(self::EVENT_FOOL1, $event->getArgument('data'));
        });
        $this->dispatcher->dispatch(self::EVENT_FOOL3, new Event(self::EVENT_FOOL3, $this, [
            'data' => self::EVENT_FOOL1
        ]));
    }

    function testDispatcherWithPriority()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $this->assertEquals(10, $event->getArgument('number'));
            $event->setArgument('number', 100);
        }, Dispatcher::PRIORITY_DEFAULT);

        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $this->assertEquals(100, $event->getArgument('number'));
        }, Dispatcher::PRIORITY_LOW);

        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $this->assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
        }, Dispatcher::PRIORITY_HIGH);

        $this->dispatcher->dispatch(self::EVENT_FOOL3, new Event(self::EVENT_FOOL3, $this, [
            'number' => 0
        ]));
    }

    function testDispatchStopPropagation()
    {
        $this->dispatcher->removeAll();
        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $this->assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
            $event->stopPropagation();
        });
        $this->dispatcher->bind(self::EVENT_FOOL3, function (Event $event) {
            $event->setArgument('number', 100);
        });
        $event = new Event(self::EVENT_FOOL3, $this, [
            'number' => 0
        ]);
        $this->dispatcher->dispatch(self::EVENT_FOOL3, $event);
        $this->assertEquals(10, $event->getArgument('number'));
    }
}

class Listener implements ListenerInterface
{

    function handle(Event $event)
    {
        throw new \Exception('Propagation Stop');
    }
}

class Subscriber implements SubscriberInterface
{
    function getEvents()
    {
        return [
            DispatcherTest::EVENT_FOOL1 => 'onFool1',
            DispatcherTest::EVENT_FOOL2 => 'onFool2',
        ];
    }

    function onFool1()
    {
        return true;
    }

    function onFool2()
    {
        return true;
    }
}