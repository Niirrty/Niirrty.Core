<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-03-24
 * @version        0.1.0
 */


namespace Niirrty\Tests;


use Niirrty\ArgumentException;
use Niirrty\ArrayHelper;
use PHPUnit\Framework\TestCase;


class ArrayHelperTest extends TestCase
{

    private const PARSE_ATTRIBUTES = [
        'foo=":-)" Bar=\'1\' baz="12" enabled="true" abc="&lt;strong&gt;Text&lt;/strong&gt;"' => [
            [ 'foo' => ':-)', 'Bar' => true, 'baz' => '12', 'enabled' => true, 'abc' => '<strong>Text</strong>' ], // false, true
            [ 'foo' => ':-)', 'bar' => true, 'baz' => '12', 'enabled' => true, 'abc' => '<strong>Text</strong>' ], // true, true
            [ 'foo' => ':-)', 'Bar' => '1', 'baz' => '12', 'enabled' => 'true', 'abc' => '<strong>Text</strong>' ], // false, false
            [ 'foo' => ':-)', 'bar' => '1', 'baz' => '12', 'enabled' => 'true', 'abc' => '<strong>Text</strong>' ]  // true, false
        ]
    ];

    public function setUp()
    {

        parent::setUp();

    }

    public function testParseAttributes()
    {

        foreach ( self::PARSE_ATTRIBUTES as $str => $expected )
        {
            $this->assertSame( $expected[ 0 ], ArrayHelper::ParseAttributes( $str, false, true ) );
            $this->assertSame( $expected[ 1 ], ArrayHelper::ParseAttributes( $str, true, true ) );
            $this->assertSame( $expected[ 2 ], ArrayHelper::ParseAttributes( $str, false, false ) );
            $this->assertSame( $expected[ 3 ], ArrayHelper::ParseAttributes( $str, true, false ) );
        }

        $this->assertSame( [], ArrayHelper::ParseAttributes( 'Foo"BAR">BAZ', true, false ) );

        $this->assertSame( [ 'multiple' => true ], ArrayHelper::ParseAttributes( ' multiple="multiple"', false, true ) );

    }

    public function testParseHtmlAttributes()
    {

        $this->assertSame(
            [ 'foo' => false, 'bar' => 'abc', 'enabled' => true, 'disabled' => true ],
            ArrayHelper::ParseHtmlAttributes( 'foo=0 bar=abc enabled disabled=disabled', true )
        );
        $this->assertSame(
            [ 'foo' => '0', 'bar' => 'abc', 'enabled' => '' ],
            ArrayHelper::ParseHtmlAttributes( 'foo=0 bar=abc enabled', false )
        );
        $this->assertSame(
            [],
            ArrayHelper::ParseHtmlAttributes( 'abc~über"$"' )
        );
        $this->assertSame(
            [],
            ArrayHelper::ParseHtmlAttributes( '' )
        );

    }

    public function testIsNumericIndicated()
    {

        $this->assertTrue( ArrayHelper::IsNumericIndicated( [ 0, 'a', '2' ] ) );
        $this->assertTrue( ArrayHelper::IsNumericIndicated( [ 0, 2, 5 ] ) );
        $this->assertFalse( ArrayHelper::IsNumericIndicated( [ 2 => 0, 2, 5 ] ) );
        $this->assertTrue( ArrayHelper::IsNumericIndicated( [] ) );

    }

    public function testCreateAttributeString()
    {

        $this->assertSame( ' a="4" b="0" _5="A&quot;B&quot;" _6="2016-06-21 13:00:00"',
                                 ArrayHelper::CreateAttributeString(
                                     [ 'a'=>4, 'b'=>false, 5=>'A"B"', new \DateTime( '2016-06-21 13:00:00' ) ]
                                 )
        );
        $this->assertSame( '', ArrayHelper::CreateAttributeString( [] ) );

    }

    public function testCreateAttributeStringException()
    {

        $this->expectException( ArgumentException::class );

        ArrayHelper::CreateAttributeString( [ 'a'=> new \stdClass() ] );

    }

    public function testInsert()
    {

        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Insert( [ 'bar' ], 'foo', 0 ) );
        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Insert( [ 'bar' ], 'foo', -1 ) );
        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Insert( [ 'foo' ], 'bar', 1 ) );
        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Insert( [ 'foo' ], 'bar', 3 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz' ],
                                 ArrayHelper::Insert( [ 'foo' ], [ 'bar', 'baz' ], 1 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz' ],
                                 ArrayHelper::Insert( [ 'bar', 'baz' ], [ 'foo' ], 0 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz' ],
                                 ArrayHelper::Insert( [ 'foo', 'baz' ], 'bar', 1 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz', 'blub' ],
                                 ArrayHelper::Insert( [ 'foo', 'blub' ], [ 'bar', 'baz' ], 1 ) );

    }

    public function testRemove()
    {

        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Remove( [ 'foo', 'bar' ], -1 ) );
        $this->assertSame( [ 'foo', 'bar' ], ArrayHelper::Remove( [ 'foo', 'bar' ], 5 ) );
        $this->assertSame( [ 'bar' ], ArrayHelper::Remove( [ 'foo', 'bar' ], 0 ) );
        $this->assertSame( [ 'foo' ], ArrayHelper::Remove( [ 'foo', 'bar' ], 1 ) );
        $this->assertSame( [ 'foo', 'baz' ], ArrayHelper::Remove( [ 'foo', 'bar', 'baz' ], 1 ) );

    }

    public function testRemoveRange()
    {

        $this->assertSame( [ 'foo', 'bar', 'baz' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], -1, 0 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], 3, 1 ) );
        $this->assertSame( [ 'bar', 'baz' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], 0, 1 ) );
        $this->assertSame( [ 'foo' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], 1, 2 ) );
        $this->assertSame( [ 'foo' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], 1, null ) );
        $this->assertSame( [ 'baz' ],
                                 ArrayHelper::RemoveRange( [ 'foo', 'bar', 'baz' ], 0, -1 ) );

    }

    public function testGetMaxDepth()
    {

        $this->assertSame( 0, ArrayHelper::GetMaxDepth( [] ) );
        $this->assertSame( 1, ArrayHelper::GetMaxDepth( [ 'foo', 'bar', 'baz' ] ) );
        $this->assertSame( 1, ArrayHelper::GetMaxDepth( [ 'foo', 'bar', [] ] ) );
        $this->assertSame( 2, ArrayHelper::GetMaxDepth( [ 'foo', 'bar', [ 'x' ] ] ) );
        $this->assertSame( 2, ArrayHelper::GetMaxDepth( [ 'foo', 'bar', [ 'x', [] ] ] ) );
        $this->assertSame( 4, ArrayHelper::GetMaxDepth( [ 'foo', 'bar', [ 'x', [ 'y', [ 'z' ] ] ] ] ) );

    }

    public function testIsSingleDepth()
    {

        $this->assertFalse( ArrayHelper::IsSingleDepth( [] ) );
        $this->assertTrue( ArrayHelper::IsSingleDepth( [ [] ] ) );

    }

    public function testExtract()
    {

        $this->assertSame( [],
                                 ArrayHelper::Extract( [], -1, 0 ) );
        $this->assertSame( [],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 4, 1 ) );
        $this->assertSame( [],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 3, 0 ) );
        $this->assertSame( [ 'blub' ],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 3, 1 ) );
        $this->assertSame( [ 'blub' ],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 3, 2 ) );
        $this->assertSame( [ 'bar', 'baz', 'blub' ],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 1 ) );
        $this->assertSame( [ 'bar', 'baz' ],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 1, -1 ) );
        $this->assertSame( [ 'foo', 'bar', 'baz', 'blub' ],
                                 ArrayHelper::Extract( [ 'foo', 'bar', 'baz', 'blub' ], 0 ) );

    }

    #public function test() { }

}
