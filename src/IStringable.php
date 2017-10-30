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
 * Each class that implements IStringSerializable is able to be converted to a string, and vice versa.
 *
 * @package UK
 */
Interface IStringable
{


   /**
    * Gets the string representation of the instance data for implementing class.
    *
    * @return string
    */
   public function __toString();

   /**
    * Re init the implementing class by a string, that defines all required instance data.
    *
    * @param null|string $str
    * @return \Niirrty\IStringable
    */
   public function fromString( ?string $str );


}

