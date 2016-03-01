<?php
use Slince\Event\DispatcherInterface;
use Slince\Event\Dispatcher;
use Slince\Event\SubscriberInterface;
use Slince\Event\EventInterface;

class OrderSubscriber implements SubscriberInterface
{

    function getEvents()
    {
        return [
            'save' => 'onSave',
            'handle' => 'onHandle',
            'check' => 'onCheck',
        ];
    }

    function onSave(EventInterface $event)
    {
        $order = $event->getArgument('order');
        $event->setArgument('onpass', true);
    }

    function onHandle(EventInterface $event)
    {
        $event->setArgument('onpass', false);
    }

    function onCheck(EventInterface $event)
    {
        $event->getArgument('onpass');
        throw new \Exception();
    }
}

class Order
{
    /**
     * 
     * @var DispatcherInterface
     */
    private $_dispatcher;
    
    function __construct(DispatcherInterface $dispatcher)
    {
        $subscriber = new OrderSubscriber();
        $dispatcher->addSubscriber($subscriber);
        $this->_dispatcher = $dispatcher;
    }
    function save()
    {
        $this->_dispatcher->dispatch('save');
    }
    function handle()
    {
        $this->_dispatcher->dispatch('handle');
    }
    function check()
    {
        $this->_dispatcher->dispatch('check');
    }
    function run()
    {
        $this->save();
        $this->handle();
        $this->check();
    }
}

class SubscriberTest extends PHPUnit_Framework_TestCase
{
    function getDispatcher()
    {
        return new Dispatcher();
    }
    
    function testSubscriber()
    {
        $dispatcher = $this->getDispatcher();
        $order = new Order($dispatcher);
        $this->setExpectedException('\\Exception');
        $order->run();
    }
}