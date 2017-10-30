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
 * Trait TDisposable. If your class uses this trait, it implements a part of the {@see \Niirrty\IDisposable} interface
 *
 * @since v0.1.0
 */
trait TDisposable
{


   /**
    * Holds the state if the instance is marked as disposed.
    *
    * @var bool
    */
   private  $_disposed = false;

   /**
    * Gets if the instance is already disposed an not usable.
    *
    * @return bool
    */
   public function disposed() : bool
   {

      return $this->_disposed;

   }

   /**
    * Marks the instance as disposed.
    */
   protected  function dispose()
   {

      $this->_disposed = true;

   }


}

