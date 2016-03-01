<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface SubscriberInterface
{

    /**
     * 获取所有注册事件
     */
    function getEvents();
}