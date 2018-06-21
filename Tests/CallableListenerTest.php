<?php

namespace Slince\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Slince\EventDispatcher\CallableListener;

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
        $this->assertFalse(CallableListener::findByCallable(function(){
        }));
    }
}
