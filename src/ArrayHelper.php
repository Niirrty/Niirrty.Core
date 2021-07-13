<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.4.0
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * A static class for doing some array helping things.
 */
abstract class ArrayHelper
{


    #region // – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –

    /**
     * Extracts some associative array data from a XML attribute format string. (e.g.: a="20")
     *
     * Entities are auto converted to unicode UTF-8 characters!
     *
     * yes|no|on|off|true|false will be converted automatically to a boolean value.
     *
     * @param  string  $attributeStr The XML attribute string to parse.
     * @param  boolean $lowerKeys    Convert all keys (attribute names) to lower case? (defaults to FALSE)
     * @param  boolean $autoBoolean  Auto convert the values yes|no|on|off|true|false to boolean? (defaults to TRUE)
     * @return array
     */
    public static function ParseAttributes(
        string $attributeStr, bool $lowerKeys = false, bool $autoBoolean = true ) : array
    {

        // This init the resulting attribute array
        $attributes = [];
        // It will save the hits from preg_match_all
        $hits       = null;

        // Find all defined attributes
        if ( ! \preg_match_all( '~(?<=\A|[ \r\n\t])(\w+)=(\'([^\']*)\'|"([^"]+)")~', $attributeStr, $hits ) )
        {
            // No attributes was found. Return the empty result array
            return $attributes;
        }

        // Loop all found attribute regexp hits
        for ( $i = 0, $j = \count( $hits[ 0 ] ); $i < $j; $i++ )
        {

            // Get the defined key (in lowercase if defined)
            $key = $lowerKeys
                ? \strtolower( $hits[ 1 ][ $i ] )
                : $hits[ 1 ][ $i ];

            if ( isset( $hits[ 4 ][ $i ] ) && ! empty( $hits[ 4 ][ $i ] ) )
            {
                // Get the value from inside double quotes
                $attributes[ $key ] = unescapeXML( $hits[ 4 ][ $i ], true );
            }
            else
            {
                // Get the value from inside single quotes
                $attributes[ $key ] = unescapeXML( $hits[ 3 ][ $i ], true );
            }

            // Convert the value to boolean if required and if it makes sense
            if ( $autoBoolean )
            {
                if ( \preg_match( '~^(0|1|true|false|yes|no|on|off)$~i', $attributes[ $key ] ) )
                {
                    $attributes[ $key ] = TypeTool::StrToType( $attributes[ $key ], Type::PHP_BOOLEAN );
                }
                else if ( \preg_match( '~^(multiple|selected|enabled|disabled|readonly|checked|required)$~i', $key ) )
                {
                    $attributes[ $key ] = \strtolower( $key ) === \strtolower( $attributes[ $key ] );
                }
            }

        }

        // Return the resulting array
        return $attributes;

    }

    /**
     * Extracts some associative array data from a HTML attribute format string. (e.g.: a="20" b=foo)
     *
     * Entities are auto converted to unicode UTF-8 characters!
     *
     * yes|no|on|off|true|false will be converted automatically to a boolean value.
     *
     * @param  string  $attributeStr The HTML attribute string to parse.
     * @param  boolean $autoBoolean  Auto convert the values yes|no|on|off|true|false to boolean? (defaults to FALSE)
     * @return array
     */
    public static function ParseHtmlAttributes( string $attributeStr, bool $autoBoolean = false ) : array
    {

        // This init the resulting attribute array
        $attributes = [];
        $dc = new \DOMDocument();

        try
        {
            if ( ! $dc->loadHTML( '<html lang="en"><body><p ' . $attributeStr . '></p></body></html>' ) )
            {
                return $attributes;
            }
        }
        catch ( \Throwable )
        {
            return $attributes;
        }

        $element = $dc->getElementsByTagName( 'p' )->item( 0 );
        if ( ! $element->hasAttributes() )
        {
            return $attributes;
        }
        $attributeNodes = $element->attributes;

        for ( $i = 0; $i < $attributeNodes->length; $i++ )
        {
            $attr = $attributeNodes->item( $i );
            $key  = $attr->nodeName;
            $attributes[ $key ] = $attr->nodeValue;
            // Convert the value to boolean if required and if it makes sense
            if ( $autoBoolean )
            {
                if ( \preg_match( '~^(yes|no|on|off|true|false|0|1)$~i', $attributes[ $key ] ) )
                {
                    $attributes[ $key ] = TypeTool::StrToType( $attributes[ $key ], Type::PHP_BOOLEAN );
                }
                else if ( \preg_match( '~^(multiple|selected|enabled|disabled|readonly|checked|required)$~i', $key ) )
                {
                    if ( \strtolower( $key ) === \strtolower( $attributes[ $key ] ) )
                    {
                        $attributes[ $key ] = true;
                    }
                    else if ( '' === $attributes[ $key ] )
                    {
                        $attributes[ $key ] = true;
                    }
                }
            }
        }

        return $attributes;

    }

    /**
     * Returns if the defined array is numerically indicated. (0-n)
     *
     * @param  array $array The array to check
     * @return boolean
     */
    public static function IsNumericIndicated( array $array ) : bool
    {

        $itemCount = \count( $array );

        if ( $itemCount < 1 ) { return true; }

        // Create the representative value (the joined array keys must be equal to it)
        $nums = \implode( '', \range( 0, $itemCount - 1 ) );

        // check the required array keys with the given.
        return ( $nums === \implode( '', \array_keys( $array ) ) );

    }

    /**
     * Builds a XML conform attribute string from a associative array (1 dimensional array!)
     *
     * If a array key not begins with a-z, A-Z or a underscore it is prefixed by a underscore!
     *
     * @param  array $attributes The associative array, defining the attributes
     * @return string
     * @throws ArgumentException If a value is not of type sting|bool|int|float|\DateTimeInterface
     */
    public static function CreateAttributeString( array $attributes ) : string
    {

        if ( empty( $attributes ) || static::IsNumericIndicated( $attributes ) )
        {
            // If no attributes are defined, or if the are not a associative array, return a empty string
            return '';
        }

        $res = [];
        foreach ( $attributes as $k => $v )
        {

            $key = $k;
            if ( ! \preg_match( '~^[A-Za-z_]~', (string) $k ) )
            {
                // numeric keys (attribute names) should be prefixed with a underscore
                $key = '_' . $k;
            }

            if ( \is_bool( $v ) )
            {
                $vl = ( $v ? '1' : '0' );
            }
            else if ( \is_int( $v ) || \is_float( $v ) )
            {
                $vl = (string) $v;
            }
            else if ( \is_string( $v ) )
            {
                $vl = escapeXMLArg( $v );
            }
            else if ( $v instanceof \DateTimeInterface )
            {
                $vl = $v->format( 'Y-m-d H:i:s' );
            }
            else
            {
                throw new ArgumentException(
                    "attributes[{$key}]",
                    $v,
                    'Unsupported value type! Accepted types are string, bool, int, float, \\DateTimeInterface'
                );
            }

            $res[] = \sprintf( '%s="%s"', $key, $vl );

        }

        return ' ' . \implode( ' ', $res );

    }

    /**
     * Inserts a new element to $array, at defined index.
     *
     * @param  array   $array   The array where to insert the new element.
     * @param  mixed   $element The element to insert.
     * @param  integer $index   The index of the new element. If lower than 0, 0 is used. If to large its appended to the end.
     * @return array
     */
    public static function Insert( array $array, mixed $element, int $index ) : array
    {

        $cnt = \count( $array );

        if ( $index < 0 )
        {
            $index = 0;
        }
        else if ( $index > $cnt )
        {
            $index = $cnt;
        }

        if ( $index === $cnt )
        {
            if ( \is_array( $element ) )
            {
                $array = \array_merge( $array, $element );
            }
            else
            {
                $array[] = $element;
            }
            return $array;
        }

        if ( $index === 0 )
        {
            if ( \is_array( $element ) )
            {
                $array = \array_merge( $element, $array );
            }
            else
            {
                $array = \array_merge( [ \rtrim( $element, "\r\n" ) ], $array );
            }
            return $array;
        }

        $tmp   = \array_slice( $array, 0, $index );

        if ( \is_array( $element ) )
        {
            $tmp = \array_merge( $tmp, $element );
        }
        else
        {
            $tmp[] = $element;
        }

        return \array_merge( $tmp, \array_slice( $array, $index ) );

    }

    /**
     * Removes the element with defined index from array and reset the array element index after the removed element.
     *
     * @param  array   $array THe array.
     * @param  integer $index The index of the element to remove.
     * @return array
     */
    public static function Remove( array $array, int $index ) : array
    {

        if ( $index < 0 || $index >= \count( $array ) )
        {
            return $array;
        }

        if ( $index === 0 )
        {
            return \array_slice( $array, 1 );
        }

        if ( $index + 1 === \count( $array ) )
        {
            return \array_slice( $array, 0, -1 );
        }

        $neu = \array_slice( $array, 0, $index );
        return \array_merge( $neu, \array_slice( $array, $index + 1 ) );

    }

    /**
     * Removes all elements of the permitted array, beginning at $indexStart, with the defined count, or to the
     * end if no count is defined.
     *
     * @param  array        $array      The array
     * @param  integer      $indexStart The index of the first element to remove.
     * @param  integer|null $length     Optional count of elements to remove (can also be negative, so it counts backward from array end)
     * @return array
     */
    public static function RemoveRange( array $array, int $indexStart, int $length = null ) : array
    {

        // Count the elements of $array
        $cnt = \count( $array );

        if ( $indexStart < 0 )
        {
            // The start index is lower than 0 => normalize it
            $indexStart = 0;
        }
        else if ( $indexStart >= $cnt )
        {
            // The start index is bigger than max => return unchanged
            return $array;
        }

        if ( null === $length )
        {
            // No length defined => use max length
            $length = $cnt - $indexStart;
        }
        else if ( $length < 0 )
        {
            // Negative Length
            $length = $cnt - $indexStart + $length;
        }

        \array_splice( $array, $indexStart, $length );

        return $array;

    }

    /**
     * Returns the depth of permitted array.
     *
     * @param  array $array The array to check
     * @return integer Returns the depth.
     */
    public static function GetMaxDepth( array $array ) : int
    {

        if ( \count( $array ) < 1 )
        {
            return 0;
        }

        $c = 1;
        foreach ( $array as $v )
        {
            if ( \is_array( $v ) )
            {
                $x = 1 + static::GetMaxDepth( $v );
                if ( $x > $c )
                {
                    $c = $x;
                }
            }
        }

        return $c;

    }

    /**
     * Returns if array has only a depth of 1 level.
     *
     * @param  array $array The array to check
     * @return bool
     */
    public static function IsSingleDepth( array $array ) : bool
    {

        if ( \count( $array ) < 1 )
        {
            return false;
        }

        foreach ( $array as $v )
        {
            if ( \is_array( $v ) && \count( $v ) > 0 )
            {
                return false;
            }
        }

        return true;

    }

    /**
     * Extracts all elements beginning at element with index $startIndex with defined length/count (or to the end)
     *
     * @param array    $array      The array
     * @param integer  $startIndex The index of the element where extraction starts
     * @param int|null $length     The optional lenght/count of required array elements (default=null mens to the end)
     * @return array
     */
    public static function Extract( array $array, int $startIndex, int $length = null ) : array
    {

        $count = \count( $array );

        if ( $count < 1 || $startIndex >= $count || 0 === $length )
        {
            return [];
        }

        if ( $startIndex === $count - 1 )
        {
            return [ $array[$startIndex] ];
        }

        if ( ! \is_int( $length ) )
        {
            $length = $count - $startIndex;
        }

        if ( $length < 0 )
        {
            $length = ( $count - $startIndex ) + $length;
        }

        if ( $length === $count )
        {
            return $array;
        }

        return \array_slice( $array, $startIndex, $length );

    }

    #endregion


}

