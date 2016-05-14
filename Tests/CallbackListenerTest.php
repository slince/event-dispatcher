<?php
namespace Slince\Event\Tests;

use Slince\Event\CallbackListener;

class CallbackListenerTest extends \PHPUnit_Framework_TestCase
{
    function testCreateFromCallable()
    {
        $listener = CallbackListener::createFromCallable(function () {
            return true;
        });
        $this->assertInstanceOf('Slince\Event\CallbackListener', $listener);
    }

    function testGetFromCallable()
    {
        $callback = function () {
            return true;
        };
        $listener = CallbackListener::createFromCallable($callback);
        $listener2 = CallbackListener::getFromCallable($callback);
        $this->assertEquals($listener, $listener2);
        $this->assertTrue($listener === $listener2);
    }
}