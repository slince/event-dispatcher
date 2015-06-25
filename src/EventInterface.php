<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

interface EventInterface
{

    function setName($name);

    function getName();

    function setDispatcher(DispatcherInterface $dispatcher);

    function getDispatcher();
    
    function setSubject($subject);
    
    function getSubject();

    function stopPropagation();

    function isPropagationStopped();
}