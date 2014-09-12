<?php
/**
 * slince event dispatcher library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Event;

abstract class AbstractEvent
{

    /**
     * 是否阻止冒泡
     * 
     * @var boolean
     */
    public $cancelBubble = false;

    /**
     * 事件名
     * 
     * @var string
     */
    protected $_name;

    /**
     * 调度器
     *
     * @var Dispatcher
     */
    protected $_dispatcher;

    /**
     * 主题
     *
     * @var object
     */
    protected $_subject;

    /**
     * 获取事件名
     *
     * @return string
     */
    function getName()
    {
        return $this->_name;
    }

    /**
     * 设置事件名
     *
     * @param string $name            
     */
    function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * 设置调度器
     *
     * @param Dispatcher $dispatcher            
     */
    function setDispatcher($dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    /**
     * 获取调度器
     * 
     * @return Dispatcher
     */
    function getDispatcher()
    {
        return $this->_dispatcher;
    }

    /**
     * 设置主题
     *
     * @param object $subject            
     */
    function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * 获取主题
     *
     * @return object
     */
    function getSubject()
    {
        return $this->_subject;
    }
}