<?php
require_once "A.class.php";

class Atest {
    private $testA;
    
    public function __construct() {
        $this->testA = new A([1,2,3,4,5,6]);
        
        foreach(get_class_methods("Atest") as $method) {
            if(substr($method, 0, 4) == "test") {
                echo $method . ":\n";
                $this->$method();
                echo "\n";
            }
        }
    }
    
    public function testConstruct() {
        var_dump($this->testA[0] === 1);
        var_dump($this->testA[1] === 2);
        var_dump($this->testA[2] === 3);
    }
    
    public function testAt() {
        var_dump($this->testA->at(0) === 1);
        var_dump($this->testA->at(1) === 2);
        var_dump($this->testA->at(2) === 3);
    }
    
    public function testFirst() {
        var_dump($this->testA->first() === 1);
    }
    
    public function testLast() {
        var_dump($this->testA->last() === 6);
    }
    
    public function testTake() {
        var_dump($this->testA->take(3) == new A([1,2,3]));
        var_dump((array) $this->testA->take(3) === [1,2,3]);
        // var_dump($this->testA->take(3));
    }
    
    public function testDrop() {
        // var_dump($this->testA->drop(3));
        var_dump($this->testA->drop(3) == new A([4,5,6]));
        var_dump((array) $this->testA->drop(3) === [4,5,6]);
    }
    
    public function testLength() {
        var_dump($this->testA->length() === 6);
    }
    
    public function testEmpty() {
        var_dump($this->testA->empty() === false);
        var_dump((new A())->empty() === true);
        // var_dump((new A())->empty === true);
    }
    
    public function testInclude() {
        var_dump($this->testA->include(1) === true);
        var_dump($this->testA->include(1337) === false);
    }
    
    public function testPush() {
        $this->testA->push(7);
        var_dump($this->testA->last() === 7);
    }
    
    public function testUnshift() {
        $this->testA->unshift(0);
        var_dump($this->testA->first() === 0);
        // var_dump($this);
    }
    
    public function testInsert() {
        $this->testA->insert(3, 'apple');
        var_dump((array) $this->testA == [0, 1, 2, 'apple', 3, 4, 5, 6, 7]);
        
        $this->testA = new A([0,1,2,3,4,5,6]);
        $this->testA->insert(3, 'orange', 'pear', 'grapefruit');
        var_dump((array) $this->testA == [0, 1, 2, 'orange', 'pear', 'grapefruit', 3, 4, 5, 6]);
    }
    
    public function testPop() {
        $this->testA = new A([1, 2, 3, 4, 5, 6]);
        var_dump($this->testA->pop() === 6);
        var_dump($this->testA->pop === 5);
        var_dump((array) $this->testA == [1, 2, 3, 4]);
    }
    
    public function testShift() {
        $this->testA = new A([1, 2, 3, 4, 5]);
        var_dump($this->testA->shift() === 1);
        var_dump((array) $this->testA == [2, 3, 4, 5]);
        // var_dump((array) $this->testA);
    }
    
    public function testDeleteAt() {
        var_dump($this->testA->delete_at(2) == 4);
        var_dump((array) $this->testA == [2, 3, 5]);
        // var_dump((array) $this->testA);
    }
    
    public function testDelete() {
        $this->testA = new A([1, 2, 2, 3]);
        var_dump((array) $this->testA->delete(2) == [1, 3]);
        // var_dump((array) $this->testA);
    }
    
    public function testIndex() {
        var_dump($this->testA->index(1) === 0);
        var_dump($this->testA->index("z") === null);
    }
    
    public function testPHPStuff() {
    //     $this->testA['first'] = "bla";
    //     var_dump($this->testA->first);
    //     var_dump($this->testA);
    
        foreach($this->testA as $testAValue) {
            echo $testAValue;
        }
    }
    
    public function testCommon() {
        $a1 = new A([1, 2, [1,2,3]]);
        var_dump($a1);
        
        $a = new A();
        // $a[0] = new A();
        $a[0]['cat'] = 'feline';
        // $a[1] = ["test"];
        var_dump($a);
    }
}

new Atest();
