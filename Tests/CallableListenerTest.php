<?php
namespace Slince\Event\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Event\CallableListener;

class CallableListenerTest extends TestCase
{
    public function testGetCallable()
    {
        $callback = function () {
            return true;
        };
        $listener = new CallableListener($callback);
        $this->assertTrue($listener->getCallable() === $callback);
    }

    public function testCreateFromCallable()
    {
        $listener = CallableListener::createFromCallable(function () {
            return true;
        });
        $this->assertInstanceOf(CallableListener::class, $listener);
    }

    public function testFindByCallable()
    {
        $callback = function () {
            return true;
        };
        $this->assertTrue(CallableListener::createFromCallable($callback) === CallableListener::findByCallable($callback));
    }
}
