<?php
namespace App\Events\PoDomain\Context;

use App\Models\Order;
use App\Models\SaleOrder;

class PoAcceptedContext {
    public $Po;
    public $So;
    public function __construct(Order $Po, SaleOrder $saleOrder)
    {
        $this->Po = $Po;
        $this->So = $saleOrder;
    }
}
