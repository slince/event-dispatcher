<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface ListenerInterface
{
    /**
     * Handles an event
     * @param Event $event
     */
    public function handle(Event $event);
}
