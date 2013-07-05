phptypes
========

PHP Custom Types

Once PHP doesn't have native core classes to deal with primitive types in a O.O. fashion and SPL types (just available 
in PECL) types lack in documentation and functionality, this project intends to create custom classes for integer, 
float, string, array and boolean types.

Main Features:

- The array class is extended from a PHP ArrayObject class.

- The array and string classes, whenever is possible, take advantatage of the method __call to reuse some native
functions like array_* ou mb_*.

- The chained syntax (or fluid sytanx) are used as much as possible, they are easier to read and code:

<?php
$array = new \Type\Arr(['a', 'b', 'c']);

$array->map('strtoupper')->merge->(['d', 'e'])->getArray() //output: ['A', 'B', 'C', 'd', 'e']
?>

- For better use methods will return other custom types:

<?php

$array = new \Type\Arr(['a', 'b', 'c']);

$array->implode('-');  //returns  object Type\Strng;

//so you can do this:

$array->implode('-')->converCase(MB_CASE_UPPER)->getString(); //returns A-B-C

?>

- You can perform type casting to get the primitive types with methods like:

Class::getString(), Class::getInteger(), Class::getFloat, Cast::getBooelan
