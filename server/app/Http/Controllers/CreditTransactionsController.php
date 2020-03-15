<?php

namespace App\Http\Controllers;
use App\Models\CreditTransaction;
use DB;
use App\Models\Error;

class CreditTransactionsController extends Controller
{
    public function index()
    {
        if(isset($this->data['distributor_name'])) {
            $data = str_replace( '*', '%', $this->data['distributor_name']);
            $credit_transaction = CreditTransaction::with(['creditAccount.distributor'])
                ->join('credit_accounts as c', 'c.id', '=', 'credit_transactions.credit_id')
                ->join('distributors as d', 'c.distributor_id', '=', 'd.id')
                ->select('credit_transactions.*','d.name as distributor_name')
                ->where('name', 'LIKE' , $data)
                ->getDynamic();
        } else {
            $credit_transaction = CreditTransaction::with(['creditAccount.distributor'])
                ->join('credit_accounts as c', 'c.id', '=', 'credit_transactions.credit_id')
                ->join('distributors as d', 'c.distributor_id', '=', 'd.id')
                ->select('credit_transactions.*','d.name as distributor_name')->getDynamic();
        }
        $this->output_json(['data' => $credit_transaction], 200);
    }

    public function createCreditTransaction(CreditTransaction $creditTransaction) {
        $this->validate($this->request,
            [
                'credit_id' => 'required',
                'transaction_type' => 'required',
                'amount' => 'required|numeric|min:0',
                'reference' => 'required',
                'description' => 'required',
            ],
            [
                'credit_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                'transaction_type.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'transaction type']),
                'amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'amount']),
                'amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'amount']),
                'amount.min' => trans('messages.api.integer_param.app_error', ['Name' => 'amount']),
                'reference.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'reference']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
            ]);
        $credit_transaction = $creditTransaction->toModel($this->data);

        $result = $credit_transaction->createCreditTransaction();

        if (is_object($result) && get_class($result) == Error::class) {
            $this->output_json_client($result, 200);
        }

        $this->output_json($result, 200);
    }
}
