<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-25
 * @version        0.1.0
 */


declare( strict_types = 1 );


namespace Niirrty\Tests\Fixtures;


class Serializeable
{

   public $foo;

   public function __construct( $foo )
   {
      $this->foo = $foo;
   }

}

