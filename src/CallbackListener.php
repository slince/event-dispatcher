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
     * @var mixed
     */
    protected $_callable;

    /**
     * 实例集
     *
     * @var array
     */
    protected static $_listeners = [];

    function __construct($callable)
    {
        $this->_callable = $callable;
    }

    /**
     * 从闭包创建当前类实例
     *
     * @param mixed $_callable            
     * @return \Slince\Event\CallbackListener
     */
    static function newFromCallable($callable)
    {
        $listener = new static($callable);
        self::$_listeners[] = $listener;
        return $listener;
    }

    /**
     * 查看callable对应的CallbackListener实例
     *
     * @param mixed $callable            
     * @return CallbackListener|NULL
     */
    static function getFromCallable($callable)
    {
        foreach (self::$_listeners as $listener) {
            if ($listener->getCallable == $callable) {
                return $listener;
            }
        }
        return null;
    }

    /**
     * 获取calleable
     *
     * @return \Slince\Event\mixed
     */
    function getCallable()
    {
        return $this->_callable;
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