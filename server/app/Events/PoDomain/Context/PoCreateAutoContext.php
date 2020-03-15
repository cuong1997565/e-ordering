<?php
namespace App\Events\PoDomain\Context;

class PoCreateAutoContext {
    public $newsale = [];
    public $orderItem = [];
    // Example payload:
    /**
     * Example payload:
     *
     *    ]
     */
    public function __construct($newsale , $orderItem)
    {
        $this->newsale = $newsale;
        $this->orderItem = $orderItem;
    }
}
