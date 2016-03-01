<?php
use Slince\Event\Dispatcher;
use Slince\Event\ListenerInterface;
use Slince\Event\EventInterface;
use Slince\Event\SubscriberInterface;
use Slince\Event\DispatcherInterface;

class DelListener implements ListenerInterface
{

    function handle(EventInterface $event)
    {
        throw new \Exception('Propagation Stop');
    }
}

class DispatcherTest extends PHPUnit_Framework_TestCase
{

    function getDispatcher()
    {
        return new Dispatcher();
    }
    function testCallback()
    {
        $dispatcher = $this->getDispatcher();
        $dispatcher->bind('delete', function($event) {
            $event->setArgument('haveTrigger', true);
        });
        $dispatcher->bind('delete', function(EventInterface $event) {
            $this->assertTrue($event->getArgument('haveTrigger'));
        });
        $dispatcher->dispatch('delete');
    }
    function testPropagation()
    {
        //冒泡
        $dispatcher = $this->getDispatcher();
        $dispatcher->bind('delete', function(EventInterface $event) {
            $event->setArgument('haveTrigger', true);
            $event->stopPropagation();
        });
        $dispatcher->bind('delete', function($event) {
            throw new \Exception('Propagation Stop');
        });
        $dispatcher->dispatch('delete');
    }
    function testPriority()
    {
        //优先级
        $dispatcher = $this->getDispatcher();
        $dispatcher->bind('delete', function(EventInterface $event) {
            $event->setArgument('var', 1);
        }, Dispatcher::PRIORITY_DEFAULT);
        $dispatcher->bind('delete', function($event) {
           $event->setArgument('var', 2);
        }, Dispatcher::PRIORITY_HIGH);
        $dispatcher->bind('delete', function($event) {
            $this->assertEquals(1, $event->getArgument('var'));
        }, Dispatcher::PRIORITY_LOW);
        $dispatcher->dispatch('delete');
    }
    
    function testUnbind()
    {
        //解绑
        $dispatcher = $this->getDispatcher();
        $callback = function(EventInterface $event) {
            throw new \Exception('Propagation Stop');
        };
        $dispatcher->bind('delete', $callback);
        $this->setExpectedException('\\Exception');
        $dispatcher->dispatch('delete');
        $dispatcher->unbind('delete', $callback);
        $dispatcher->dispatch('delete');
    }
    
    function testListener()
    {
        $dispatcher = $this->getDispatcher();
        $delListener = new DelListener();
        $this->setExpectedException('\\Exception');
        $dispatcher->addListener('delete', $delListener);
        $dispatcher->dispatch('delete');
    }
    
    function tes2tDelayEvent()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->attach('del', function (AbstractEvent $event)
        {
            $litener = new DelLitener();
            $event->getDispatcher()->attach('del', $litener);
        });
        $dispatcher->dispatch('del');
    }
}