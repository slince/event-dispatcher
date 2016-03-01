<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface EventInterface
{

    /**
     * 设置事件名
     * @param string $name
     */
    function setName($name);

    /**
     * 获取事件名
     */
    function getName();

    /**
     * 设置调度器
     * @param DispatcherInterface $dispatcher
     */
    function setDispatcher(DispatcherInterface $dispatcher);

    /**
     * 获取调度器
     */
    function getDispatcher();
    
    /**
     * 设置subject
     * @param string $subject
     */
    function setSubject($subject);
    
    /**
     * 获取subject
     */
    function getSubject();

    /**
     * 阻止冒泡
     */
    function stopPropagation();

    /**
     * 检查是否阻止冒泡
     */
    function isPropagationStopped();
}