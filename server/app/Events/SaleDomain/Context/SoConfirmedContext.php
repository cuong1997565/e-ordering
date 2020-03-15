<?php
namespace App\Events\SaleDomain\Context;

class SoConfirmedContext {
    public $So;

    public function __construct($So)
    {
        $this->So = $So;
    }
}
