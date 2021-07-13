<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.4.0
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * Each class that implement this interface is marked to be able to get all instance data as a associative array.
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


}

