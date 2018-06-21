<?php

/*
 * This file is part of the slince/event-dispatcher package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\EventDispatcher;

interface EventInterface
{
    /**
     * Gets the event name.
     *
     * @return string
     */
    public function getName();
}