<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface SubscriberInterface
{
    function getSubscribedEvents();
}