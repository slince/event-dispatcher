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
        $dispatcher->bind('delete', function($event) {
            $this->assertTrue($event->getArgument('haveTrigger'));
        });
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