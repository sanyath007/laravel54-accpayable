<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bank;
use App\Models\BankBranch;

class BankBranchController extends Controller
{
    public function index()
    {
    	return view('bank-branches.list');
    }

    public function search($searchKey)
    {
        $conditions = [];

        if($searchKey != '0') array_push($conditions, ['bank_branch_name', 'like', '%'.$searchKey.'%']);

        if(count($conditions) > 0) {
            $branches = BankBranch::where($conditions)
                            ->with('bank')
                            ->paginate(10);
        } else {
            $branches = BankBranch::with('bank')
                            ->paginate(10);
        }

        echo $branches;
        return [
            'branches' => $branches
        ];
    }

    public function getById($bbId)
    {
        return [
            'branch' => BankBranch::where(['bank_branch_id' => $bbId])
                            ->with('bank')
                            ->first(),
        ];
    }

    private function generateAutoId()
    {
        $branch = BankBranch::where('bank_branch_id', '<>', '999')
                        ->orderBy('bank_branch_id', 'DESC')
                        ->first();

        $tmpLastId =  ((int)($branch->bank_branch_id)) + 1;
        $lastId = sprintf("%'.02d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
    	return view('bank-branches.add', [
            'banks' => Bank::all(),
    	]);
    }

    public function store(Request $req)
    {
        $branch = new BankBranch();
        $branch->bank_branch_id = $this->generateAutoId();
        $branch->bank_branch_name = $req['bank_branch_name'];
        $branch->bank_id = $req['bank_id'];
        $branch->bank_branch_addr = $req['bank_branch_addr'];
        $branch->bank_branch_tel = $req['bank_branch_tel'];
        $branch->bank_branch_fax = $req['bank_branch_fax'];

        if($branch->save()) {
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

    public function edit($bbId)
    {
        return view('bank-branches.edit', [
            'branch'   => BankBranch::find($bbId),
            'banks'     => Bank::all(),
        ]);
    }

    public function update(Request $req)
    {
        $branch = BankBranch::find($req['bank_branch_id']);
        $branch->bank_branch_name = $req['bank_branch_name'];
        $branch->bank_id = $req['bank_id'];
        $branch->bank_branch_addr = $req['bank_branch_addr'];
        $branch->bank_branch_tel = $req['bank_branch_tel'];
        $branch->bank_branch_fax = $req['bank_branch_fax'];

        if($branch->save()) {
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

    public function delete($bbId)
    {
        $branch = BankBranch::find($bbId);

        if($branch->delete()) {
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
