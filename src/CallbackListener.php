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
    private $_callback;

    function __construct(\Closure $callback)
    {
        $this->_callback = $callback;
    }

    /**
     * 从闭包创建当前类实例
     *
     * @param \Closure $callback            
     * @return \Slince\Event\CallbackListener
     */
    static function newFromCallable(\Closure $callable)
    {
        return new self($callable);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\ListenerInterface::handle()
     */
    function handle(EventInterface $event)
    {
        call_user_func($this->_callback, $event);
    }
}