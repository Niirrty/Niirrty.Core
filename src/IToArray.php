<?php
/**
 * @author    Ni Irrty <niirrty+code@gmail.com>
 * @copyright © 2024, Niirrty
 * @package   Niirrty
 * @since     2024-02-17
 * @version   0.6.2
 */


declare( strict_types = 1 );


namespace Niirrty;

/**
 * Each class that implement this interface is marked to be able to get all instance data as an associative array.
 */
interface IToArray
{

    /**
     * Return all data of the implementing instance as array.
     *
     * @return array
     */
    public function toArray() : array;

}