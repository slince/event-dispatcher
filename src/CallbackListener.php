<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class CallbackListener implements ListenerInterface
{

    /**
     * id
     * 
     * @var string
     */
    private $_id;

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

    function __construct(\Closure $callable, $id)
    {
        $this->_callable = $callable;
        $this->_id = $id;
    }

    /**
     * 从闭包创建当前类实例
     *
     * @param \Closure $callback            
     * @return \Slince\Event\CallbackListener
     */
    static function newFromCallable($id, \Closure $callable)
    {
        if (! isset(self::$_listener[$id])) {
            self::$_listener[$id] = new self($id, $callable);
        }
        return self::$_listener[$id];
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