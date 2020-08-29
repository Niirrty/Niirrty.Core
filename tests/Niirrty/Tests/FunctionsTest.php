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
use function Niirrty\escape;
use const Niirrty\ESCAPE_HTML;
use const Niirrty\ESCAPE_HTML_ALL;
use const Niirrty\ESCAPE_JSON;
use const Niirrty\ESCAPE_URL;
use function Niirrty\escapeXML;
use function Niirrty\escapeXMLArg;
use function Niirrty\jsonDecode;
use function Niirrty\preg_match_callback;
use function Niirrty\print_h;
use function Niirrty\splitLines;
use function Niirrty\strContains;
use function Niirrty\strEndsWith;
use function Niirrty\stripTags;
use function Niirrty\strIReplace;
use function Niirrty\strLastPos;
use function Niirrty\strMax;
use function Niirrty\strPositions;
use function Niirrty\strStartsWith;
use function Niirrty\substring;
use function Niirrty\unescapeXML;
use PHPUnit\Framework\TestCase;


class FunctionsTest extends TestCase
{


    public function testSubstring()
    {

        $this->assertSame( '…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', substring( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', 1 ) );
        $this->assertSame( 'öü¹¬@ł€', substring( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', 2, 7 ) );
        $this->assertSame( '…öü¹¬@ł€¶ŧ←↓→ßÄ', substring( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', 1, -3 ) );

    }

    public function testStrPos()
    {

        $this->assertSame( 11, \Niirrty\strPos( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', '←↓→' ) );
        $this->assertSame( -1, \Niirrty\strPos( '', '←↓→' ) );
        $this->assertSame( -1, \Niirrty\strPos( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', '' ) );
        $this->assertSame( 15, \Niirrty\strPos( 'ä…öü¹¬@ł€¶ŧ←↓→ßÄÖÜØ', 'äöü', true ) );

    }

    public function testStrLastPos()
    {

        $this->assertSame( 2, strLastPos( 'øØæÆłŁäÄ', 'æ' ) );
        $this->assertSame( 3, strLastPos( 'øØæÆłŁäÄ', 'æ', true ) );
        $this->assertSame( -1, strLastPos( 'øØæÆłŁäÄ', '' ) );
        $this->assertSame( -1, strLastPos( '', 'æ' ) );

    }

    public function testStrPositions()
    {

        $this->assertSame( [ 0 ], strPositions( 'øØæÆ', 'ø' ) );
        $this->assertSame( [ 0, 1 ], strPositions( 'øØæÆ', 'ø', true ) );
        $this->assertSame( null, strPositions( 'øØæÆ', 'ä' ) );

    }

    public function testStrStartsWith()
    {

        $this->assertTrue( strStartsWith( 'øØæÆ', 'ø' ) );
        $this->assertFalse( strStartsWith( 'øØæÆ', 'Ø' ) );
        $this->assertTrue( strStartsWith( 'øØæÆ', 'Ø', true ) );

    }

    public function testStrEndsWith()
    {

        $this->assertTrue( strEndsWith( 'øØæÆ', 'Æ' ) );
        $this->assertFalse( strEndsWith( 'øØæÆ', 'æ' ) );
        $this->assertTrue( strEndsWith( 'øØæÆ', 'æ', true ) );
        $this->assertFalse( strEndsWith( 'øØæÆ', '', true ) );
        $this->assertFalse( strEndsWith( 'øØæÆ', 'øØæÆæ', true ) );

    }

    public function testStrContains()
    {

        $this->assertTrue( strContains( 'øØæÆ', 'ø' ) );
        $this->assertFalse( strContains( 'øæÆ', 'Ø' ) );
        $this->assertTrue( strContains( 'øæÆ', 'Ø', true ) );
        $this->assertFalse( strContains( 'øØæÆ', '' ) );

    }

    public function testEscapeXML()
    {

        $this->assertSame( '&lt;foo&amp;"\'bar&gt;', escapeXML( '<foo&"\'bar>' ) );
        $this->assertSame( '&lt;foo&amp;"\'bar&gt;', escapeXML( "<\0foo&\"'bar>" ) );

    }

    public function testEscapeXMLArg()
    {

        $this->assertSame( '&lt;foo&amp;&quot;&apos;bar&gt;', escapeXMLArg( '<foo&"\'bar>' ) );
        $this->assertSame( '&lt;foo&amp;&quot;&apos;bar&gt;', escapeXMLArg( "<\0foo&\"'bar>" ) );

    }

    public function testEscape()
    {

        $this->assertSame( '&lt;foo&amp;"\'bar&gt;', escape( '<foo&"\'bar>', ESCAPE_HTML ) );
        $this->assertSame( '&lt;foo&amp;&quot;&apos;bar&gt;', escape( '<foo&"\'bar>', ESCAPE_HTML_ALL ) );
        $this->assertSame( \json_encode( 'Foo "Bar" \'Baz\'' ), escape( 'Foo "Bar" \'Baz\'', ESCAPE_JSON ) );
        $this->assertSame( \urlencode( 'Foo "Bar" \'Baz\'' ), escape( 'Foo "Bar" \'Baz\'', ESCAPE_URL ) );
        $this->assertSame( '&lt;foo&amp;&quot;&apos;bar&gt;', escape( '<foo&"\'bar>', 'unknown' ) );

    }

    public function testUnescapeXML()
    {

        $this->assertSame( '<>"&äÄöÖüÜ\'°ß\' ',
                                 unescapeXML( '&lt;&gt;&quot;&amp;&auml;&Auml;&ouml;&Ouml;&uuml;&Uuml;&#39;&deg;&szlig;&apos;&nbsp;',
                                                  false ) );
        $this->assertSame( '€P<>"&äÄöÖüÜ\'°ß\' ',
                                 unescapeXML( '&euro;&#80;&lt;&gt;&quot;&amp;&auml;&Auml;&ouml;&Ouml;&uuml;&Uuml;&#39;&deg;&szlig;&apos;&nbsp;',
                                                  true ) );

    }

    public function testStrMax()
    {

        $this->assertSame( 'foo', strMax( 'foo', 3 ) );
        $this->assertSame( 'fo…', strMax( 'fooo', 3 ) );
        $this->assertSame( 'fo...', strMax( 'foo000', 5, '...' ) );

    }

    public function testStrIReplace()
    {

        $this->assertSame( '---bar.bar.bar.F00', strIReplace( 'foo', 'bar', '---foo.Foo.FOO.F00' ) );
        #$this->assertSame( 'fo…', strMax( 'fooo', 3 ) );
        #$this->assertSame( 'fo...', strMax( 'foo000', 5, '...' ) );

    }

    public function testStripTags()
    {

        $this->assertSame( 'Visible', stripTags( '<foo><!-- Invisible -->Visible</foo><script>document.write( \'<p>:-(</p>\' )</script>' ) );

    }

    public function testPrint_h()
    {

        $this->expectOutputString( '<pre>Array(    [0] =&gt; 14.147)</pre>' );
        print_h( [ 14.147 ] );

    }

    public function testSplitLines()
    {

        $this->assertSame( [ 'Foo', 'Bar', '', 'Baz', 'Blub' ], splitLines( "Foo\nBar\r\n\r\nBaz\rBlub" ) );

    }

    public function testPreg_match_callback()
    {

        $this->assertTrue(
            preg_match_callback(
                '~^<a\s+href="([^"]+)"~',
                '<a href="https://example.com/">…</a>',
                function ( array $matches ) : bool { return strStartsWith( $matches[ 1 ], 'https://' ); },
                $matches
            )
        );
        $this->assertFalse(
            preg_match_callback(
                '~^<a\s+href="([^"]+)"~',
                '<a href="http://example.com/">…</a>',
                function ( array $matches ) : bool { return strStartsWith( $matches[ 1 ], 'https://' ); },
                $matches
            )
        );
        $this->assertFalse(
            preg_match_callback(
                '~^<a\s+href="([^"]+)"~',
                '<a id="foo" href="ftp://example.com">…</a>',
                function ( array $matches ) : bool { return strStartsWith( $matches[ 1 ], 'https://' ); },
                $matches
            )
        );
        $this->assertTrue(
            preg_match_callback(
                '~^<a\s+href="([^"]+)"~',
                '<a href="https://example.com/">…</a>',
                null,
                $matches
            )
        );

    }

    public function testJsonDecode()
    {

        $this->assertSame( [ 'Foo', 'Bar' ], jsonDecode( 'FooBar([ "Foo", "Bar" ]);', true ) );
        $this->assertSame( [ 'Foo', 'Bar' ], jsonDecode( '[ "Foo", "Bar" ]', true ) );

    }




}
