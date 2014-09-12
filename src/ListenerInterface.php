<?php
/**
 * slince event dispatcher library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Event;

interface ListenerInterface
{

    /**
     * 响应事件
     *
     * @param AbstractEvent $event            
     */
    function handle(AbstractEvent $event);
}