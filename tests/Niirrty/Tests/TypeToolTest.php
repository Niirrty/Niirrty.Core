<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-25
 * @version        0.1.0
 */


namespace Niirrty\Tests;


use Niirrty\ArgumentException;
use Niirrty\Tests\Fixtures\Serializeable;
use Niirrty\Tests\Fixtures\Stringable;
use Niirrty\Type;
use Niirrty\TypeTool;
use PHPUnit\Framework\TestCase;


class TypeToolTest extends TestCase
{

    public function testIsInteger()
    {

        $this->assertTrue( TypeTool::IsInteger( 1 ) );
        $this->assertTrue( TypeTool::IsInteger( '10' ) );
        $this->assertTrue( TypeTool::IsInteger( 0x01 ) );
        $this->assertTrue( TypeTool::IsInteger( 1.0 ) );
        $this->assertFalse( TypeTool::IsInteger( 'a' ) );
        $this->assertFalse( TypeTool::IsInteger( false ) );
        $this->assertFalse( TypeTool::IsInteger( [] ) );

    }

    public function testTryParseInteger()
    {

        $this->assertTrue( TypeTool::TryParseInteger( 10, $int1 ) );
        $this->assertSame( 10, $int1 );
        $this->assertTrue( TypeTool::TryParseInteger( '12345', $int2 ) );
        $this->assertSame( 12345, $int2 );
        $this->assertTrue( TypeTool::TryParseInteger( true, $int3 ) );
        $this->assertSame( 1, $int3 );
        $this->assertTrue( TypeTool::TryParseInteger( false, $int4 ) );
        $this->assertSame( 0, $int4 );
        $this->assertTrue( TypeTool::TryParseInteger( 14.2, $int5 ) );
        $this->assertSame( 14, $int5 );
        $this->assertTrue( TypeTool::TryParseInteger( 14.51, $int6 ) );
        $this->assertSame( 14, $int6 );
        $this->assertFalse( TypeTool::TryParseInteger( 'abc', $int7 ) );
        $this->assertFalse( TypeTool::TryParseInteger( ( (double) PHP_INT_MAX ) + ( (double) 5000 ), $int8 ) );
        $this->assertFalse( TypeTool::TryParseInteger( new \stdClass(), $int9 ) );

    }

    public function testIsDecimal()
    {

        $this->assertTrue( TypeTool::IsDecimal( 1 ) );
        $this->assertTrue( TypeTool::IsDecimal( 1.0 ) );
        $this->assertTrue( TypeTool::IsDecimal( '145' ) );
        $this->assertTrue( TypeTool::IsDecimal( '145,5', true ) );
        $this->assertFalse( TypeTool::IsDecimal( '145,5', false ) );
        $this->assertTrue( TypeTool::IsDecimal( '145.5' ) );

    }

    public function testTryParseFloat()
    {

        $this->assertTrue( TypeTool::TryParseFloat( 10, $float1 ) );
        $this->assertSame( (float) 10, $float1 );
        $this->assertTrue( TypeTool::TryParseFloat( '12345', $float2 ) );
        $this->assertSame( (float) 12345, $float2 );
        $this->assertTrue( TypeTool::TryParseFloat( true, $float3 ) );
        $this->assertSame( (float) 1, $float3 );
        $this->assertTrue( TypeTool::TryParseFloat( 14.4, $float0 ) );
        $this->assertSame( 14.4, $float0 );
        $this->assertTrue( TypeTool::TryParseFloat( '14.4', $float4 ) );
        $this->assertSame( 14.4, $float4 );
        $this->assertTrue( TypeTool::TryParseFloat( '14,4', $float5, true ) );
        $this->assertSame( 14.4, $float5 );
        $this->assertFalse( TypeTool::TryParseFloat( '14,4', $float6 ) );
        $this->assertFalse( TypeTool::TryParseFloat( '14-4', $float7, true ) );
        $this->assertFalse( TypeTool::TryParseFloat( 'abc', $float8 ) );
        $this->assertFalse( TypeTool::TryParseFloat( new \stdClass(), $float9 ) );

    }

    public function testIsBoolConvertible()
    {

        $this->assertFalse( TypeTool::IsBoolConvertible( null, $bool1 ) );
        $this->assertTrue( TypeTool::IsBoolConvertible( false, $bool1 ) );
        $this->assertFalse( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( true, $bool1 ) );
        $this->assertTrue( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( 1, $bool1 ) );
        $this->assertTrue( $bool1 );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->assertTrue( TypeTool::IsBoolConvertible( $fp, $bool1 ) );
        fclose( $fp );
        $this->assertTrue( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( new Stringable( 'false' ), $bool1 ) );
        $this->assertFalse( $bool1 );
        $this->assertFalse( TypeTool::IsBoolConvertible( new \stdClass(), $bool1 ) );
        $this->assertTrue( TypeTool::IsBoolConvertible( [], $bool1 ) );
        $this->assertFalse( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( [ 0, 1 ], $bool1 ) );
        $this->assertTrue( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( 14.4, $bool1 ) );
        $this->assertTrue( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( \serialize( false ), $bool1 ) );
        $this->assertFalse( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( '', $bool1 ) );
        $this->assertFalse( $bool1 );
        $this->assertTrue( TypeTool::IsBoolConvertible( 'yes', $bool1 ) );
        $this->assertTrue( $bool1 );
        $this->assertFalse( TypeTool::IsBoolConvertible( 'foo', $bool1 ) );

    }

    public function testIsStringConvertible()
    {

        $this->assertTrue( TypeTool::IsStringConvertible( null, $str ) );
        $this->assertSame( '',  $str );
        $this->assertTrue( TypeTool::IsStringConvertible( 'foo', $str ) );
        $this->assertSame( 'foo', $str );
        $this->assertTrue( TypeTool::IsStringConvertible( false, $str ) );
        $this->assertSame( 'false', $str );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->assertFalse( TypeTool::IsStringConvertible( $fp, $str ) );
        fclose( $fp );
        $this->assertSame( '',  $str );
        $this->assertTrue( TypeTool::IsStringConvertible( new \DateTime( '2017-12-24 12:24:36' ), $str ) );
        $this->assertSame( '2017-12-24 12:24:36', $str );
        $this->assertTrue( TypeTool::IsStringConvertible( [ 'foo' ], $str ) );
        $this->assertSame( \serialize( [ 'foo' ] ), $str );
        $this->assertTrue( TypeTool::IsStringConvertible( -123, $str ) );
        $this->assertSame( '-123', $str );
        $this->assertTrue( TypeTool::IsStringConvertible( 12.3, $str ) );
        $this->assertSame( '12.3', $str );
        #$this->assertFalse( TypeTool::IsStringConvertible( 12.3, $str ) );

    }

    public function testStrToType()
    {

        $this->assertTrue( TypeTool::StrToType( '1', 'boolean' ) );
        $this->assertSame( 14, TypeTool::StrToType( '14.4', 'int' ) );
        $this->assertSame( 14, TypeTool::StrToType( '14.4', 'integer' ) );
        $this->assertSame( 0, TypeTool::StrToType( '', 'int' ) );
        $this->assertSame( null, TypeTool::StrToType( 'blub', 'int' ) );
        $this->assertSame( 14.4, TypeTool::StrToType( '14.4', 'float' ) );
        $this->assertSame( 0, TypeTool::StrToType( '', 'float' ) );
        $this->assertSame( null, TypeTool::StrToType( 'sadas', 'float' ) );
        $this->assertSame( '14.000,4', TypeTool::StrToType( '14.000,4', 'string' ) );
        $this->assertSame( [], TypeTool::StrToType( '', 'array' ) );
        $this->assertSame( [ 'foo' ], TypeTool::StrToType( \serialize( [ 'foo' ] ), 'array' ) );
        $this->assertSame( [ 'foo' ], TypeTool::StrToType( \json_encode( [ 'foo' ] ), 'array' ) );
        $this->assertSame( [ '[abc-def>d]' ], TypeTool::StrToType( '[abc-def>d]', 'array' ) );
        $this->assertSame( [ 'a:bc-def' ], TypeTool::StrToType( 'a:bc-def', 'array' ) );
        $this->assertSame( [ '{abc-def>d}' ], TypeTool::StrToType( '{abc-def>d}', 'array' ) );
        $ser = new Serializeable( '2017-12-24 12:00:00' );
        $tmp = \serialize( $ser );
        $this->assertEquals( $ser, TypeTool::StrToType( $tmp, 'Niirrty\\Tests\\Fixtures\\Serializeable' ) );
        $this->assertSame( null, TypeTool::StrToType( '', '\DateTime' ) );
        $this->assertSame( null, TypeTool::StrToType( 'ab', '\DateTime' ) );

    }

    public function testXmlToType()
    {

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<configs>
  <config1 type="int">14</config1>
  <config2>
     <type>float</type>
     <value>.147</value>
  </config2>
  <config3 type="bool" value="false" />
  <config4>
     <Type>string</Type>
     <Value>foo bar</Value>
  </config4>
  <config5 foo="1" />
</configs>
XML;
        $doc = \simplexml_load_string( $xml );
        $this->assertSame( 14, TypeTool::XmlToType( $doc->config1[ 0 ] ) );
        $this->assertSame( .147, TypeTool::XmlToType( $doc->config2[ 0 ] ) );
        $this->assertSame( false, TypeTool::XmlToType( $doc->config3[ 0 ] ) );
        $this->assertSame( 'foo bar', TypeTool::XmlToType( $doc->config4[ 0 ] ) );
        $this->assertSame( null, TypeTool::XmlToType( $doc->config5[ 0 ] ) );

    }

    public function testIsNativeType()
    {

        $this->assertFalse( TypeTool::IsNativeType( null ) );
        $this->assertTrue( TypeTool::IsNativeType( 11 ) );
        $this->assertTrue( TypeTool::IsNativeType( .147 ) );
        $this->assertTrue( TypeTool::IsNativeType( false ) );
        $this->assertTrue( TypeTool::IsNativeType( 'dfsdf' ) );
        $this->assertTrue( TypeTool::IsNativeType( [ 1 ] ) );
        $this->assertFalse( TypeTool::IsNativeType( new \stdClass() ) );

    }

    public function testGetNativeType()
    {

        $this->assertSame( Type::PHP_ARRAY, TypeTool::GetNativeType( [] ) );
        $this->assertSame( Type::PHP_FLOAT, TypeTool::GetNativeType( .147 ) );
        $this->assertSame( Type::PHP_INTEGER, TypeTool::GetNativeType( 147 ) );
        $this->assertSame( Type::PHP_BOOLEAN, TypeTool::GetNativeType( true ) );
        $this->assertSame( Type::PHP_STRING, TypeTool::GetNativeType( 'sds' ) );
        $this->assertFalse( TypeTool::GetNativeType( new \stdClass() ) );

    }

    public function testGetTypeName()
    {

        $this->assertSame( Type::PHP_ARRAY, TypeTool::GetTypeName( [] ) );
        $this->assertSame( Type::PHP_FLOAT, TypeTool::GetTypeName( .147 ) );
        $this->assertSame( Type::PHP_INTEGER, TypeTool::GetTypeName( 0775 ) );
        $this->assertSame( Type::PHP_BOOLEAN, TypeTool::GetTypeName( true ) );
        $this->assertSame( Type::PHP_STRING, TypeTool::GetTypeName( 'sds' ) );
        $this->assertSame( Type::PHP_NULL, TypeTool::GetTypeName( null ) );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->assertSame( Type::PHP_RESOURCE, TypeTool::GetTypeName( $fp ) );
        fclose( $fp );
        $this->assertSame( 'DateTime', TypeTool::GetTypeName( new \DateTime() ) );

    }

    public function testConvertNative()
    {

        $this->assertSame( null, TypeTool::ConvertNative( null, Type::PHP_NULL ) );
        $this->assertSame( 123, TypeTool::ConvertNative( '123', Type::PHP_INTEGER ) );
        $this->assertSame( true, TypeTool::ConvertNative( '1', Type::PHP_BOOLEAN ) );
        $this->assertSame( false, TypeTool::ConvertNative( 'false', 'boolean' ) );
        $this->assertSame( (float) 123, TypeTool::ConvertNative( '123', Type::PHP_FLOAT ) );
        $this->assertSame( '123', TypeTool::ConvertNative( '123', Type::PHP_STRING ) );
        $this->assertSame( '1', TypeTool::ConvertNative( true, Type::PHP_STRING ) );
        $this->assertSame( (float) 1, TypeTool::ConvertNative( true, Type::PHP_FLOAT ) );
        $this->assertSame( 1, TypeTool::ConvertNative( true, Type::PHP_INTEGER ) );
        $this->assertSame( 14, TypeTool::ConvertNative( 14.4, Type::PHP_INTEGER ) );
        $this->assertSame( false, TypeTool::ConvertNative( -1.4, Type::PHP_BOOLEAN ) );
        $this->assertSame( '1.44', TypeTool::ConvertNative( 1.44, Type::PHP_STRING ) );
        $this->assertSame( 14, TypeTool::ConvertNative( 14, Type::PHP_FLOAT ) );
        $this->assertSame( false, TypeTool::ConvertNative( 0, Type::PHP_BOOLEAN ) );
        $this->assertSame( '-144', TypeTool::ConvertNative( -144, Type::PHP_STRING ) );

    }

    public function testConvertNativeException1()
    {

        $this->expectException( ArgumentException::class );
        TypeTool::ConvertNative( new \stdClass(), Type::PHP_STRING );

    }

    public function testConvertNativeException2()
    {

        $this->expectException( ArgumentException::class );
        TypeTool::ConvertNative( false, Type::PHP_ARRAY );

    }

    public function testConvertNativeException3()
    {

        $this->expectException( ArgumentException::class );
        TypeTool::ConvertNative( '123', Type::PHP_RESOURCE );

    }

    public function testConvertNativeException4()
    {

        $this->expectException( ArgumentException::class );
        TypeTool::ConvertNative( 12.3, Type::PHP_RESOURCE );

    }

    public function testConvertNativeException5()
    {

        $this->expectException( ArgumentException::class );
        TypeTool::ConvertNative( 12, Type::PHP_RESOURCE );

    }

}
