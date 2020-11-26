<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bank;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
    	return view('bankaccs.list');
    }

    public function search($searchKey)
    {
        if($searchKey == '0') {
            $bankaccs = BankAccount::with('bank')
                            ->with('branch')
                            ->orderBy('bank_acc_no')
                            ->paginate(10);
        } else {
            $bankaccs = BankAccount::where('bank_acc_name', 'like', '%'.$searchKey.'%')
                            ->with('bank')
                            ->with('branch')
                            ->orderBy('bank_acc_no')
                            ->paginate(10);
        }

        return [
            'bankaccs' => $bankaccs,
        ];
    }

    private function generateAutoId()
    {
        $bankacc = \DB::table('nrhosp_acc_com_bank')
                        ->select('bank_acc_id')
                        ->orderBy('bank_acc_id', 'DESC')
                        ->first();

        $tmpLastId =  ((int)($bankacc->bank_acc_id)) + 1;
        $lastId = sprintf("%'.05d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
    	return view('bankaccs.add', [
            'banks' => Bank::all(),
            'branches' => \DB::table('nrhosp_acc_bank_branch')->select('*')->get(),
            'bankTypes' => \DB::table('nrhosp_acc_bankacc_type')->select('*')->get(),
    	]);
    }

    public function store(Request $req)
    {
        $bankacc = new BankAccount();
        $bankacc->bank_acc_id = $this->generateAutoId();
        $bankacc->bank_acc_no = $req['bank_acc_no'];
        $bankacc->bank_acc_name = $req['bank_acc_name'];
        $bankacc->bank_id = $req['bank_id'];
        $bankacc->bankacc_type_id = $req['bankacc_type_id'];
        $bankacc->bank_branch_id = $req['bank_branch_id'];
        $bankacc->company_id = $req['company_id'];

        if($bankacc->save()) {
            return [
                "status" => "success",
                "message" => "Insert success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Insert failed.",
            ];
        }
    }

    public function getById($baId)
    {
        return [
            'bankacc' => BankAccount::find($baId)
                            ->with('bank')
                            ->get(),
        ];
    }

    public function edit($baId)
    {
        return view('bankaccs.edit', [
            'bankacc'   => BankAccount::find($baId),
            'banks'     => Bank::all(),
            'branches'  => \DB::table('nrhosp_acc_bank_branch')->select('*')->get(),
            'bankTypes' => \DB::table('nrhosp_acc_bankacc_type')->select('*')->get(),
        ]);
    }

    public function update(Request $req)
    {
        $bankacc = BankAccount::find($req['bank_acc_id']);
        $bankacc->bank_acc_no = $req['bank_acc_no'];
        $bankacc->bank_acc_name = $req['bank_acc_name'];
        $bankacc->bank_id = $req['bank_id'];
        $bankacc->bankacc_type_id = $req['bankacc_type_id'];
        $bankacc->bank_branch_id = $req['bank_branch_id'];
        $bankacc->company_id = $req['company_id'];

        if($bankacc->save()) {
            return [
                "status" => "success",
                "message" => "Update success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Update failed.",
            ];
        }
    }

    public function delete($baId)
    {
        $bankacc = BankAccount::find($baId);

        if($bankacc->delete()) {
            return [
                "status" => "success",
                "message" => "Delete success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Delete failed.",
            ];
        }
    }
}
