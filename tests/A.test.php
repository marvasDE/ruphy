<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../src/A.class.php";

assert_options( ASSERT_CALLBACK, 'assert_callback');

function assert_callback($script, $line, $message) {
    echo 'You have a design error in your script <b>', $script,'</b> : line <b>', $line,'</b> :<br />';
    echo '<b>'.$message.'</b><br /><br />';
    echo 'Open the source file and check it, because it\'s not a normal behaviour !';
    exit;
}

class Atest {
    private $testA;
    
    public function __construct() {
        $this->testA = new A([1,2,3,4,5,6]);
        
        foreach(get_class_methods("Atest") as $method) {
            if(substr($method, 0, 4) == "test") {
                echo $method . ":<br />\n";
                $this->$method();
                echo "Keine Fehler<br />\n";
                echo "<br />\n";
            }
        }
    }
    
    public function testConstruct() {
        assert($this->testA[0] === 1);
        assert($this->testA[1] === 2);
        assert($this->testA[2] === 3);
    }
    
    public function testAt() {
        assert($this->testA->at(0) === 1);
        assert($this->testA->at(1) === 2);
        assert($this->testA->at(2) === 3);
    }
    
    public function testFirst() {
        assert($this->testA->first() === 1);
    }
    
    public function testLast() {
        assert($this->testA->last() === 6);
    }
    
    public function testTake() {
        assert($this->testA->take(3) == new A([1,2,3]));
        assert((array) $this->testA->take(3) === [1,2,3]);
        // assert($this->testA->take(3));
    }
    
    public function testDrop() {
        // assert($this->testA->drop(3));
        assert($this->testA->drop(3) == new A([4,5,6]));
        assert((array) $this->testA->drop(3) === [4,5,6]);
    }
    
    public function testLength() {
        assert($this->testA->length() === 6);
    }
    
    public function testEmpty() {
        assert($this->testA->empty() === false);
        assert((new A())->empty() === true);
        // assert((new A())->empty === true);
    }
    
    public function testInclude() {
        assert($this->testA->include(1) === true);
        assert($this->testA->include(1337) === false);
    }
    
    public function testPush() {
        $this->testA->push(7);
        assert($this->testA->last() === 7);
    }
    
    public function testUnshift() {
        $this->testA->unshift(0);
        assert($this->testA->first() === 0);
        // assert($this);
    }
    
    public function testInsert() {
        $this->testA->insert(3, 'apple');
        assert((array) $this->testA == [0, 1, 2, 'apple', 3, 4, 5, 6, 7]);
        
        $this->testA = new A([0,1,2,3,4,5,6]);
        $this->testA->insert(3, 'orange', 'pear', 'grapefruit');
        assert((array) $this->testA == [0, 1, 2, 'orange', 'pear', 'grapefruit', 3, 4, 5, 6]);
    }
    
    public function testPop() {
        $this->testA = new A([1, 2, 3, 4, 5, 6]);
        assert($this->testA->pop() === 6);
        assert($this->testA->pop === 5);
        assert((array) $this->testA == [1, 2, 3, 4]);
    }
    
    public function testShift() {
        $this->testA = new A([1, 2, 3, 4, 5]);
        assert($this->testA->shift() === 1);
        assert((array) $this->testA == [2, 3, 4, 5]);
        // assert((array) $this->testA);
    }
    
    public function testDeleteAt() {
        assert($this->testA->delete_at(2) == 4);
        assert((array) $this->testA == [2, 3, 5]);
        // assert((array) $this->testA);
    }
    
    public function testDelete() {
        $this->testA = new A([1, 2, 2, 3]);
        assert((array) $this->testA->delete(2) == [1, 3]);
        // assert((array) $this->testA);
    }
    
    public function testIndex() {
        assert($this->testA->index(1) === 0);
        assert($this->testA->index("z") === null);
    }
    
    public function testPHPStuff() {
        // isset works great:
        assert(isset($this->testA[1337]) === false);
        assert(isset($this->testA[1337]) === false);
        
        // but after getting, it's an A Object to handle multi dimensional arrays:
        $this->testA[1337];
        assert(isset($this->testA[1337]) === true);
        assert(get_class($this->testA[1337]) === "A");
    }
    
    public function testCommon() {
        $a1 = new A([1, 2, [1,2,3]]);
        // auto converting constructor:
        assert($a1 == new A([1, 2, new A([1,2,3])]));
        
        $a = new A();
        // multi dimension converting
        $a[0]['cat'] = 'feline';
        assert($a == new A([new A(['cat' => 'feline'])]));
        
        // convert by setting arrays
        $a[1] = ["test"];
        assert($a == new A([new A(['cat' => 'feline']), new A(["test"])]));
    }
    
    public function testRangeIndex() {
        $a = new A(["a", "b", "c", "d", "e"]);
        assert($a[1.3] == new A(["b", "c", "d"]));
        assert($a["1.3"] == new A(["b", "c", "d"]));
        assert($a["1..3"] == new A(["b", "c", "d"]));
        assert($a == new A(["a", "b", "c", "d", "e"]));
    }
    
    public function testSliceIndex() {
        $a = new A(["a", "b", "c", "d", "e"]);
        assert($a["2,3"] == new A(["c", "d", "e"]));
        assert($a["-3,3"] == new A(["c", "d", "e"]));
    }
    
    public function testStaticNew() {
        assert(A::new() == new A());
        assert(A::new(3) == new A([null, null, null]));
        assert(A::new(3, true) == new A([true, true, true]));
    }
    
    public function testStaticNewWithFunctions() {
        assert(A::new(3, function() { return A::new(); }) == new A([new A(), new A(), new A()]));
        assert(A::new(3, function() { return A::new(3); }) == new A([new A([null,null,null]), new A([null,null,null]), new A([null,null,null])]));
    }
}

new Atest();
