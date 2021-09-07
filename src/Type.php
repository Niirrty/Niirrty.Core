<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.5.0
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * This class defines a object for getting and holding information about a value.
 */
class Type
{


    #region // – – –   P R I V A T E   F I E L D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Defines if the value is convertible to a string.
     *
     * @var boolean
     */
    private bool $isStringConvertible;

    /**
     * The string value associate with the base value.
     *
     * @var string|null
     */
    private ?string $stringValue;

    /**
     * The name of the base value type.
     *
     * @var string
     */
    private string $typeName;

    #endregion


    #region // – – –   C L A S S   C O N S T A N T S   – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * The PHP type of float values.
     */
    public const PHP_FLOAT  = 'float';

    /**
     * The PHP type of integer values.
     */
    public const PHP_INTEGER = 'int';

    /**
     * The PHP type of boolean values.
     */
    public const PHP_BOOLEAN = 'bool';

    /**
     * The PHP type of string values.
     */
    public const PHP_STRING = 'string';

    /**
     * The PHP type of NULL values.
     */
    public const PHP_NULL = 'null';

    /**
     * The PHP type of resource values.
     */
    public const PHP_RESOURCE = 'resource';

    /**
     * The PHP type of array values.
     */
    public const PHP_ARRAY = 'array';

    /**
     * All other unknown types
     */
    public const PHP_UNKNOWN = 'unknown';

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Init a new instance.
     *
     * @param  mixed $value The base value.
     * @throws NiirrtyException If $value is of type {@see Type}
     */
    public function __construct( private mixed $value )
    {

        $this->isStringConvertible = TypeTool::IsStringConvertible( $value, $this->stringValue );

        if ( ! $this->isStringConvertible )
        {
            $this->stringValue = null;
        }

        $this->typeName = Type::PHP_UNKNOWN;

        if ( null === $value )
        {
            $this->typeName = Type::PHP_NULL;
        }
        else if ( \is_string( $value ) )
        {
            $this->typeName = Type::PHP_STRING;
        }
        else if ( \is_array( $value ) )
        {
            $this->typeName = Type::PHP_ARRAY;
        }
        else if ( \is_object( $value ) )
        {
            if ( $value instanceof Type )
            {
                throw new NiirrtyException( 'Could not create \\Niirrty\\Type-Instance of a \\Niirrty\\Type-Value!' );
            }
            $this->typeName = \get_class( $value );
        }
        else if ( \is_int( $value ) )
        {
            $this->typeName = Type::PHP_INTEGER;
        }
        else if ( \is_float( $value ) )
        {
            $this->typeName = Type::PHP_FLOAT;
        }
        else if ( \is_bool( $value ) )
        {
            $this->typeName = Type::PHP_BOOLEAN;
        }
        else if ( \is_resource( $value ) )
        {
            $this->typeName = Type::PHP_RESOURCE;
        }

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Checks if the current instance is equal to defined value. If strict is FALSE only the value is checked.
     * Otherwise also the type is checked.
     *
     * @param mixed $value The other value.
     * @param boolean $strict Check strictly also the type
     * @return boolean         TRUE oder FALSE
     * @throws NiirrtyException
     */
    public function equals( mixed $value, bool $strict = false ) : bool
    {

        if ( ! ( $value instanceof Type ) )
        {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $value = new Type( $value );
        }

        $res = ( $value->typeName === $this->typeName );

        if ( ! $res && $strict )
        {
            return false;
        }

        if ( $value->isStringConvertible )
        {
            return ( $value->stringValue === $this->stringValue );
        }

        return ( $value->value === $this->value );

    }

    /**
     * Returns the base value.
     *
     * @return mixed
     */
    public final function getValue(): mixed
    {

        return $this->value;

    }

    /**
     * Returns the associated string value, if the base value is convertible to a string.
     * Otherwise the defined default value is returned.
     *
     * @param  string $default THis will be returned if the base value is not string convertible (default='')
     * @return string
     */
    public final function getStringValue( string $default = '' ) : string
    {

        if ( ! $this->isStringConvertible )
        {
            return $default;
        }

        return $this->stringValue;

    }

    /**
     * Returns if the base value can be converted to a string.
     *
     * @return boolean
     */
    public final function hasAssociatedString() : bool
    {

        return $this->isStringConvertible;

    }

    /**
     * Returns the type name of the base value.
     *
     * @return string
     */
    public final function getType() : string
    {

        return $this->typeName;

    }

    /**
     * Returns if the base value is a PHP resource type.
     *
     * @return boolean
     */
    public final function isResource() : bool
    {

        return $this->typeName === Type::PHP_RESOURCE;

    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString() : string
    {

        return ( $this->stringValue ?? '' );

    }

    /**
     * Returns the PHP code, defining the current base value.
     *
     * @return string
     */
    public final function getPhpCode() : string
    {

        $str = $this->stringValue;

        switch ( $this->typeName )
        {

            case Type::PHP_BOOLEAN:
            case 'boolean':
                return ( $this->value ? 'true' : 'false' );

            case Type::PHP_FLOAT:
            case Type::PHP_INTEGER:
            case 'integer':
            case 'double':
                return $str;

            case Type::PHP_STRING:
                if ( \preg_match( "~[\r\n\t]+~", $str ) )
                {
                    $str = \str_replace(
                        array( '\\',  "\r", "\n", "\t", "\0", '"',  '$' ),
                        array( '\\\\','\\r','\\n','\\t','\\0','\\"','\\$' ),
                        $str
                    );
                    return '"' . $str . '"';
                }
                return "'" . \str_replace(
                    array( '\\',   "'" ),
                    array( '\\\\', "\\'" ),
                    $str
                )
                . "'";

            case Type::PHP_RESOURCE:
            case Type::PHP_NULL:
            case Type::PHP_UNKNOWN:
                return 'null';

            default:
                $str = \serialize( $this->value );
                if ( \preg_match( "~[\r\n\t]+~", $str ) )
                {
                    $str = \str_replace(
                        array( '\\',  "\r", "\n", "\t", "\0", '"',  '$' ),
                        array( '\\\\','\\r','\\n','\\t','\\0','\\"','\\$' ),
                        $str
                    );
                }
                else
                {
                    $str = \str_replace(
                        array('\\',   '"',   '$'),
                        array('\\\\', '\\"', '\\$'),
                        $str );
                }
                return '\unserialize("' . $str . '")';

        }

    }

    /**
     * Makes the Type instance cloneable.
     */
    public function __clone()
    {

        switch ( $this->typeName )
        {
            case Type::PHP_BOOLEAN:
            case Type::PHP_FLOAT:
            case Type::PHP_INTEGER:
            case Type::PHP_NULL:
            case Type::PHP_RESOURCE:
            case Type::PHP_STRING:
                break;
            default:
                $this->value = clone $this->value;
                break;
        }

    }

    #endregion


}

