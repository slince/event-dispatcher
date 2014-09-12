<?php
use Slince\Event\Dispatcher;
use Slince\Event\ListenerInterface;
use Slince\Event\AbstractEvent;

class DelLitener implements ListenerInterface
{

    function handle(AbstractEvent $event)
    {
        echo 'del2';
    }
}

class DispatcherTest extends PHPUnit_Framework_TestCase
{

    function tes2tAttach()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->attach('del', function ()
        {
            echo 'del1...';
        });
        $litener = new DelLitener();
        $dispatcher->attach('del', $litener);
        $dispatcher->dispatch('del');
    }

    function tes2tDetach()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->attach('del', function ()
        {
            echo 'del1...';
        });
        $litener = new DelLitener();
        $dispatcher->attach('del', function ()
        {
            echo 'del3...';
        });
        $dispatcher->attach('del', $litener);
        $dispatcher->dispatch('del');
    }
    function testDelayEvent()
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