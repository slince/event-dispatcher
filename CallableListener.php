<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class CallableListener implements ListenerInterface
{
    /**
     * The callable callback
     * @var callable
     */
    protected $callable;

    /**
     * Array of callable-listeners
     * @var array
     */
    protected static $listeners = [];

    public function __construct($callable)
    {
        $this->callable = $callable;
        static::$listeners[] = $this;
    }

    /**
     * Gets callback
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
     * Creates a callable-listener
     * @param callable $callable
     * @return CallableListener
     */
    public static function createFromCallable($callable)
    {
        $listener = new static($callable);
        return $listener;
    }

    /**
     * Finds the listener from the collection by its callable
     * @param mixed $callable
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
     * Removes all registered callable-listeners
     */
    public static function clearListeners()
    {
        static::$listeners = [];
    }
}
