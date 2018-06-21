<?php

namespace Slince\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Slince\EventDispatcher\Event;

class EventTest extends TestCase
{
    public function testName()
    {
        $event = new Event('foo');
        $this->assertEquals('foo', $event->getName());
        $event->setName('bar');
        $this->assertEquals('bar', $event->getName());
    }

    public function testArguments()
    {
        $event = new Event('foo');
        $this->assertCount(0, $event->getArguments());
        $event->setArgument('foo', 'bar');
        $this->assertCount(1, $event->getArguments());
        $this->assertEquals('bar', $event->getArgument('foo'));
        $event->setArguments([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'baz',
        ], $event->getArguments());
    }

    public function testStopPropagation()
    {
        $event = new Event('foo');
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testSubject()
    {
        $event = new Event('foo', $this);
        $this->assertTrue($event->getSubject() === $this);
        $event->setSubject(null);
        $this->assertNull($event->getSubject());
    }
}
