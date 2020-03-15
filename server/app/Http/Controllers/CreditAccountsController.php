<?php

namespace App\Http\Controllers;

use App\Models\CreditAccount;
use DB;

class CreditAccountsController extends Controller
{
    public function index()
    {
        $credit_account = CreditAccount::with('distributor', 'credit_transactions')->getDynamic();

        $this->output_json(['data' => $credit_account], 200);
    }

    public function createCreditAccount(CreditAccount $credit_account)
    {
        $this->validate($this->request,
            [
                'distributor_id' => 'required',
                'amount' => 'required|numeric',
                'hold_amount' => 'required|numeric',
                'available_amount' => 'required|numeric',
                'credit_limit' => 'required|numeric',
            ],
            [
                'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                'amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'amount']),
                'amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'amount']),
                'hold_amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'hold amount']),
                'hold_amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'hold amount']),
                'available_amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'available amount']),
                'available_amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'available amount']),
                'credit_limit.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'credit limit']),
                'credit_limit.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'credit limit']),
            ]);
        $credit_account = $credit_account->toModel($this->data);

        $result = $credit_account->createCreditAccount();

        $this->output_json($result, 200);

    }

    public function updateCreditAccount(CreditAccount $credit_account, $credit_account_id)
    {
        $this->validate($this->request,
            [
                'distributor_id' => 'required',
                'amount' => 'required|numeric',
                'hold_amount' => 'required|numeric',
                'available_amount' => 'required|numeric',
                'credit_limit' => 'required|numeric',
            ],
            [
                'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                'amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'amount']),
                'amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'amount']),
                'hold_amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'hold amount']),
                'hold_amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'hold amount']),
                'available_amount.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'available amount']),
                'available_amount.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'available amount']),
                'credit_limit.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'credit limit']),
                'credit_limit.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'credit limit']),
            ]);
        $this->data['id'] = $credit_account_id;

        $credit_account = $credit_account->toModel($this->data);

        $result = $credit_account->updateCreditAccount();

        $this->output_json($result, 200);

    }

    public function creditAccountAndDistributor() {
        $result = DB::table('credit_accounts')->join('distributors','distributors.id','credit_accounts.distributor_id')
             ->select('credit_accounts.id as id','distributors.name as name')
            ->get();
        $this->output_json(['data' => $result], 200);
    }

    public function getSomeFieldCreditAccount($distributor_id) {
        $result = CreditAccount::where('distributor_id', $distributor_id)->first();
        $this->output_json(['data' => $result], 200);
    }

    public function delete()
    {
        $this->deleteRecord('CreditAccount');
    }
}
