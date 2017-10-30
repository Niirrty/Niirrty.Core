<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.1.0
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

   /**
    * Re init the implementing class by a array, that defines all required instance data.
    *
    * @param  array $array
    * @return \Niirrty\IStringable
    */
   public function fromArray( array $array );


}

