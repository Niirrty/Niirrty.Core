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


use Niirrty\ArgumentException;
use Niirrty\IStringable;
use Niirrty\TypeTool;


class Stringable implements IStringable
{


    private int $_value;


    public function __construct( int $value )
    {

        $this->_value = $value;

    }


    /**
     * Gets the string representation of the instance data for implementing class.
     *
     * @return string
     */
    public function __toString()
    {

        return (string) $this->_value;

    }

    public static function FromString( string $str, bool $throwOnError = false ): bool|static
    {
        if ( ! TypeTool::TryParseInteger( $str, $intVal ) )
        {
            if ( $throwOnError ) {
                throw new ArgumentException( 'str', $str, 'Something is wrong with the string value' );
            }
            return false;
        }
        return new Stringable( $intVal );
    }


}

