<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

abstract class AbstractEvent implements EventInterface
{

    /**
     * 是否阻止冒泡
     *
     * @var boolean
     */
    protected $_propagationStopped = false;

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

    function __construct($name, $subject = null, DispatcherInterface $dispatcher = null)
    {
        $this->_name = $name;
        $this->_subject = $subject;
        $this->_dispatcher = $dispatcher;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::getName()
     */
    function getName()
    {
        return $this->_name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::setName()
     */
    function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::setDispatcher()
     */
    function setDispatcher(Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::getDispatcher()
     */
    function getDispatcher()
    {
        return $this->_dispatcher;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::setSubject()
     */
    function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Event\EventInterface::getSubject()
     */
    function getSubject()
    {
        return $this->_subject;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Event\EventInterface::stopPropagation()
     */
    function stopPropagation()
    {
        $this->_propagationStopped = true;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Event\EventInterface::isPropagationStopped()
     */
    function isPropagationStopped()
    {
        return $this->_propagationStopped;
    }
}