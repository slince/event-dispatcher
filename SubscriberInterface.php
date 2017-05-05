<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface SubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array
     */
    public function getEvents();
}