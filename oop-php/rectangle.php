<?php
class Rectangle {
    private $width;
    private $height;

    public function __construct($width = 1, $height = 1) {
        $this->width = $width;
        $this->height = $height;
    }

    public function getArea() {
        return $this->width * $this->height;
    }

    public function getPerimeter() {
        return 2 * ($this->width + $this->height);
    }

    public function display() {
        echo "Rectangle width: {$this->width}, height: {$this->height}<br>";
        echo "Area: " . $this->getArea() . "<br>";
        echo "Perimeter: " . $this->getPerimeter() . "<br>";
    }
}

$rect1 = new Rectangle(); 
$rect1->display();

echo "<br>";

$rect2 = new Rectangle(7, 3); 
$rect2->display();
?>