<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfServiceReference represents a service reference.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class sfServiceReference
{
    protected $id;

    /**
     * Constructor.
     *
     * @param string $id The service identifier
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * __toString.
     *
     * @return string The service identifier
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
