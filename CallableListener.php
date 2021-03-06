<?php

/*
 * This file is part of the slince/event-dispatcher package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\EventDispatcher;

class CallableListener implements ListenerInterface
{
    /**
     * The callable callback.
     *
     * @var callable
     */
    protected $callable;

    /**
     * Array of callable-listeners.
     *
     * @var array
     */
    protected static $listeners = [];

    public function __construct($callable)
    {
        $this->callable = $callable;
        static::$listeners[] = $this;
    }

    /**
     * Gets callback.
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        call_user_func($this->callable, $event);
    }

    /**
     * Creates a callable-listener.
     *
     * @param callable $callable
     *
     * @return CallableListener
     */
    public static function createFromCallable($callable)
    {
        $listener = new static($callable);

        return $listener;
    }

    /**
     * Finds the listener from the collection by its callable.
     *
     * @param callable $callable
     *
     * @return CallableListener|false
     */
    public static function findByCallable($callable)
    {
        foreach (static::$listeners as $listener) {
            if ($listener->getCallable() == $callable) {
                return $listener;
            }
        }

        return false;
    }

    /**
     * Removes all registered callable-listeners.
     */
    public static function clearListeners()
    {
        static::$listeners = [];
    }
}
