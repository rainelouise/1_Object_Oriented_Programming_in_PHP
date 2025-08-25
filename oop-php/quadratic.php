<?php
class QuadraticEquation {
    private float $a;
    private float $b;
    private float $c;

    public function __construct(float $a, float $b, float $c) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function getA(): float {
        return $this->a;
    }

    public function getB(): float {
        return $this->b;
    }

    public function getC(): float {
        return $this->c;
    }

    public function getDiscriminant(): float {
        return ($this->b * $this->b) - (4 * $this->a * $this->c);
    }

    public function getRoot1(): ?float {
        $d = $this->getDiscriminant();
        if ($d < 0) {
            return null;
        }
        return (-$this->b + sqrt($d)) / (2 * $this->a);
    }

    public function getRoot2(): ?float {
        $d = $this->getDiscriminant();
        if ($d < 0) {
            return null; 
        }
        return (-$this->b - sqrt($d)) / (2 * $this->a);
    }
}

$eq = new QuadraticEquation(2, -6, 4); 

echo "a = " . $eq->getA() . "<br>";
echo "b = " . $eq->getB() . "<br>";
echo "c = " . $eq->getC() . "<br>";
echo "Discriminant = " . $eq->getDiscriminant() . "<br>";

$root1 = $eq->getRoot1();
$root2 = $eq->getRoot2();

if ($root1 === null || $root2 === null) {
    echo "The equation has no real roots.";
} else {
    echo "Root 1 = $root1 <br>";
    echo "Root 2 = $root2 <br>";
}
?>