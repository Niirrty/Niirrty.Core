# Version 0.5.0 `2021-08-13`

* Switch code inside `\Niirrty\strStartsWith()`, `\Niirrty\strEndsWith()` and `\Niirrty\strContains()` for use of
  new PHP 8 functions `\str_starts_with()`, `\str_ends_with()` and `\str_contains()` and remove 4th parameter `charset`.
  The charset of the engine, is now used always. 
* **`Niirrty\IArrayable`**: The interface now declares a static method for parsing an array to a implementing instance.
* **`Niirrty\ArgumentException`**: Add a getter for error argument value.
* **`Niirrty\ArrayHelper`**: Add the `::ParseXmlAttributes(...)` method. It does the same like `::ParseAttributes()`
  but is named more precise.
* **`Niirrty\IChangeable`**: Add optional parameter `$itemName` to the `isChanged` method.
* **`Niirrty\IStringable`**: The interface now declares a static method for parsing a string to an implementing instance.
* **`Niirrty\generateGUID()`**: Add this function to create a unique GUID (36 chars)
* **`NIIRRTY_NO_ERROR_HANDLER`**: If this constant is defined, the Niirrty internal error handler is not registered.