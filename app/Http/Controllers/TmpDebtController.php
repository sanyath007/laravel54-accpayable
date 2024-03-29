<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TmpDebt;
use App\Models\DebtType;
use App\Models\Creditor;


class TmpDebtController extends Controller
{
    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'withdraw_id' => 'required',
            'deliver_no' => 'required',
            'deliver_date' => 'required',
            'year' => 'required',
            'supplier_id' => 'required',
            'amount' => 'required',
            'vatrate' => 'required',
            'vat' => 'required',
            'total' => 'required',
            'remark' => 'required',
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

        $debts = TmpDebt::where('debt_status', '=', '0')
                    ->when(count($conditions) > 0, function($q) use($conditions) {
                        $q->where($conditions);
                    })
                    ->with('debttype');

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

    public function getAll(Request $req)
    {
        $supplier = $req->get('supplier');
        $deliverNo = $req->get('deliver_no');

        $conditions = [];
        $debts = TmpDebt::where('status', '0')
                    ->with('supplier')
                    ->when(count($conditions) > 0, function($q) use($conditions) {
                        $q->where($conditions);
                    })
                    ->when(!empty($supplier), function($q) use ($supplier) {
                        $q->where('supplier_id', $supplier);
                    })
                    ->when(!empty($deliverNo), function($q) use ($deliverNo) {
                        $q->where('deliver_no', 'like', '%'.$deliverNo.'%');
                    })
                    ->paginate(20);

        return [
            'debts' => $debts,
        ];
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
        } else {
            $debts = Debt::where('supplier_id', '=', $creditor)
                            ->where('debt_status', '=', '0')
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
        try {
            $debt = new TmpDebt();
            $debt->withdraw_id      = $req['withdraw_id'];
            $debt->withdraw_no      = $req['withdraw_no'];
            $debt->withdraw_date    = $req['withdraw_date'];
            $debt->deliver_no       = $req['deliver_no'];
            $debt->deliver_date     = $req['deliver_date'];
            $debt->year             = $req['year'];
            $debt->supplier_id      = $req['supplier_id'];
            $debt->supplier_name    = $req['supplier_name'];
            $debt->desc             = $req['desc'];
            $debt->po_no            = $req['po_no'];
            $debt->po_date          = $req['po_date'];
            $debt->amount           = $req['amount'];
            $debt->vatrate          = $req['vatrate'];
            $debt->vat              = $req['vat'];
            $debt->total            = $req['total'];
            $debt->remark           = $req['remark'];
            $debt->status           = '0';
            $debt->created_user     = $req['user'];
            $debt->updated_user     = $req['user'];

            if($debt->save()) {
                return [
                    "status"    => 1,
                    "message"   => "Insert success.",
                    "debt"      => $debt
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
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

    public function update(Request $req, $id)
    {
        try {
            $debt = TmpDebt::find($id);
            $debt->withdraw_id      = $req['withdraw_id'];
            $debt->withdraw_no      = $req['withdraw_no'];
            $debt->withdraw_date    = $req['withdraw_date'];
            $debt->deliver_no       = $req['deliver_no'];
            $debt->deliver_date     = $req['deliver_date'];
            $debt->year             = $req['year'];
            $debt->supplier_id      = $req['supplier_id'];
            $debt->supplier_name    = $req['supplier_name'];
            $debt->desc             = $req['desc'];
            $debt->po_no            = $req['po_no'];
            $debt->po_date          = $req['po_date'];
            $debt->amount           = $req['amount'];
            $debt->vatrate          = $req['vatrate'];
            $debt->vat              = $req['vat'];
            $debt->total            = $req['total'];
            $debt->remark           = $req['remark'];
            $debt->status           = '0';
            $debt->updated_user     = $req['user'];

            if($debt->save()) {
                return [
                    "status"    => 1,
                    "message"   => "Insertion successfully!!",
                    "debt"      => $debt
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
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

    public function pending(Request $req, $id)
    {
        try {
            $tmp = TmpDebt::find($id);
            $tmp->status = 9;
    
            if ($tmp->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Updating successfully!!',
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function resend(Request $req, $id)
    {
        try {
            $tmp = TmpDebt::find($id);
            $tmp->status = 0;
    
            if ($tmp->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Updating successfully!!',
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }
}
