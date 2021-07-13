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
 * You should implement this interface if your class should return if it declares valid data.
 *
 * @package Niirrty
 */
interface IValidStatus
{


    /**
     * Gets if the instance defines valid data.
     *
     * @return bool
     */
    public function isValid() : bool;

}
