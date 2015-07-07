<?php
use Slince\Event\Dispatcher;
use Slince\Event\ListenerInterface;
use Slince\Event\EventInterface;

class DeleteListener implements ListenerInterface
{

    function handle(EventInterface $event)
    {
        echo 'del2';
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
        //冒泡
        $dispatcher->removeAll();
        $dispatcher->bind('delete', function(EventInterface $event) {
            $event->setArgument('haveTrigger', true);
            $event->stopPropagation();
        });
        $dispatcher->bind('delete', function($event) {
            $this->setExpectedException('\\Exception');
            throw new \Exception('Propagation Stop');
        });
        //优先级
        $dispatcher->removeAll();
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