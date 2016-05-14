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
    protected $callable;

    /**
     * 实例集
     *
     * @var array
     */
    protected static $listeners = [];

    function __construct($callable)
    {
        $this->callable = $callable;
    }

    /**
     * 从闭包创建当前类实例
     *
     * @param mixed $callable
     * @return CallbackListener
     */
    static function createFromCallable($callable)
    {
        $listener = new static($callable);
        self::$listeners[] = $listener;
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
        foreach (self::$listeners as $listener) {
            if ($listener->getCallable() == $callable) {
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
        return $this->callable;
    }

    /**
     * 响应事件
     *
     * @param Event $event
     */
    function handle(Event $event)
    {
        call_user_func($this->callable, $event);
    }
}