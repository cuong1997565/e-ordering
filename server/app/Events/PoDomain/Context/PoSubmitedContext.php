<?php
namespace App\Events\PoDomain\Context;

class PoSubmitedContext {
    public $Po;

    public function __construct($Po)
    {
        $this->Po = $Po;
    }
}
