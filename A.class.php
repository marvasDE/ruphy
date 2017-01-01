<?php

class A extends ArrayObject {
    /* +++++ Magic Methods +++++ */
    public function __get($name) {
        // method calling like ruby e.g. $a->pop
        if(method_exists("A", $name)) {
            return $this->$name();
        }
    }
    
    public function __construct(array $array = []) {
        foreach($array as &$value) {
            if(is_array($value)) {
                $value = new A($value);
            }
        }
        parent::__construct($array);
    }
    
    /* +++++ Overwritten methods form ArrayObject +++++  */
    public function &offsetGet($index) {
        // handle ranges e.g. $arr[1.3]
        if(is_float($index)) {
            $start = intval($index);
            $end = explode(".", $index)[1];
            $result = new A(array_slice((array) $this, $start, $end));
            return $result;
        }
        // handle (comma seperated) slicing indizies, e.g. $a["-3,3"]
        if(is_string($index) && preg_match("/^-?[0-9]+,[0-9]+$/", str_replace(" ", "", $index))) {
            $range = explode(",", $index);
            $start = $range[0];
            $end   = $range[1];
            $result = new A(array_slice((array) $this, $start, $end));
            return $result;
        }
        if(!$this->offsetExists($index)) {
            $this->offsetSet($index, new A());
        }

        $var = parent::offsetGet($index);
        return $var;
    }
    
    public function offsetSet($index, $value) { // : void
        if(is_array($value)) {
            $value = new A($value);
        }
        parent::offsetSet($index, $value);
    }
    public function offsetUnset($index) { // : void
        parent::offsetUnset($index);
        $this->exchangeArray(array_values((array) $this)); // reindex
    }
    
    /* +++++ Ruby Array Methods +++++ */
    public function at(int $index) { // : mixed
        return $this[$index];
    }
    public function delete($mixed) {
        while(($key = array_search($mixed, (array) $this)) !== false) {
            unset($this[$key]);
        }
        return $this;
    }
    public function delete_at(int $index) {
        $result = $this[$index];
        unset($this[$index]);
        return $result;
    }
    public function drop(int $offset) : A {
        return new A(array_slice((array) $this, $offset));
    }
    public function empty() : bool {
        return $this->count() === 0;
    }
    public function first() { // : mixed
        return $this[0];
    }
    public function index($mixed) { // : ?int
        $index = array_search($mixed, (array) $this);
        return $index !== false ? $index : null;
    }
    public function include($needle) : bool {
        return in_array($needle, (array) $this);
    }
    public function insert(int $index, $mixed) {
        $newArray = (array) $this;
        if(func_num_args() > 2) {
            $mixed = func_get_args();
            array_shift($mixed); // remove $index
        }
        array_splice($newArray, $index, 0, $mixed);
        $this->exchangeArray($newArray);
    }
    public function last() { // : mixed
        return end($this);
    }
    public function length() : int {
        return $this->count();
    }
    public function pop() { // : mixed
        $newArray = (array) $this;
        $result = array_pop($newArray);
        $this->exchangeArray($newArray);
        return $result;
    }
    public function push($mixed) { // : void
        $this[] = $mixed;
    }
    public function shift() { // : mixed
        $newArray = (array) $this;
        $result = array_shift($newArray);
        $this->exchangeArray($newArray);
        return $result;
    }
    public function take(int $length) : A {
        return new A(array_slice((array) $this, 0, $length));
    }
    public function unshift($mixed) {
        $newArray = (array) $this;
        array_unshift($newArray, $mixed);
        $this->exchangeArray($newArray);
    }
}
