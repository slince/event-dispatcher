<?php
/**
 * slince event dispatcher library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Event;

class Event
{

    public $cancelBubble = false;

    private $_name;

    private $_dispatcher;

    private $_subject;

    private $_arguments;

    function __construct(Dispatcher $dispatcher, $arguments)
    {
        $this->_dispatcher = $dispatcher;
        $this->_arguments = $arguments;
    }

    function getName()
    {
        return $this->_name;
    }

    function setName($name)
    {
        $this->_name = $name;
    }

    function setDispatcher($dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    function getDispatcher()
    {
        return $this->_dispatcher;
    }

    function setArguments($name, $value)
    {
        $this->_arguments[$name] = $value;
    }

    function getArgument($name)
    {
        return isset($this->_arguments[$name]) ? $this->_arguments[$name] : null;
    }
}