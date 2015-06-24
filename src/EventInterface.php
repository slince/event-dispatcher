<?php
namespace Slince\Event;

interface EventInterface
{

    function setName($name);

    function getName();

    function setDispatcher();
}