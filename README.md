phptypes
========

PHP Custom Types

Once PHP doesn't have native core classes to deal with primitive types in a O.O. fashion and SPL types (just available 
in PECL) types lack in documentation and functionality, this project intends to create custom classes for integer, 
float, string, array and boolean types.

Main Features:

- PHP 5.4+.

- The array class is extended from the PHP ArrayObject native class.

- The array and string classes, whenever is possible, take advantatage of the method __call to reuse some native
functions like array_* or mb_*. They will be mapped like this:

mb_substr()          => $string->substr()

mb_substr_count()    => $string->substrCount()

array_chunk()        => $array->chunk()

array_count_values() => $array->countValues()

- The float class uses __call for math functions.

- The chained syntax (AKA fluid sytanx) is used as much as possible, an easier way to read and write:

<?php

$array = new \Type\Arr(['a', 'b', 'c']);

$array->map('strtoupper')->merge->(['d', 'e'])->getArray(); //output: ['A', 'B', 'C', 'd', 'e']

instead of:

$array = ['a', 'b', 'c'];

$array = array_merge(array_map('strtoupper', $array), ['d', 'e']);

?>

- Some methods return other custom types:

<?php

$array = new \Type\Arr(['a', 'b', 'c']);

$array->implode('-');  //returns  object Type\Strng;

//so you can do this:

$array->implode('-')->convertCase(MB_CASE_UPPER)->getString(); //returns 'A-B-C'

?>

- You can perform type casting to get the primitive types like this:

Class::getString(), Class::getInteger(), Class::getFloat, Class::getBooelan

<?php

$integer = new \Type\Intgr(1);

$integer->sum(6);

$integer->getInteger();  //will return integer 7

$integer->getString();  //will return string '7'

?>

- PSR-2 code format.

- New functionalities are being added to the classes :D. So we are in version 0.0.
