<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class Event
{
    /**
     * 事件名
     * @var string
     */
    protected $name;

    /**
     * 主题
     * @var object
     */
    protected $subject;

    /**
     * 额外参数
     * @var array
     */
    protected $arguments = [];

    /**
     * 是否阻止冒泡
     *
     * @var boolean
     */
    protected $propagationStopped = false;

    function __construct($name, $subject = null, array $arguments = [])
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->arguments = $arguments;
    }


    /**
     * 获取事件名
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * 设置事件名
     * @param $name
     */
    function setName($name)
    {
        $this->name = $name;
    }


    /**
     * 设置subject
     * @param $subject
     */
    function setSubject($subject)
    {
        $this->subject = $subject;
    }


    /**
     * 获取subject
     * @return null|object
     */
    function getSubject()
    {
        return $this->subject;
    }

    /**
     * 设置参数
     * @param string $name
     * @param mixed $value
     */
    function setArgument($name, $value)
    {
        $this->arguments[$name] = $value;
    }

    /**
     * 获取参数
     * @param string $name
     * @return mixed
     */
    function getArgument($name)
    {
        return isset($this->arguments[$name]) ? $this->arguments[$name] : null;
    }

    /**
     * 批量设置参数
     * @param array $arguments
     */
    function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * 批量获取参数
     * @return array
     */
    function getArguments()
    {
        return $this->arguments;
    }

    /**
     * 阻止事件冒泡
     * @return $this
     */
    function stopPropagation()
    {
        $this->propagationStopped = true;
        return $this;
    }

    /**
     * 是否阻止事件冒泡
     * @return bool
     */
    function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}