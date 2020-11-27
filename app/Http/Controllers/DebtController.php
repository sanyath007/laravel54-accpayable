<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\DebtType;
use App\Models\Creditor;


class DebtController extends Controller
{
    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'debt_date' => 'required',
            'debt_doc_recno' => 'required',
            'debt_doc_recdate' => 'required',
            'deliver_no' => 'required',
            'deliver_date' => 'required',
            'debt_doc_no' => 'required',
            'purchased_method' => 'required',
            'budget_type' => 'required',
            'debt_doc_date' => 'required',
            'debt_type_id' => 'required',
            'debt_type_detail' => 'required',
            'debt_month' => 'required',
            'debt_year' => 'required',
            'supplier_id' => 'required',
            'supplier_name' => 'required',
            'doc_receive' => 'required',
            'debt_amount' => 'required',
            'debt_vatrate' => 'required',
            'debt_vat' => 'required',
            'debt_total' => 'required',
            'debt_remark' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        } else {
            return [
                'success' => 1,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    public function index()
    {
    	return view('debts.list', [
            "creditors" => Creditor::all(),
    	]);
    }

    public function search($dataType, $sdate, $edate, $supplier, $showall)
    {
        $conditions = [];

        if($showall == 0) {
            if($sdate != 0 && $edate != 0) {
                array_push($conditions, ['debt_date', '>=', $sdate]);
                array_push($conditions, ['debt_date', '<=', $edate]);
            }
        }

        if($supplier !== '0') array_push($conditions, ['supplier_id', '=', $supplier]);

        if(count($conditions) > 0) {
            $debts = Debt::where('debt_status', '=', '0')
                        ->where($conditions)
                        ->with('debttype');
        } else {
            $debts = Debt::where('debt_status', '=', '0')                                   
                        ->with('debttype');
        }

        if($dataType == 'excel') {
            $fileName = 'debt-list-' . date('YmdHis') . '.xlsx';
            $options = ['sdate' => $sdate, 'edate' => $edate];
            $data = $debts->get();
            
            $this->exportExcel($fileName, 'exports.debt-list-excel', $data, $options);
        } else {
            return [
                'debts' => $debts->paginate(20),
            ];
        }
    }

    private function exportExcel($fileName, $view, $data, $options)
    {
        return \Excel::create($fileName, function($excel) use ($view, $data, $options) {
            $excel->sheet('sheet1', function($sheet) use ($view, $data, $options)
            {
                $sheet->loadView($view, [
                    'debts' => $data,
                    'options' => $options
                ]);                
            });
        })->download();
    }

    public function debtRpt($creditor, $sdate, $edate, $showall)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        if($showall == 1) {
            $debts = Debt::where('supplier_id', '=', $creditor)
                            ->where('debt_status', '=', '0')
                            ->with('debttype')
                            ->paginate(20);
            $apps = Debt::where('supplier_id', '=', $creditor)
                            ->where('debt_status', '=', '1')
                            ->with('debttype')
                            ->paginate(20);
            $paids = Debt::where('supplier_id', '=', $creditor)
                            ->whereIn('debt_status', [2])
                            ->with('debttype')
                            ->paginate(20);
            $setzeros = Debt::where('supplier_id', '=', $creditor)
                            ->whereIn('debt_status', [4])
                            ->with('debttype')
                            ->paginate(20);
        } else {
            $debts = Debt::where('supplier_id', '=', $creditor)
                            ->where('debt_status', '=', '0')
                            ->whereBetween('debt_date', [$sdate, $edate])
                            ->with('debttype')
                            ->paginate(20);
            $apps =  Debt::where('supplier_id', '=', $creditor)
                            ->where('debt_status', '=', '1')
                            ->whereBetween('debt_date', [$sdate, $edate])
                            ->with('debttype')
                            ->paginate(20);
            $paids = Debt::where('supplier_id', '=', $creditor)
                            ->whereIn('debt_status', [2])
                            ->whereBetween('debt_date', [$sdate, $edate])
                            ->with('debttype')
                            ->paginate(20);
            $setzeros = Debt::where('supplier_id', '=', $creditor)
                            ->whereIn('debt_status', [4])
                            ->whereBetween('debt_date', [$sdate, $edate])
                            ->with('debttype')
                            ->paginate(20);
        }

        $totalDebt = Debt::where('supplier_id', '=', $creditor)->sum('debt_total');
        
        return [
            "debts"     => $debts,
            "apps"      => $apps,
            "paids"     => $paids,
            "setzeros"  => $setzeros,
            "totalDebt" => $totalDebt,
        ];
    }

    private function generateAutoId()
    {
        $debt = \DB::table('nrhosp_acc_debt')
                        ->select('debt_id')
                        ->orderBy('debt_id', 'DESC')
                        ->first();

        $startId = 'DB'.substr((date('Y') + 543), 2);
        $tmpLastId =  ((int)(substr($debt->debt_id, 4))) + 1;
        $lastId = $startId.sprintf("%'.07d", $tmpLastId);

        return $lastId;
    }

    public function add($creditor)
    {
    	return view('debts.add', [
    		"creditor" => Creditor::where('supplier_id', '=', $creditor)->first(),
            "debttypes" => DebtType::all(),
    	]);
    }

    public function store(Request $req)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        $debt = new Debt();
        $debt->debt_id = $this->generateAutoId();
        $debt->debt_date = $req['debt_date'];
        $debt->debt_doc_recno = $req['debt_doc_recno'];
        $debt->debt_doc_recdate = $req['debt_doc_recdate'];
        $debt->deliver_no = $req['deliver_no'];
        $debt->deliver_date = $req['deliver_date'];
        $debt->debt_doc_no = $req['debt_doc_no'];
        $debt->debt_doc_date = $req['debt_doc_date'];
        $debt->debt_type_id = $req['debt_type_id'];
        $debt->debt_type_detail = $req['debt_type_detail'];
        $debt->debt_month = $req['debt_month'];
        $debt->debt_year = $req['debt_year'];
        $debt->supplier_id = $req['supplier_id'];
        $debt->supplier_name = $req['supplier_name'];
        $debt->doc_receive = $req['doc_receive'];
        $debt->debt_amount = $req['debt_amount'];
        $debt->debt_vatrate = $req['debt_vatrate'];
        $debt->debt_vat = $req['debt_vat'];
        $debt->debt_total = $req['debt_total'];
        $debt->debt_remark = $req['debt_remark'];
        
        $debt->debt_creby = $req['debt_creby'];
        $debt->debt_credate = date("Y-m-d H:i:s");
        $debt->debt_userid = $req['debt_userid'];
        $debt->debt_chgdate = date("Y-m-d H:i:s");
        $debt->debt_status = '0';

        if($debt->save()) {
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

    public function getById($debtId)
    {
        return [
            'debt' => Debt::find($debtId),
        ];
    }

    public function edit($creditor, $debtId)
    {
        return view('debts.edit', [
            "creditor" => Creditor::where('supplier_id', '=', $creditor)->first(),
            'debt' => Debt::find($debtId),
            "debttypes" => DebtType::all(),
        ]);
    }

    public function update(Request $req)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        $debt = Debt::find($req['debt_id']);
        $debt->debt_date = $req['debt_date'];
        $debt->debt_doc_recno = $req['debt_doc_recno'];
        $debt->debt_doc_recdate = $req['debt_doc_recdate'];        
        $debt->deliver_no = $req['deliver_no'];
        $debt->deliver_date = $req['deliver_date'];
        $debt->debt_doc_no = $req['debt_doc_no'];
        $debt->debt_doc_date = $req['debt_doc_date'];
        $debt->debt_type_id = $req['debt_type_id'];
        $debt->debt_type_detail = $req['debt_type_detail'];
        $debt->supplier_id = $req['supplier_id'];
        $debt->supplier_name = $req['supplier_name'];
        $debt->doc_receive = $req['doc_receive'];
        $debt->debt_year = $req['debt_year'];
        $debt->debt_amount = $req['debt_amount'];
        $debt->debt_vatrate = $req['debt_vatrate'];
        $debt->debt_vat = $req['debt_vat'];
        $debt->debt_total = $req['debt_total'];
        $debt->debt_remark = $req['debt_remark'];

        $debt->debt_creby = $req['debt_creby'];
        $debt->debt_credate = date("Y-m-d H:i:s");
        $debt->debt_userid = $req['debt_userid'];
        $debt->debt_chgdate = date("Y-m-d H:i:s");

        if($debt->save()) {
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

    public function delete($debtId)
    {
        $debt = Debt::find($debtId);

        if($debt->delete()) {
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

    public function setZero(Request $req)
    {
        if(Debt::where('debt_id', '=', $req['debt_id'])->update(['debt_status' => '4']) <> 0) {
            return [
                'status' => 'success',
                'message' => 'Updated id ' . $req['debt_id'] . 'is successed.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Updated id ' . $req['debt_id'] . 'is failed.',
            ];
        }
    }

    public function supplierDebt($creditor)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        return [
            'debts' => Debt::where(['supplier_id' => $creditor])
                            ->where(['debt_status' => 0])
                            ->with('debttype')
                            ->paginate(10),
        ];
    }
}
