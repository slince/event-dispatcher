<?php
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
    }
}

class Order
{
    private $_dispatcher;
    
    function __construct(DispatcherInterface $dispatcher)
    {
        $subscriber = new OrderSubscriber();
        $dispatcher->addSubscriber($subscriber);
        $this->_dispatcher = $dispatcher;
    }
    function save()
    {

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
        $order->run();
    }
}