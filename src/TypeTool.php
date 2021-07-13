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
 *  This is a static helper class, to better handle some PHP type juggling.
 */
final class TypeTool
{


    #region // – – –   P R I V A T E   S T A T I C   F I E L D S   – – – – – – – – – – – – – – – – –

    /**
     * This is the regular expression to check if a string can be used as integer value.
     *
     * @var string
     */
    private static string $rxInt32 = '~^-?(0|[1-9]\d{0,11})$~';

    /**
     * This is the regular expression to check if a string can be used as big integer or long value.
     *
     * @var string
     */
    private static string $rxInt64 = '~^-?(0|[1-9]\d{0,19})$~';

    /**
     * This is the regular expression to check if a string can be used as double precission value,
     * with a dot as decimal separator.
     *
     * @var string
     */
    private static string $rxD1 = '~^-?((0|[1-9]\d{0,20})?\.\d{0,14}|\.\d{0,14})$~';

    /**
     * This is the regular expression to check if a string can be used as decimal value,
     * with a dot or comma as decimal separator.
     *
     * @var string
     */
    private static string $D = '~^-?((0|[1-9]\d{0,20})?(\.|,)\d{0,14}|(\.|,)\d{1,14})$~';

    /**
     * This is the regular expression to check if a string can be used a boolean TRUE value.
     *
     * @var string
     */
    private static string $rxBoolTRUE = '~^([1-9]\d*|t(rue)?|on|yes|ok|enabled|disabled|readonly|autocomplete|autofill|selected)$~i';

    /**
     * This is the regular expression to check if a string can be used a boolean FALSE value.
     *
     * @var string
     */
    private static string $rxBoolFALSE = '~^(0|-[1-9]\d*|f(alse)|off|no|out)$~i';

    #endregion


    #region // – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –

    /**
     * Returns if the defined value is usable as integer value.
     *
     * @param  mixed $value The value to check
     * @return boolean
     */
    public static function IsInteger( mixed $value ) : bool
    {

        if ( \is_int( $value ) || \is_float( $value ) )
        {
            return true;
        }

        if ( ! TypeTool::IsStringConvertible( $value, $strValue ) )
        {
            return false;
        }

        return (bool) \preg_match( TypeTool::$rxInt32, $strValue );

    }

    /**
     * Tries to parse the defined value as an integer. On success the method returns TRUE an the integer value is
     * returned by parameter $intValueOut
     *
     * @param mixed    $value       The value
     * @param int|null $intValueOut Returns the integer value if the method returns TRUE.
     * @return bool
     */
    public static function TryParseInteger( mixed $value, ?int &$intValueOut = 0 ) : bool
    {

        if ( \is_int( $value ) )
        {
            $intValueOut = $value;
            return true;
        }
        if ( \is_float( $value ) )
        {
            if ( $value > \PHP_INT_MAX || $value < \PHP_INT_MIN )
            {
                return false;
            }
            $intValueOut = (int) $value;
            return true;
        }
        if ( \is_bool( $value ) )
        {
            $intValueOut = $value ? 1 : 0;
            return true;
        }
        if ( ! TypeTool::IsStringConvertible( $value, $strVal ) )
        {
            return false;
        }

        if ( \preg_match( TypeTool::$rxInt32, $strVal ) )
        {
            $intValueOut = (int) $strVal;
            return true;
        }

        return false;

    }

    /**
     * Returns if the defined value is usable as a decimal value.
     *
     * @param  mixed   $value            The value to check.
     * @param  boolean $cultureInvariant If TRUE, also the comma can be used as decimal separator.
     * @return boolean
     */
    public static function IsDecimal( mixed $value, bool $cultureInvariant = false ) : bool
    {

        if ( \is_float( $value ) || \is_int( $value ) || \preg_match( TypeTool::$rxInt64, (string) $value ) )
        {
            return true;
        }

        if ( ! $cultureInvariant )
        {
            return (bool) \preg_match( TypeTool::$rxD1, (string) $value );
        }

        return (bool) \preg_match( TypeTool::$D, (string) $value );

    }

    /**
     * Tries to parse the defined value as an float. On success the method returns TRUE an the float value is
     * returned by parameter $floatValueOut
     *
     * @param mixed      $value            The value
     * @param float|null $floatValueOut    Returns the float value if the method returns TRUE.
     * @param bool       $cultureInvariant If TRUE, also the comma can be used as decimal separator.
     * @return bool
     */
    public static function TryParseFloat( mixed $value, ?float &$floatValueOut = 0, bool $cultureInvariant = false ) : bool
    {

        if ( \is_float( $value ) )
        {
            $floatValueOut = $value;
            return true;
        }
        if ( \is_int( $value ) )
        {
            $floatValueOut = (float) $value;
            return true;
        }
        if ( \is_bool( $value ) )
        {
            $floatValueOut = (float) ( $value ? 1 : 0 );
            return true;
        }
        if ( ! TypeTool::IsStringConvertible( $value, $strVal ) )
        {
            return false;
        }
        if ( \preg_match( TypeTool::$rxInt64, $strVal ) )
        {
            $floatValueOut = (float) $strVal;
            return true;
        }
        if ( ! $cultureInvariant )
        {
            if ( \preg_match( TypeTool::$rxD1, $strVal ) )
            {
                $floatValueOut = (float) $strVal;
                return true;
            }
            return false;
        }

        if ( \preg_match( TypeTool::$D, $strVal ) )
        {
            $floatValueOut = (float) \str_replace( ',', '.', $strVal );
            return true;
        }

        return false;

    }

    /**
     * Returns if the defined value is usable as a boolean value. If so, $resultingBoolValue returns the resulting
     * boolean value.
     *
     * @param mixed     $value              The value to check.
     * @param bool|null $resultingBoolValue Returns the resulting boolean value, if the method returns TRUE
     * @return bool
     */
    public static function IsBoolConvertible( mixed $value, ?bool &$resultingBoolValue = false ) : bool
    {

        if ( null === $value )
        {
            $resultingBoolValue = false;
            return false;
        }

        if ( \is_bool( $value ) )
        {
            $resultingBoolValue = $value;
            return true;
        }

        if ( \is_resource( $value ) )
        {
            $resultingBoolValue = true;
            return true;
        }

        if ( \is_object( $value ) )
        {
            if ( ! TypeTool::IsStringConvertible( $value, $strVal ) )
            {
                $resultingBoolValue = false;
                return false;
            }
            $value = $strVal;
        }

        if ( \is_array( $value ) )
        {
            $resultingBoolValue = \count( $value ) > 0;
            return true;
        }

        if ( TypeTool::IsInteger( $value ) )
        {
            $resultingBoolValue = ( ( (int) $value ) > 0 );
            return true;
        }

        if ( TypeTool::IsDecimal( $value ) )
        {
            $resultingBoolValue = ( ( (float) $value ) > 0 );
            return true;
        }

        if ( \preg_match( TypeTool::$rxBoolTRUE, $value ) )
        {
            $resultingBoolValue = true;
            return true;
        }

        if ( \preg_match( TypeTool::$rxBoolFALSE, $value ) )
        {
            $resultingBoolValue = false;
            return true;
        }

        if ( \preg_match( '~^b:[01];$~', $value ) )
        {
            // A serialized boolean value
            $resultingBoolValue = \unserialize( $value );
            return true;
        }

        $resultingBoolValue = false;

        if ( '' === $value )
        {
            return true;
        }

        return false;

    }

    /**
     * Returns if the defined value is usable as a string value. If so, $resultingString returns the resulting
     * string value.
     *
     * @param  mixed       $value           The value to check.
     * @param  string|null $resultingString Returns the resulting string value, if the method returns TRUE
     * @return boolean
     */
    public static function IsStringConvertible( mixed $value, ?string &$resultingString = null ) : bool
    {

        if ( null === $value )
        {
            $resultingString = '';
            return true;
        }

        if ( \is_string( $value ) )
        {
            $resultingString = $value;
            return true;
        }

        if ( \is_bool( $value ) )
        {
            $resultingString = $value ? 'true' : 'false';
            return true;
        }

        if ( \is_resource( $value ) )
        {
            $resultingString = '';
            return false;
        }

        if ( \is_object( $value ) )
        {
            if ( \method_exists( $value, '__toString' ) )
            {
                $resultingString = (string) $value;
                return true;
            }
            if ( $value instanceof \DateTimeInterface )
            {
                $resultingString = $value->format( 'Y-m-d H:i:s' );
                return true;
            }
            $resultingString = '';
            return false;
        }

        if ( \is_array( $value ) )
        {
            $resultingString = \serialize( $value );
            return true;
        }

        if ( \is_int( $value ) )
        {
            $resultingString = (string) $value;
            return true;
        }

        if ( TypeTool::IsDecimal( $value ) )
        {
            $resultingString = (string) $value;
            return true;
        }

        $resultingString = '';
        return false;

    }

    /**
     * Converts a string to defined native PHP type.
     *
     * @param  string $string   The string to convert
     * @param string  $typename The name of the required resulting PHP type.
     *         Allowed types are (bool|boolean|double|float|int|integer|string|array)
     *
     * @return mixed
     */
    public static function StrToType( string $string, string $typename ): mixed
    {

        switch ( \strtolower( $typename ) )
        {

            case 'bool':
            case 'boolean':
                $res = false;
                TypeTool::IsBoolConvertible( $string, $res );
                return $res;

            case 'float':
                if ( ! TypeTool::IsDecimal( $string, true ) )
                {
                    if ( '' === $string )
                    {
                        return 0;
                    }
                    return null;
                }
                $res = \str_replace( ',', '.', $string );
                return (float) $res;

            case 'int':
            case 'integer':
                if ( TypeTool::IsInteger( $string ) || TypeTool::IsDecimal( $string, true ) )
                {
                    return (int) $string;
                }
                if ( '' === $string )
                {
                    return 0;
                }
                return null;

            case 'string':
                return $string;

            case 'array':
                if ( '' === $string )
                {
                    return [];
                }
                if ( \strlen( $string ) > 3 )
                {
                    if ( \str_starts_with( $string, 'a:' ) )
                    {
                        try
                        {
                            $res = \unserialize( $string );
                            if ( \is_array( $res ) ) { return $res; }
                            else { throw new \Exception(); }
                        }
                        catch ( \Throwable ) { }
                    }
                    if ( \str_starts_with( $string, '[' ) && \str_ends_with( $string, ']' ))
                    {
                        $array = \json_decode( $string, true );
                        if ( ! \is_array( $array ) )
                        {
                            return [ $string ];
                        }
                        return $array;
                    }
                    else if ( \str_starts_with( $string, '{' ) && \str_ends_with( $string, '}' ) )
                    {
                        $array = \json_decode( $string, true );
                        if ( ! \is_array( $array ) )
                        {
                            return [ $string ];
                        }
                        return $array;
                    }
                }
                return [ $string ];

            default:
                if ( '' === $string )
                {
                    return null;
                }
                if ( \strlen( $string ) > 3 )
                {
                    if ( \str_starts_with( $string, 'O:' ) && \preg_match( '~^O:[^"]+"' . \preg_quote( $typename ) . '":~', $string ) )
                    {
                        $res = \unserialize( $string );
                        if ( ! \is_object( $res ) ) { return null; }
                        if ( \get_class( $res ) === $typename )
                        {
                            return $res;
                        }
                    }
                }
                return null;

        }

    }

    /**
     * Extracts typed data from defined XML element.
     *
     * For it, the XML element must define the data of a single value.
     *
     * The type name can be defined by 'type' attribute or by the
     * &lt;Type&gt;Typename&lt;/Type&gt; element.
     *
     * The type value can be defined as string by 'value' attribute or by the
     * &lt;Value&gt;A value&lt;/Value&gt; element, or by the string value defined
     * inside the XML element it self. (&lt;XmlElement type="..."&gt;A Value&lt;/XmlElement&gt;)
     *
     * @param  \SimpleXMLElement $xmlElement The XML element defining the typed value.
     * @return mixed The typed value, or NULL if no usable data are defined
     */
    public static function XmlToType( \SimpleXMLElement $xmlElement ): mixed
    {

        if ( null !== ( $type = XmlAttributeHelper::GetAttributeValue( $xmlElement, 'type' ) ) )
        {
            $type = (string) $type;
        }
        else if ( isset( $xmlElement->type ) )
        {
            $type = (string) $xmlElement->type;
        }
        else if ( isset( $xmlElement->Type ) )
        {
            $type = (string) $xmlElement->Type;
        }
        else
        {
            return null;
        }

        if ( null !== ( $value = XmlAttributeHelper::GetAttributeValue( $xmlElement, 'value' ) ) )
        {
            $value = (string) $value;
        }
        else if ( isset( $xmlElement->value ) )
        {
            $value = (string) $xmlElement->value;
        }
        else if ( isset( $xmlElement->Value ) )
        {
            $value = (string) $xmlElement->Value;
        }
        else
        {
            $value = (string) $xmlElement;
        }

        return TypeTool::StrToType( $value, $type );

    }

    /**
     * Returns if the type of defined value is a native PHP type.
     *
     * Native types are: boolean, integer, double, float, string, array
     *
     * @param  mixed $value THe value to check.
     * @return boolean
     */
    public static function IsNativeType( mixed $value ) : bool
    {

        return (
            \is_bool(   $value ) ||
            \is_int(    $value ) ||
            \is_string( $value ) ||
            \is_float(  $value ) ||
            \is_array(  $value )
        );

    }

    /**
     * Returns the native type name of the defined value.
     *
     * @param  mixed $value The value.
     * @return bool|string Returns the name of the type (see \Messier\Type::PHP_* constants) or boolean FALSE if the value has not native type
     */
    public static function GetNativeType( mixed $value ): bool|string
    {

        if ( \is_string( $value ) )
        {
            return Type::PHP_STRING;
        }

        if ( \is_int( $value ) )
        {
            return Type::PHP_INTEGER;
        }

        if ( \is_bool( $value ) )
        {
            return Type::PHP_BOOLEAN;
        }

        if ( \is_float( $value ) )
        {
            return Type::PHP_FLOAT;
        }

        if ( \is_array( $value ) )
        {
            return Type::PHP_ARRAY;
        }

        return false;

    }

    /**
     * Returns the type name of the defined value.
     *
     * @param  mixed $value The value.
     * @return string
     */
    public static function GetTypeName( mixed $value ) : string
    {

        if ( null === $value )
        {
            return Type::PHP_NULL;
        }

        if ( \is_object( $value ) )
        {
            return \get_class( $value );
        }

        if ( \is_string( $value ) )
        {
            return Type::PHP_STRING;
        }

        if ( \is_int( $value ) )
        {
            return Type::PHP_INTEGER;
        }

        if ( \is_bool( $value ) )
        {
            return Type::PHP_BOOLEAN;
        }

        if ( \is_float( $value ) )
        {
            return Type::PHP_FLOAT;
        }

        if ( \is_resource( $value ) )
        {
            return Type::PHP_RESOURCE;
        }

        if ( \is_array( $value ) )
        {
            return Type::PHP_ARRAY;
        }

        return Type::PHP_UNKNOWN;

    }

    /**
     * Converts the value! It must be a native PHP type (bool, int, float, double, string) to a other native PHP type.
     *
     * @param  mixed  $sourceValue The value to convert
     * @param  string $newType Native PHP type as target type. (See \Niirrty\Type::PHP_* constants)
     * @return mixed
     * @throws ArgumentException
     */
    public static function ConvertNative( mixed $sourceValue, string $newType ): mixed
    {

        if ( null === $sourceValue )
        {
            return null;
        }

        if ( false === ( $sourceType = TypeTool::GetNativeType( $sourceValue ) ) )
        {
            throw new ArgumentException(
                'sourceValue',
                $sourceValue,
                'Can not convert a value of a type that is not a native PHP type! (bool, int, double, float, string)'
            );
        }

        if ( $sourceType === $newType )
        {
            return $sourceValue;
        }

        switch ( $sourceType )
        {

            case Type::PHP_BOOLEAN:
            case 'boolean':
                switch ( $newType )
                {
                    case Type::PHP_FLOAT:
                        return (float) ( $sourceValue ? 1 : 0 );
                    case Type::PHP_INTEGER:
                        return ( $sourceValue ? 1 : 0 );
                    case Type::PHP_STRING:
                        return ( $sourceValue ? '1' : '0' );
                }
                break;

            case Type::PHP_FLOAT:
                switch ( $newType )
                {
                    case Type::PHP_INTEGER:
                        return (int) $sourceValue;
                    case Type::PHP_STRING:
                        return '' . $sourceValue;
                    case Type::PHP_BOOLEAN:
                        return $sourceValue > 0;
                }
                break;

            case Type::PHP_INTEGER:
                switch ( $newType )
                {
                    case Type::PHP_FLOAT:
                        return (int) $sourceValue;
                    case Type::PHP_STRING:
                        return '' . $sourceValue;
                    case Type::PHP_BOOLEAN:
                        return $sourceValue > 0;
                }
                break;

            case Type::PHP_STRING:
                switch ( $newType )
                {
                    case Type::PHP_INTEGER:
                    case Type::PHP_BOOLEAN:
                    case 'boolean':
                    case Type::PHP_FLOAT:
                        return TypeTool::StrToType($sourceValue, $newType);
                }
                break;

        }

        throw new ArgumentException(
            'newType',
            $newType,
            'Can not convert a value of a type that is not a native PHP type! (bool, int, double, float, string)'
        );

    }

    #endregion


}

