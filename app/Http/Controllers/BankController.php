<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
    	return view('banks.list');
    }

    public function search($searchKey)
    {
        if($searchKey == '0') {
            $banks = Bank::paginate(10);
        } else {
            $banks = Bank::where('bank_name', 'like', '%'.$searchKey.'%')->paginate(10);
        }

        return [
            'banks' => $banks,
        ];
    }

    public function getById($bankId)
    {
        return [
            'bank' => Bank::find($bankId)
        ];
    }

    private function generateAutoId()
    {
        $bank = Bank::orderBy('bank_id', 'DESC')->first();

        $tmpLastId =  ((int)($bank->bank_id)) + 1;
        $lastId = sprintf("%'.02d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
    	return view('banks.add');
    }

    public function store(Request $req)
    {
        $bank = new Bank();
        $bank->bank_id = $this->generateAutoId();
        $bank->bank_name = $req['bank_name'];

        if($bank->save()) {
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

    public function edit($bankId)
    {
        return view('banks.edit', [
            'bank'   => Bank::find($bankId)
        ]);
    }

    public function update(Request $req)
    {
        $bank = Bank::find($req['bank_id']);
        $bank->bank_name = $req['bank_name'];

        if($bank->save()) {
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

    public function delete($bankId)
    {
        $bank = Bank::find($bankId);

        if($bank->delete()) {
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
