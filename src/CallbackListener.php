<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class CallbackListener implements ListenerInterface
{

    /**
     * 回调
     *
     * @var \Closure
     */
    private $_callable;

    /**
     * 实例集
     *
     * @var array
     */
    private static $_listener = [];

    function __construct(\Closure $callable)
    {
        $this->_callable = $callable;
    }

    /**
     * 从闭包创建当前类实例
     *
     * @param \Closure $_callable            
     * @return \Slince\Event\CallbackListener
     */
    static function newFromCallable(\Closure $callable)
    {
        $hash = spl_object_hash($callable);
        if (! isset(self::$_listener[$hash])) {
            self::$_listener[$hash] = new self($callable);
        }
        return self::$_listener[$hash];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\ListenerInterface::handle()
     */
    function handle(EventInterface $event)
    {
        call_user_func($this->_callable, $event);
    }
}