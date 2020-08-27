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
 * Is thrown if an method call is not supported by current context
 */
class OperationNotSupportedException extends NiirrtyException
{


   // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * The function or method name.
    *
    * @var string
    */
   protected $_functionName;

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

   /**
    * Init a new instance.
    *
    * @param string|null     $message  The error message (default=null)
    * @param int|string      $code     The error code (default=\E_USER_ERROR)
    * @param \Throwable|null $previous A optional previous exception
    */
   public function __construct(
      string $message = null, $code = 256, ?\Throwable $previous = null )
   {

      // Getting the debug backtrace to find out the method/function that is called with an bad argument.
      $trace = \debug_backtrace();

      // Getting the index of the last trace element.
      $lIdx  = \count( $trace ) - 1;

      $foo = 10;

      // Getting the method or function name.
      $this->_functionName = ( empty( $trace[ $lIdx ][ 'class' ] )    ? '' : $trace[ $lIdx ][ 'class' ] )
                           . ( empty( $trace[ $lIdx ][ 'type' ] )     ? '' : $trace[ $lIdx ][ 'type' ] )
                           . ( empty( $trace[ $lIdx ][ 'function' ] ) ? '' : $trace[ $lIdx ][ 'function' ] );

      // Init with parent constructor
      parent::__construct(
         'Unsupported operation'
         .  ( empty( $this->_functionName ) ? '' : ' in ' . $this->_functionName . '(…)' )
         .  '!'
         .  static::appendMessage( $message ),
         $code,
         $previous
      );

   }

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * Returns the value of the error argument/parameter.
    *
    * @return mixed
    */
   public final function getFunctionName() : string
   {

      return $this->_functionName;

   }

   // </editor-fold>


}


