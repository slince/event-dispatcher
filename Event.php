<?php
/**
 * slince event dispatcher library
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class Event extends AbstractEvent
{

    /**
     * 额外参数
     *
     * @var array
     */
    protected $_arguments = [];

    function __construct($name, $subject, DispatcherInterface $dispatcher = null, array $arguments = [])
    {
        parent::__construct($name, $subject, $dispatcher);
        $this->_arguments = $arguments;
    }

    /**
     * 设置参数
     *
     * @param string $name
     * @param mixed $value
     */
    function setArgument($name, $value)
    {
        $this->_arguments[$name] = $value;
    }

    /**
     * 获取参数
     *
     * @param string $name
     * @return mixed
     */
    function getArgument($name)
    {
        return isset($this->_arguments[$name]) ? $this->_arguments[$name] : null;
    }

    /**
     * 批量设置参数
     *
     * @param array $arguments
     */
    function setArguments(array $arguments)
    {
        $this->_arguments = $arguments;
    }

    /**
     * 批量获取参数
     *
     * @return array
     */
    function getArguments()
    {
        return $this->_arguments;
    }
}