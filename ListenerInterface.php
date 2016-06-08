<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface ListenerInterface
{
    /**
     * 响应事件
     * @param Event $event
     */
    function handle(Event $event);
}