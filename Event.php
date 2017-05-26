<?php
/**
 * slince event dispatcher library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Event;

class Event
{
    /**
     * The event name
     * @var string
     */
    protected $name;

    /**
     * The subject
     * @var object
     */
    protected $subject;

    /**
     * Array of arguments
     * @var array
     */
    protected $arguments = [];

    /**
     * Whether the propagation is stopped
     * @var boolean
     */
    protected $propagationStopped = false;

    public function __construct($name, $subject = null, array $arguments = [])
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->arguments = $arguments;
    }


    /**
     * Gets the  event name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the event name
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Sets the subject
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Gets the subject
     * @return null|object
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets a argument to the event
     * @param string $name
     * @param mixed $value
     */
    public function setArgument($name, $value)
    {
        $this->arguments[$name] = $value;
    }

    /**
     * Gets the argument by its key
     * @param string $name
     * @return mixed
     */
    public function getArgument($name)
    {
        return isset($this->arguments[$name]) ? $this->arguments[$name] : null;
    }

    /**
     * Sets array of arguments
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Gets all arguments
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Stop event propagation
     * @return $this
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
        return $this;
    }

    /**
     * Checks whether propagation was stopped.
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
