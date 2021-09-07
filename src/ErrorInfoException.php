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
 * This is the Niirrty {@see \Niirrty\IErrorInfo} depending base exception.
 *
 * @since v0.1.0
 */
class ErrorInfoException extends NiirrtyException
{


    #region // = = = =   P U B L I C   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = =

    /**
     * Init a new ErrorInfoException instance.
     *
     * @param IErrorInfo      $errorInfo The Object that implements the IErrorInfo interface.
     * @param string|null     $message   The error message.
     * @param int             $code      The optional error code.
     * @param \Throwable|null $previous  Optional previous throwable.
     */
    public function __construct (
        protected IErrorInfo $errorInfo, ?string $message = null, $code = 0, ?\Throwable $previous = null )
    {

        parent::__construct(
            ( ! empty( $message ) ? ( $message . ' - ' ) : '' ) . $errorInfo->getErrorInfoString(),
            $code,
            $previous
        );

    }

    #endregion


    #region // = = = =   P U B L I C   M E T H O D S   = = = = = = = = = = = = = = = = = = = = = = = = =

    /**
     * @return IErrorInfo
     */
    public function getErrorInfo() : IErrorInfo
    {

        return $this->errorInfo;

    }

    #endregion


}

