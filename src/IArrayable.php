<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2024, Niirrty
 * @package    Niirrty
 * @since      2017-10-30
 * @version    0.6.2
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * Each class that implement this interface is marked to be able to get all instance data as an associative array and
 * parse an array back to an instance.
 */
interface IArrayable extends IToArray
{

    /**
     * Allow you to parse a arrray to a object of the type, that implements this interface.
     *
     * @param array $array        The array that should be parsed
     * @param bool  $throwOnError Defines if the Method should throw an Exception on error.
     * @return bool|static It returns false if parsing false. Otherwise the implementing instance is returned
     * @throws ArgumentException If the array defines invalid/no data and only if $throwOnError is set to true.
     */
    public static function FromArray( array $array, bool $throwOnError = false ) : bool|static;

}
