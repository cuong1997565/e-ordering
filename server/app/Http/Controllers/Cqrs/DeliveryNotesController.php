<?php
namespace App\Http\Controllers\Cqrs;

use App\App\AuthorizationRepository;
use App\Dashboard\ReadModel\IDashboardDao;
use App\Http\Context\Context;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNotesController extends Controller {
    public $dao;
    public function __construct(Request $request, Context $context, AuthorizationRepository $authorization, IDashboardDao $dao)
    {
        parent::__construct($request, $context, $authorization);
        $this->dao = $dao;
    }

    public function test() {
        $this->output($this->dao->getAll(), 200);
    }
}
