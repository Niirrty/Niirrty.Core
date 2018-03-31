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


/**
 * Each class that offer some specific instance info for use inside errors, should implement this interface.
 *
 * @since v0.1.0
 */
interface IErrorInfo
{


   /**
    * Gets information about the instance of the implementing class, that can be used for some error reasons.
    *
    * @return string
    */
   public function getErrorInfoString() : string;


}

