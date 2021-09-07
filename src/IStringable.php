<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.5.0
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

    /**
     * Allow you to parse a string to a object of the type, that implements this interface.
     *
     * @param string $str          The string that should be parsed
     * @param bool   $throwOnError Defines if the Method should throw an Exception on error.
     * @return bool|static It returns false if parsing false. Otherwise the implementing instance is returned
     * @throws ArgumentException If the string defines invalid/no data and only if $throwOnError is set to true.
     */
    public static function FromString( string $str, bool $throwOnError = false ) : bool|static;


}

