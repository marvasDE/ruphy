<?php

trait ObjectTrait {
    public function __call($method, $args) {
        if (isset($this->$method) && is_callable($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
    
    public function __get($name) {
        // method calling like ruby e.g. $a->pop
        if(method_exists(get_called_class(), $name)) {
            return $this->$name();
        }
    }
    
    public function __set(string $name, $value) {
        if(is_callable($value) && method_exists($this, $name)) {
            throw new InvalidArgumentException("Sorry, ruphy can not overwrite existing methods at this time.");
        } else {
            $this->$name = $value;
        }
    }
    
    public static function new() {
        return new self(...func_get_args());
    }
}
