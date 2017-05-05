<?php
namespace Slince\Event\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Event\Event;

class EventTest extends TestCase
{
    protected function createEvent($name)
    {
        return new Event($name);
    }

    public function testGetName()
    {
        $event = new Event('foo');
        $this->assertEquals('foo', $event->getName());
    }

    public function testSetName()
    {
        $event = new Event('foo');
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
