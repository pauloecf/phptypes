<?php
namespace Type;

class Arr extends \ArrayObject
{

    /**
     * Class constructor
     * 
     * @param array $input            
     * @return null
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get array
     * 
     * @return array
     */
    public function getArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * Set a new array value
     * 
     * @param array $input            
     * @return \Core\Type\Arr
     */
    public function setArray(array $input)
    {
        $this->exchangeArray($input);
        
        return $this;
    }

    /**
     * Advances array's internal pointer to the last element, and returns its value
     * 
     * @return mixed
     */
    public function end()
    {
        $arr = $this->getArrayCopy();
        
        return end($arr);
    }

    /**
     * Checks if a value exists in an array
     * 
     * @param mixed $needle            
     * @param boolean $strict            
     * @return boolean
     */
    public function hasValue($needle, $strict = false)
    {
        return in_array($needle, $this->getArrayCopy(), $strict);
    }

    /**
     * Determine whether an array is empty
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        $arr = $this->getArrayCopy();
        
        return empty($arr);
    }

    /**
     * Checks if the given key or index exists in the array
     * Returns TRUE if the given key is set in the array. 
     * key can be any value possible for an array index
     * 
     * @param mixed $key            
     * @return boolean
     */
    public function keyExists($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Applies the callback to the elements of the array
     * 
     * @param callable $callback            
     * @return \Core\Type\Arr
     */
    public function map(callable $callback)
    {
        $arr = array_map($callback, $this->getArrayCopy());
        
        $this->exchangeArray($arr);
        
        return $this;
    }

    /**
     * Applies recursively the callback to all nested elements of the array
     * 
     * @param callable $callback            
     * @return \Core\Type\Arr
     */
    public function mapRecursive(callable $callback)
    {
        $arr = $this->_recursive($this->getArrayCopy(), $callback);
        
        $this->exchangeArray($arr);
        
        return $this;
    }

    /**
     * Push one or more elements onto the end of array
     * 
     * @return \Core\Type\Arr
     */
    public function push()
    {
        $arr = $this->getArrayCopy();
        
        call_user_func_array('array_push', array_merge([
            &$arr
        ], func_get_args()));
        
        $this->exchangeArray($arr);
        
        return $this;
    }

    /**
     * Array map recursive method
     * 
     * @param array $arr            
     * @param callable $callback            
     * @return mixed
     */
    private function _recursive(array $arr, callable $callback)
    {
        if (! is_array($arr)) {
            return is_array($callback) ? call_user_func_array($callback, $arr) : $callback($arr);
        } else {
            // $new_arr = array();
            $new_arr = new self([]);
            
            foreach ($arr as $key => $value) {
                $new_arr[$key] = (is_array($value) ? $this->recursive($value, $callback) : (is_array($callback) ? call_user_func_array($callback, $value) : $callback($value)));
            }
            
            return $new_arr;
        }
    }

    /**
     * Join array elements with a string
     * 
     * @param string $glue            
     * @return \Core\Type\Strng
     */
    public function implode($glue)
    {
        $string = implode($glue, $this->getArrayCopy());
        
        return new \Core\Type\Strng($string);
    }

    /**
     * Shift an element off the beginning of array
     * 
     * @return mixed
     */
    public function shift()
    {
        $arr = $this->getArrayCopy();
        $arr_value = array_shift($arr);
        
        $this->exchangeArray($arr);
        
        return $arr_value;
    }

    /**
     * Prepend one or more elements to the beginning of an array
     * 
     * @return \Core\Type\Arr
     */
    public function unshift()
    {
        $arr = $this->getArrayCopy();
        
        call_user_func_array('array_unshift', array_merge([
            &$arr
        ], func_get_args()));
        
        $this->exchangeArray($arr);
        
        return $this;
    }

    /**
     * Call non existent methods implementated in class
     * In this case it will try to use PHP native Array Functions
     * array_chunk()        => \Type\Arr::chunk()
     * array_count_values() => \Type\Arr::countValues()
     * 
     * @param string $callback            
     * @param array $args            
     * @return String
     */
    public function __call($callback, array $args)
    {
        $callback = $this->_formatCallback($callback);
        $args = array_map(function ($arg)
        {
            return ($arg instanceof \Type\Arr) ? $arg->getArray() : $arg;
        }, $args);
        
        $arr = call_user_func_array($callback, array_merge([
            $this->getArrayCopy()
        ], $args));
        
        $this->exchangeArray($arr);
        
        return $this;
    }

    /**
     * Format callback function to a valid PHP native Multibyte String Function format
     * Added mb prefix and underscore separators
     * helloWorld => array_hello_world
     * keys       => array_keys
     * You can check all PHP native array functions
     * in http://www.php.net/manual/en/ref.array.php
     * 
     * @param string $callback            
     * @return string
     */
    private function _formatCallback($callback)
    {
        $pattern = '/([A-Z]{1,}[a-z]{1,}|[a-z]{1,})/';
        preg_match_all($pattern, $callback, $matches);
        
        $callback = implode('_', $matches['0']);
        
        return 'array_' . strtolower($callback);
    }
}
