<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

use App\Models\Debt;
use App\Models\DebtType;
use App\Models\Creditor;
use App\Models\Payment;
use App\Models\PaymentDetail;

use App\Exports\LedgerExport;
use App\Exports\LedgerDebttypeExport;
use App\Exports\ArrearExport;
use App\Exports\CreditorPaidExport;

class AccountController extends Controller
{
    
    /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */

    public function arrear()
    {
        return view('accounts.arrear', [
            "creditors" => Creditor::all(),
            "debttypes" => DebtType::all(),
        ]);
    }

    public function arrearData($dataType, $debttype, $creditor, $sdate, $edate, $showall)
    {
        $debts = [];

        if($showall == 1) {
            $debts = \DB::table('nrhosp_acc_debt')
                        ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_app.app_recdoc_date',
                                'nrhosp_acc_app.app_id')
                        ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                        ->leftJoin('nrhosp_acc_app_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_app_detail.debt_id')
                        ->leftJoin('nrhosp_acc_app', 'nrhosp_acc_app_detail.app_id', '=', 'nrhosp_acc_app.app_id')
                        ->whereNotIn('nrhosp_acc_debt.debt_status', [2,3,4])
                        ->orderBy('nrhosp_acc_debt.debt_date', 'ASC');

            $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])->sum('debt_total');
        } else {
            $debtsConditions = [
                ['nrhosp_acc_debt.debt_date', '>=', $sdate],
                ['nrhosp_acc_debt.debt_date', '<=', $edate]
            ];
            $totalConditions = [
                ['debt_date', '>=', $sdate],
                ['debt_date', '<=', $edate]
            ];

            if($debttype != 0) {
                array_push($debtsConditions, ['nrhosp_acc_debt.debt_type_id', '=', $debttype]);
                array_push($totalConditions, ['debt_type_id', '=', $debttype]);
            }

            if($creditor != 0) {
                array_push($debtsConditions, ['nrhosp_acc_debt.supplier_id', '=', $creditor]);
                array_push($totalConditions, ['supplier_id', '=', $creditor]);
            }

            $debts = \DB::table('nrhosp_acc_debt')
                        ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_app.app_recdoc_date',
                                'nrhosp_acc_app.app_id')
                        ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                        ->leftJoin('nrhosp_acc_app_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_app_detail.debt_id')
                        ->leftJoin('nrhosp_acc_app', 'nrhosp_acc_app_detail.app_id', '=', 'nrhosp_acc_app.app_id')
                        ->whereNotIn('nrhosp_acc_debt.debt_status', [2,3,4])
                        ->where($debtsConditions)
                        ->orderBy('nrhosp_acc_debt.debt_date', 'ASC');

            $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])
                            ->where($totalConditions)
                            ->sum('debt_total');
        }

        if($dataType == 'excel') {
            $fileName = 'arrear-' . date('YmdHis') . '.xlsx';

            $data = $debts->get();

            return \Excel::create($fileName, function($excel) use ($data) {
                $excel->sheet('sheet1', function($sheet) use ($data)
                {
                    $sheet->loadView('exports.arrear-excel', [
                        'debts' => $data
                    ]);                
                });
            })->download();
        } else {
            return [
                "debts"     => $debts->paginate(20),
                "totalDebt" => $totalDebt,
            ];
        }
    }
    
    public function creditorPaid()
    {
        return view('accounts.creditor-paid', [
            "creditors" => Creditor::all(),
        ]);
    }

    public function creditorPaidData($dataType, $creditor, $sdate, $edate, $showall)
    {
        $debts = [];
        $conditions1 = [];
        $conditions2 = [];

        if($sdate != 0 && $edate != 0) {
            array_push($conditions1, ['nrhosp_acc_payment.paid_date', '>=', $sdate]);
            array_push($conditions1, ['nrhosp_acc_payment.paid_date', '<=', $edate]);

            array_push($conditions2, ['paid_date', '>=', $sdate]);
            array_push($conditions2, ['paid_date', '<=', $edate]);
        }

        if($showall != 1) {
            if($creditor != '0') {
                array_push($conditions1, ['nrhosp_acc_payment.supplier_id', '=', $creditor]);
                array_push($conditions2, ['supplier_id', '=', $creditor]);
            }
        }
                
        $payments = \DB::table('nrhosp_acc_payment')
                        ->select('nrhosp_acc_payment.*', 'nrhosp_acc_debt.debt_id', 'nrhosp_acc_debt.debt_type_detail', 
                            'nrhosp_acc_debt.deliver_no', 'nrhosp_acc_debt.debt_total', 'nrhosp_acc_debt.debt_status',
                            'nrhosp_acc_com_bank.bank_acc_no', 'nrhosp_acc_com_bank.bank_acc_name', 'nrhosp_acc_bank.bank_name',
                            'nrhosp_acc_debt_type.debt_type_name')
                        ->join('nrhosp_acc_payment_detail', 'nrhosp_acc_payment.payment_id', '=', 'nrhosp_acc_payment_detail.payment_id')
                        ->join('nrhosp_acc_debt', 'nrhosp_acc_payment_detail.debt_id', '=', 'nrhosp_acc_debt.debt_id')
                        ->join('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                        ->join('nrhosp_acc_com_bank', 'nrhosp_acc_payment.bank_acc_id', '=', 'nrhosp_acc_com_bank.bank_acc_id')
                        ->join('nrhosp_acc_bank', 'nrhosp_acc_com_bank.bank_id', '=', 'nrhosp_acc_bank.bank_id')
                        ->where($conditions1)
                        ->orderBy('nrhosp_acc_payment.supplier_id');

        $totalDebt = Payment::where('paid_stat', '=', 'Y')
                        ->where($conditions2)
                        ->sum('total');
        
        if($dataType == 'excel') {
            $fileName = 'creditor-paid-' . date('YmdHis') . '.xlsx';

            $data = $payments->get();

            return \Excel::create($fileName, function($excel) use ($data) {
                $excel->sheet('sheet1', function($sheet) use ($data)
                {
                    $sheet->loadView('exports.creditor-paid-excel', [
                        'payments' => $data
                    ]);                
                });
            })->download();
        } else {
            return [
                "payments"  => $payments->paginate(20),
                "totalDebt" => $totalDebt,
            ];
        }
    }

    public function ledgerCreditors()
    {
        return view('accounts.ledger-creditors', [
            "creditors" => Creditor::all(),
        ]);
    }

    public function ledgerCreditorsData(Request $req, $dataType, $sdate, $edate)
    {
        $debts = [];
        $supplier = $req->input('supplier');
        $showall = $req->input('showall');

        $debts = \DB::table('nrhosp_acc_debt')
                        ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_payment_detail.cheque_amt',
                                'nrhosp_acc_payment_detail.rcpamt', 'nrhosp_acc_payment.cheque_no', 'nrhosp_acc_payment.payment_id')
                        ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                        ->leftJoin('nrhosp_acc_payment_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_payment_detail.debt_id')
                        ->leftJoin('nrhosp_acc_payment', 'nrhosp_acc_payment_detail.payment_id', '=', 'nrhosp_acc_payment.payment_id')
                        ->whereNotIn('nrhosp_acc_debt.debt_status', [3,4])
                        ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                        ->when(!empty($supplier), function($q) use ($supplier) {
                            $q->where('nrhosp_acc_debt.supplier_id', $supplier);
                        })
                        ->get();

        $subQuery = \DB::table('nrhosp_acc_debt')
                        ->select('nrhosp_acc_debt.supplier_id', 'nrhosp_acc_debt.supplier_name')
                        ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                        ->when(!empty($supplier), function($q) use ($supplier) {
                            $q->where('nrhosp_acc_debt.supplier_id', $supplier);
                        })
                        ->groupBy('nrhosp_acc_debt.supplier_id', 'nrhosp_acc_debt.supplier_name');

        $creditors = \DB::table(\DB::raw("(" .$subQuery->toSql() . ") as creditors"))
                        ->mergeBindings($subQuery);

        if($dataType == 'excel') {
            $fileName = 'ledger-creditors-' . date('YmdHis') . '.xlsx';

            $data = [
                "creditors" => $creditors->get(),
                "debts"     => $debts
            ];

            return \Excel::create($fileName, function($excel) use ($data) {
                $excel->sheet('sheet1', function($sheet) use ($data)
                {
                    $sheet->loadView('exports.ledger-creditors-excel', $data);                
                });
            })->download();
        } else {
            return [
                "creditors" => $creditors->paginate(10),
                "debts"     => $debts
            ];
        }
    }

    public function ledgerDebttypes()
    {
        return view('accounts.ledger-debttypes');
    }

    public function ledgerDebttypesData(Request $req, $dataType, $sdate, $edate)
    {
        $debts = [];
        $showall = $req->input('showall');

        $debts = \DB::table('nrhosp_acc_debt')
                        ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_payment_detail.cheque_amt',
                                'nrhosp_acc_payment_detail.rcpamt', 'nrhosp_acc_payment.cheque_no', 'nrhosp_acc_payment.payment_id')
                        ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                        ->leftJoin('nrhosp_acc_payment_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_payment_detail.debt_id')
                        ->leftJoin('nrhosp_acc_payment', 'nrhosp_acc_payment_detail.payment_id', '=', 'nrhosp_acc_payment.payment_id')
                        ->whereNotIn('nrhosp_acc_debt.debt_status', [3,4])
                        ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                        ->get();

        $subQuery = \DB::table('nrhosp_acc_debt')
                        ->join('nrhosp_acc_debt_type','nrhosp_acc_debt_type.debt_type_id','=','nrhosp_acc_debt.debt_type_id')
                        ->select('nrhosp_acc_debt.debt_type_id', 'nrhosp_acc_debt_type.debt_type_name')
                        ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                        ->groupBy('nrhosp_acc_debt.debt_type_id', 'nrhosp_acc_debt_type.debt_type_name');

        $debttypes = \DB::table(\DB::raw("(" .$subQuery->toSql() . ") as debttypes"))
                        ->mergeBindings($subQuery);

        if($dataType == 'excel') {
            $fileName = 'ledger-debttypes-' . date('YmdHis') . '.xlsx';

            $data = [
                "debttypes" => $debttypes->get(),
                "debts"     => $debts
            ];

            return \Excel::create($fileName, function($excel) use ($data) {
                $excel->sheet('sheet1', function($sheet) use ($data)
                {
                    $sheet->loadView('exports.ledger-debttypes-excel', $data);                
                });
            })->download();
        } else {
            return [
                "debttypes" => $debttypes->paginate(20),
                "debts"     => $debts,
            ];
        }
    }
}
