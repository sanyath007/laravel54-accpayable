<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\DebtType;
use App\Models\Creditor;
use App\Models\TmpDebt;

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
    
    public function getById($debtId)
    {
        return [
            'debt' => Debt::find($debtId),
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

    public function add()
    {
        return view('debts.add', [
            "creditors" => Creditor::all(),
            "debttypes" => DebtType::all(),
        ]);
    }

    public function store(Request $req)
    {
        try{
            /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
            $debt = new Debt();
            $debt->debt_id          = $this->generateAutoId();
            $debt->debt_date        = convThDateToDb($req['debt_date']);
            $debt->debt_doc_recno   = $req['debt_doc_recno'];
            $debt->debt_doc_recdate = convThDateToDb($req['debt_doc_recdate']);
            $debt->deliver_no       = $req['deliver_no'];
            $debt->deliver_date     = convThDateToDb($req['deliver_date']);
            $debt->debt_doc_no      = $req['debt_doc_no'];

            if(!empty($req['debt_doc_date'])) {
                $debt->debt_doc_date    = convThDateToDb($req['debt_doc_date']);
            }

            if(!empty($req['withdraw_id'])) {
                $debt->withdraw_id  = $req['withdraw_id'];
                $debt->po_no        = $req['po_no'];
                $debt->po_date      = convThDateToDb($req['po_date']);
            }

            $debt->debt_type_id     = $req['debt_type_id'];
            $debt->debt_type_detail = $req['debt_type_detail'];
            $debt->debt_month       = $req['debt_month'];
            $debt->debt_year        = $req['debt_year'];
            $debt->supplier_id      = $req['supplier_id'];
            $debt->supplier_name    = $req['supplier_name'];
            $debt->doc_receive      = convThDateToDb($req['doc_receive']);
            $debt->debt_amount      = $req['debt_amount'];
            $debt->debt_vatrate     = $req['debt_vatrate'];
            $debt->debt_vat         = $req['debt_vat'];
            $debt->debt_total       = $req['debt_total'];
            $debt->debt_remark      = $req['debt_remark'];

            $debt->debt_creby       = $req['debt_creby'];
            $debt->debt_credate     = date("Y-m-d H:i:s");
            $debt->debt_userid      = $req['debt_userid'];
            $debt->debt_chgdate     = date("Y-m-d H:i:s");
            $debt->debt_status      = '0';

            if($debt->save()) {
                /** Update status of TmpDebt data to 1 */
                if(!empty($req['withdraw_id'])) {
                    $tmp = TmpDebt::where('withdraw_id', $req['withdraw_id'])->first();
                    $tmp->status = 1;
                    $tmp->save();
                }

                return [
                    "status"    => 1,
                    "message"   => "Insert success.",
                    "debt"      => $debt
                ];
            } else {
                return [
                    "status"    => 0,
                    "message"   => "Insert failed.",
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function edit($debtId)
    {
        $debt = Debt::find($debtId);

        return view('debts.edit', [
            'debt' => $debt,
            "creditor" => Creditor::where('supplier_id', '=', $debt->supplier_id)->first(),
            "debttypes" => DebtType::all(),
        ]);
    }

    public function update(Request $req)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        $debt = Debt::find($req['debt_id']);
        $debt->debt_date        = convThDateToDb($req['debt_date']);
        $debt->debt_doc_recno   = $req['debt_doc_recno'];
        $debt->debt_doc_recdate = convThDateToDb($req['debt_doc_recdate']);        
        $debt->deliver_no       = $req['deliver_no'];
        $debt->deliver_date     = convThDateToDb($req['deliver_date']);
        $debt->debt_doc_no      = $req['debt_doc_no'];

        if(!empty($req['debt_doc_date'])) {
            $debt->debt_doc_date    = convThDateToDb($req['debt_doc_date']);
        }

        if(!empty($req['withdraw_id'])) {
            $debt->withdraw_id  = $req['withdraw_id'];
            $debt->po_no        = $req['po_no'];
            $debt->po_date      = convThDateToDb($req['po_date']);
        }

        $debt->debt_type_id     = $req['debt_type_id'];
        $debt->debt_type_detail = $req['debt_type_detail'];
        $debt->supplier_id      = $req['supplier_id'];
        $debt->supplier_name    = $req['supplier_name'];
        $debt->doc_receive      = convThDateToDb($req['doc_receive']);
        $debt->debt_year        = $req['debt_year'];
        $debt->debt_amount      = $req['debt_amount'];
        $debt->debt_vatrate     = $req['debt_vatrate'];
        $debt->debt_vat         = $req['debt_vat'];
        $debt->debt_total       = $req['debt_total'];
        $debt->debt_remark      = $req['debt_remark'];

        $debt->debt_creby       = $req['debt_creby'];
        $debt->debt_credate     = date("Y-m-d H:i:s");
        $debt->debt_userid      = $req['debt_userid'];
        $debt->debt_chgdate     = date("Y-m-d H:i:s");

        if($debt->save()) {
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

    public function updateStatus (Request $req, $id)
    {
        $debt = Debt::find($id)->update([
                    'debt_chgdate'  => date("Y-m-d H:i:s"),
                    'debt_userid'   => $req['user'],
                    'debt_status'   => $req['status']
                ]);

        if ($debt) {
            return [
                'status' => 'success',
                'message' => 'Updated id ' . $req['debt_id'] . 'is successed.',
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
