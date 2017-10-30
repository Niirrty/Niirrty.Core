<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Niirrty
 * @package        Niirrty
 * @since          2017-10-30
 * @version        0.1.0
 */


declare( strict_types = 1 );


namespace Niirrty;


/**
 * This class "XmlAttributeHelper" defines a object that have some static methods for handling XML attributes
 */
class XmlAttributeHelper
{


   // <editor-fold desc="// – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –">

   /**
    * Returns the XML attribute value of the defined XML element, or $defaultValue if the attribute not exists.
    *
    * @param  \SimpleXMLElement $element       The XML element
    * @param  string            $attributeName The name of the required XML attribute
    * @param  string|null       $defaultValue  The default value, returned if the attribute not exists.
    * @return string|null
    */
   public static function GetAttributeValue(
      \SimpleXMLElement $element, string $attributeName, ?string $defaultValue = null ) : ?string
   {

      if ( isset( $element[ $attributeName ] ) )
      {
         return (string) $element[ $attributeName ];
      }

      if ( isset( $element[ 'attributes' ][ $attributeName ] ) )
      {
         return (string) $element[ 'attributes' ][ $attributeName ];
      }

      if ( isset( $element[ '@attributes' ][ $attributeName ] ) )
      {
         return (string) $element[ '@attributes' ][ $attributeName ];
      }

      $attr = $element->attributes();
      if ( isset( $attr[ $attributeName ] ) )
      {
         return (string) $attr[ $attributeName ];
      }

      return $defaultValue;

   }

   // </editor-fold>


}

