<?php

require_once "../src/A.class.php";

class Car {
    use ObjectTrait;
    
    public function inspect() {
        return "billig";
    }
}

echo "\n";

$porsche = Car::new();
echo $porsche->inspect . "\n";  #-> billig

$porsche->inspect2 = function() {
  return "teuer";
};

echo $porsche->inspect2();  #-> teuer
echo "\n";

// you can not overwrite existing methods in PHP :(
try {
    $porsche->inspect = function() { # throws InvalidArgumentException because inspect already exists
      return "teuer";
    };
    echo $porsche->inspect();
} catch(InvalidArgumentException $e) {
    echo $e->getMessage() . "\n";
}
