<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2020, Ni Irrty
 * @license        MIT
 * @since          2020-08-27
 * @version        0.2.1
 */


namespace Niirrty\Tests;


use Niirrty\OperationNotSupportedException;
use PHPUnit\Framework\TestCase;


class OperationNotSupportedExceptionTest extends TestCase
{


   public function testGetFunctionName()
   {
      try { throw new OperationNotSupportedException( 'A optional message' ); }
      catch ( OperationNotSupportedException $ex )
      {
         $this->assertSame( 'PHPUnit\\TextUI\\Command::main', $ex->getFunctionName() );
      }
   }


}
