<?php

namespace Slince\EventDispatcher\Tests;

use Slince\EventDispatcher\ListenerInterface;
use Slince\EventDispatcher\Event;

class FooListener implements ListenerInterface
{
    public function handle(Event $event)
    {
        return true;
    }
}
