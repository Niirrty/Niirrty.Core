<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.3.0
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * Each class that implements IStringSerializable is able to be converted to a string, and vice versa.
 *
 * @package UK
 */
interface IStringable
{


    /**
     * Gets the string representation of the instance data for implementing class.
     *
     * @return string
     */
    public function __toString();


}

