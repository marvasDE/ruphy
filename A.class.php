<?php

class A extends ArrayObject {
    public function __get($name) {
        // method calling like ruby e.g. $a->pop
        if(method_exists("A", $name)) {
            return $this->$name();
        }
    }
    
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
    public function index($mixed) {
        $pos = array_search($this, $mixed);
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
    /* @override */
    public function offsetUnset($index) { // : void
        parent::offsetUnset($index);
        $this->exchangeArray(array_values((array) $this)); // reindex
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
