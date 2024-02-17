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
 * Each class that implements IToString can be converted to a string.
 */
interface IToString
{

    /**
     * Gets the string representation of the instance data for implementing class.
     *
     * @return string
     */
    public function __toString();

}