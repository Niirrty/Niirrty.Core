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
 * Each class that implement this interface is marked to be able to get all instance data as a associative array and
 * parse a array back to a instance.
 *
 * @since v0.1.0
 */
interface IArrayable
{


    /**
     * Returns all instance data as an associative array.
     *
     * @return array
     */
    public function toArray() : array;

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

