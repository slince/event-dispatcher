<?php

namespace Slince\Event\Tests;

use Slince\Event\ListenerInterface;
use Slince\Event\Event;

class FooListener implements ListenerInterface
{
    public function handle(Event $event)
    {
        return true;
    }
}
