<?php
namespace Slince\Event;

use GuzzleHttp\Event\EventInterface;

interface EventInterface{
    function setName($name);
    
    function getName();
    
    function setDispatcher();
}