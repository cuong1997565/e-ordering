<?php
namespace App\Events\SaleDomain\Context;

class SoClosedContext {
    public $So;

    public function __construct($So)
    {
        $this->So = $So;
    }
}
