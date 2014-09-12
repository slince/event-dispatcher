<?php
/**
 * slince event dispatcher library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Event;

class Event extends AbstractEvent
{

    /**
     * 额外参数
     *
     * @var array
     */
    private $_arguments = [];

    function __construct($name, Dispatcher $dispatcher = null, $arguments = [])
    {
        $this->_name = $name;
        $this->_dispatcher = $dispatcher;
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
}