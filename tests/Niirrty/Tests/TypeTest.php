<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-30
 * @version        0.1.0
 */


namespace Niirrty\Tests;


use Niirrty\NiirrtyException;
use Niirrty\Tests\Fixtures\Serializeable;
use Niirrty\Type;
use PHPUnit\Framework\TestCase;


class TypeTest extends TestCase
{


    /** @type \Niirrty\Type */
    private $typeStr;
    /** @type \Niirrty\Type */
    private $typeInt;
    /** @type \Niirrty\Type */
    private $typeBool;
    /** @type \Niirrty\Type */
    private $typeFloat;
    /** @type \Niirrty\Type */
    private $typeArray;
    /** @type \Niirrty\Type */
    private $typeObject;
    /** @type \Niirrty\Type */
    private $typeNull;
    /** @type \Niirrty\Type */
    private $typeRes;

    public function setUp() : void/* The :void return type declaration that should be here would cause a BC issue */
    {

        $this->typeStr    = new Type( 'A string …øł¶€ŧ←↓→' );
        $this->typeInt    = new Type( 12345 );
        $this->typeBool   = new Type( false );
        $this->typeFloat  = new Type( 14.47 );
        $this->typeArray  = new Type( [ 1, 4, [ 7 ] ] );
        $this->typeObject = new Type( new \DateTime( '2017-08-12 14:23:00' ) );
        $this->typeNull   = new Type( null );

        parent::setUp();

    }

    public function testConstruct()
    {

        $this->assertSame( 'A string …øł¶€ŧ←↓→', (string) $this->typeStr );
        $this->assertSame( '12345', (string) $this->typeInt );
        $this->assertSame( 'false', (string) $this->typeBool );
        $this->assertSame( '14.47', (string) $this->typeFloat );
        $this->assertSame( serialize( [ 1, 4, [ 7 ] ] ), (string) $this->typeArray );
        $this->assertSame( '2017-08-12 14:23:00', (string) $this->typeObject );
        $this->assertSame( '', (string) $this->typeNull );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->typeRes    = new Type( $fp );
        $this->assertSame( '', (string) $this->typeRes );
        $this->typeRes    = null;
        fclose( $fp );

    }

    public function testConstructException()
    {
        $this->expectException( NiirrtyException::class );
        new Type( new Type( 'foo' ) );
    }

    public function testEquals()
    {

        $this->assertTrue( $this->typeStr->equals( 'A string …øł¶€ŧ←↓→', false ) );
        $this->assertFalse( $this->typeInt->equals( '12345', true ) );
        $this->assertTrue( $this->typeFloat->equals( '14.47', false ) );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->typeRes    = new Type( $fp );
        $this->assertTrue( $this->typeRes->equals( $fp, true ) );
        $this->typeRes    = null;
        fclose( $fp );

    }

    public function testGetValue()
    {

        $this->assertSame( 'A string …øł¶€ŧ←↓→', $this->typeStr->getValue() );
        $this->assertSame( 12345, $this->typeInt->getValue() );
        $this->assertSame( 14.47, $this->typeFloat->getValue() );

    }

    public function testGetStringValue()
    {

        $this->assertSame( 'A string …øł¶€ŧ←↓→', $this->typeStr->getStringValue() );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->typeRes    = new Type( $fp );
        $this->assertSame( '<NULL>', $this->typeRes->getStringValue( '<NULL>' ) );
        $this->typeRes    = null;
        fclose( $fp );

    }

    public function testHasAssociatedString()
    {

        $this->assertTrue( $this->typeArray->hasAssociatedString() );
        $this->assertTrue( $this->typeBool->hasAssociatedString() );
        $this->assertTrue( $this->typeFloat->hasAssociatedString() );
        $this->assertTrue( $this->typeInt->hasAssociatedString() );
        $this->assertTrue( $this->typeNull->hasAssociatedString() );
        $this->assertTrue( $this->typeObject->hasAssociatedString() );
        $this->assertFalse( ( new Type( new \stdClass() ) )->hasAssociatedString() );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->typeRes    = new Type( $fp );
        $this->assertFalse( $this->typeRes->hasAssociatedString() );
        $this->typeRes    = null;
        fclose( $fp );

    }

    public function testGetType()
    {

        $this->assertSame( Type::PHP_ARRAY, $this->typeArray->getType() );
        $this->assertSame( Type::PHP_BOOLEAN, $this->typeBool->getType() );
        $this->assertSame( Type::PHP_FLOAT, $this->typeFloat->getType() );
        $this->assertSame( Type::PHP_INTEGER, $this->typeInt->getType() );
        $this->assertSame( Type::PHP_NULL, $this->typeNull->getType() );
        $this->assertSame( 'DateTime', $this->typeObject->getType() );
        $fp = fopen( tempnam( sys_get_temp_dir(), 'tst' ), 'rb' );
        $this->typeRes    = new Type( $fp );
        $this->assertSame( Type::PHP_RESOURCE, $this->typeRes->getType() );
        $this->typeRes    = null;
        fclose( $fp );

    }

    public function testIsResource()
    {

        $this->assertFalse( $this->typeObject->isResource() );

    }

    public function testGetPhpCode()
    {
        $this->assertSame( '\'A string …øł¶€ŧ←↓→\'', $this->typeStr->getPhpCode() );
        $this->assertSame( '12345', $this->typeInt->getPhpCode() );
        $this->assertSame( 'false', $this->typeBool->getPhpCode() );
        $this->assertSame( '14.47', $this->typeFloat->getPhpCode() );
        $this->assertSame( '\\unserialize("a:3:{i:0;i:1;i:1;i:4;i:2;a:1:{i:0;i:7;}}")', $this->typeArray->getPhpCode() );
        //$this->assertSame( '\\unserialize("O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2017-08-12 14:23:00.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}")', $this->typeObject->getPhpCode() );
        $this->assertSame( 'null', $this->typeNull->getPhpCode() );
        $this->assertSame( '"\'\\"\r\n\t:-)"', ( new Type( "'\"\r\n\t:-)" ) )->getPhpCode() );
        //$this->assertSame( '\\unserialize("O:36:\"Niirrty\\\\Tests\\\\Fixtures\\\\Serializeable\":1:{s:3:\"foo\";s:9:\":-)\r\n\t:-)\";}")', ( new Type( new Serializeable( ":-)\r\n\t:-)" ) ) )->getPhpCode() );
    }

    public function testClone()
    {

        $this->assertNotSame( $this->typeInt, clone $this->typeInt );
        $this->assertEquals( $this->typeInt, clone $this->typeInt );
        $this->assertNotSame( $this->typeObject, clone $this->typeObject );
        $this->assertEquals( $this->typeObject, clone $this->typeObject );

    }

}

