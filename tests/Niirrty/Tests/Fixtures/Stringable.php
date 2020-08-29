<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-25
 * @version        0.1.0
 */


declare( strict_types = 1 );


namespace Niirrty\Tests\Fixtures;


use Niirrty\IStringable;


class Stringable implements IStringable
{


    private $_value;


    public function __construct( $value )
    {

        $this->_value = $value;

    }


    /**
     * Gets the string representation of the instance data for implementing class.
     *
     * @return string
     */
    public function __toString()
    {

        return (string) $this->_value;

    }

}

