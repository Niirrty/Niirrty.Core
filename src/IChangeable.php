<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.2.0
 */


declare( strict_types = 1 );


namespace Niirrty;


interface IChangeable
{


   /**
    * Gets if something has changed.
    *
    * @return bool
    */
   public function isChanged() : bool;

}

