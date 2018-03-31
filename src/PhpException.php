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
 * Defines a class that …
 *
 * @since v0.1.0
 */
class PhpException extends NiirrtyException
{


   // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

   /**
    * PhpException constructor.
    *
    * @param string $msg
    * @param int    $code
    * @param int    $line
    * @param        $file
    */
   public function __construct( $msg, $code, $line, $file )
   {

      parent::__construct( \strip_tags( $msg ), $code );

      $this->file = $file;
      $this->line = $line;

   }

   // </editor-fold>


}

