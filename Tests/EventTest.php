<?php
namespace Slince\Event\Tests;

use Slince\Event\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    const EVENT_FOOL1 = 'fool1';

    const EVENT_FOOL2 = 'fool2';

    protected function createEvent($name)
    {
        return new Event($name);
    }

    public function testGetName()
    {
        $event = $this->createEvent(self::EVENT_FOOL1);
        $this->assertEquals(self::EVENT_FOOL1, $event->getName());
    }

    public function testSetName()
    {
        $event = $this->createEvent(self::EVENT_FOOL1);
        $this->assertEquals(self::EVENT_FOOL1, $event->getName());
        $event->setName(self::EVENT_FOOL2);
        $this->assertEquals(self::EVENT_FOOL2, $event->getName());
    }

    public function testArguments()
    {
        $event = $this->createEvent(self::EVENT_FOOL1);
        $this->assertCount(0, $event->getArguments());
        $event->setArgument('parameter', 'value');
        $this->assertCount(1, $event->getArguments());
        $this->assertEquals('value', $event->getArgument('parameter'));
    }

    public function testStopPropagation()
    {
        $event = $this->createEvent(self::EVENT_FOOL1);
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}