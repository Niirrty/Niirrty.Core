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
 * A class that should be disposable, must implement this interface.
 *
 * Disposable means a instance that point to some resource/class/Callback that should be closed before it is destroyed.
 *
 * It means the destructor must be implemented and
 *
 * @since v0.1.1
 */
interface IDisposable
{

   /**
    * Gets if the instance is already disposed.
    *
    * @return bool
    */
   public function disposed() : bool;

   /**
    * Disposes the instance
    */
   public function __destruct();

}

