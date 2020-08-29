<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-26
 * @version        0.1.0
 */


namespace Niirrty\Tests;


use Niirrty\ArgumentException;
use PHPUnit\Framework\TestCase;


class ArgumentExceptionTest extends TestCase
{


    public function testGetArgumentName()
    {
        try { throw new ArgumentException( 'foo', 'Foo value' ); }
        catch ( ArgumentException $ex ) { $this->assertSame( 'foo', $ex->getArgumentName() ); }
        try { throw new ArgumentException( 'foo', null ); }
        catch ( ArgumentException $ex ) {}
        try { throw new ArgumentException( 'foo', 14 ); }
        catch ( ArgumentException $ex ) { }
        try { throw new ArgumentException( 'foo', 14.72 ); }
        catch ( ArgumentException $ex ) { }
        try { throw new ArgumentException( 'foo', false ); }
        catch ( ArgumentException $ex ) { }
        try { throw new ArgumentException( 'foo', [ ':-)' ] ); }
        catch ( ArgumentException $ex ) { }
        try { throw new ArgumentException( 'foo', \str_repeat( 'a b', 128 ) ); }
        catch ( ArgumentException $ex ) { }
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        try { throw new ArgumentException( 'foo', $fp ); }
        catch ( ArgumentException $ex ) { }
        fclose( $fp );
    }
    public function testGetFunctionName()
    {
        try { throw new ArgumentException( 'foo', 'Foo value', 'A optional message' ); }
        catch ( ArgumentException $ex )
        {
            $this->assertSame( 'PHPUnit\\TextUI\\Command::main', $ex->getFunctionName() );
        }
    }


}
