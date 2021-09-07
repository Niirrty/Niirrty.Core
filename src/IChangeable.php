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
 * A class that implements this interface, must give a status if something at the instance data is changed
 *
 * @package Niirrty
 */
interface IChangeable
{


    /**
     * Gets if something has changed.
     *
     * @param string|null $itemName If defined it is checked, if the property, or something else with the defined item
     *                              name is changed. THe meaning of the identifier is controlled by the
     *                              implementing class. If no item name is defined, all changable things must be checked
     * @return bool
     */
    public function isChanged( ?string $itemName = null ) : bool;

}

