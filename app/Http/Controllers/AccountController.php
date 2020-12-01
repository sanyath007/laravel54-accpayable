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

            $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])
                                ->sum('debt_total');
        } else {
            if($debttype != 0 && $creditor != 0) {
                $debts = \DB::table('nrhosp_acc_debt')
                            ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_app.app_recdoc_date',
                                     'nrhosp_acc_app.app_id')
                            ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                            ->leftJoin('nrhosp_acc_app_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_app_detail.debt_id')
                            ->leftJoin('nrhosp_acc_app', 'nrhosp_acc_app_detail.app_id', '=', 'nrhosp_acc_app.app_id')
                            ->whereNotIn('nrhosp_acc_debt.debt_status', [2,3,4])
                            ->where('nrhosp_acc_debt.debt_type_id', '=', $debttype)
                            ->where('nrhosp_acc_debt.supplier_id', '=', $creditor)
                            ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                            ->orderBy('nrhosp_acc_debt.debt_date', 'ASC');

                $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])
                                ->where('debt_type_id', '=', $debttype)
                                ->where('supplier_id', '=', $creditor)
                                ->whereBetween('debt_date', [$sdate, $edate])
                                ->sum('debt_total');
            } else {
                if($debttype != 0 && $creditor == 0) {
                    $debts = \DB::table('nrhosp_acc_debt')
                                ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_app.app_recdoc_date',
                                         'nrhosp_acc_app.app_id')
                                ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                                ->leftJoin('nrhosp_acc_app_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_app_detail.debt_id')
                                ->leftJoin('nrhosp_acc_app', 'nrhosp_acc_app_detail.app_id', '=', 'nrhosp_acc_app.app_id')
                                ->whereNotIn('nrhosp_acc_debt.debt_status', [2,3,4])
                                ->where('nrhosp_acc_debt.debt_type_id', '=', $debttype)
                                ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                                ->orderBy('nrhosp_acc_debt.debt_date', 'ASC');

                    $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])
                                    ->where('debt_type_id', '=', $debttype)
                                    ->whereBetween('debt_date', [$sdate, $edate])
                                    ->sum('debt_total');
                } else if($debttype == 0 && $creditor != 0) {
                     $debts = \DB::table('nrhosp_acc_debt')
                                    ->select('nrhosp_acc_debt.*', 'nrhosp_acc_debt_type.debt_type_name', 'nrhosp_acc_app.app_recdoc_date',
                                             'nrhosp_acc_app.app_id')
                                    ->leftJoin('nrhosp_acc_debt_type', 'nrhosp_acc_debt.debt_type_id', '=', 'nrhosp_acc_debt_type.debt_type_id')
                                    ->leftJoin('nrhosp_acc_app_detail', 'nrhosp_acc_debt.debt_id', '=', 'nrhosp_acc_app_detail.debt_id')
                                    ->leftJoin('nrhosp_acc_app', 'nrhosp_acc_app_detail.app_id', '=', 'nrhosp_acc_app.app_id')
                                    ->whereNotIn('nrhosp_acc_debt.debt_status', [2,3,4])
                                    ->where('nrhosp_acc_debt.supplier_id', '=', $creditor)
                                    ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                                    ->orderBy('nrhosp_acc_debt.debt_date', 'ASC');

                    $totalDebt = Debt::whereNotIn('debt_status', [2,3,4])
                                    ->where('supplier_id', '=', $creditor)
                                    ->whereBetween('debt_date', [$sdate, $edate])
                                    ->sum('debt_total');
                }   
            }   
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

    public function ledger($dataType, $sdate, $edate, $showall)
    {
        $debts = [];

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
                        ->select('nrhosp_acc_debt.supplier_id', 'nrhosp_acc_debt.supplier_name')
                        ->whereBetween('nrhosp_acc_debt.debt_date', [$sdate, $edate])
                        ->groupBy('nrhosp_acc_debt.supplier_id', 'nrhosp_acc_debt.supplier_name');

        $creditors = \DB::table(\DB::raw("(" .$subQuery->toSql() . ") as creditors"))
                        ->mergeBindings($subQuery)
                        ->get();

        return view('accounts.ledger', [
            "creditors" => $creditors,
            "debts"     => $debts,
            "sdate"     => $sdate,
            "edate"     => $edate,
            "showall"  => $showall,
        ]);
    }

    public function ledgerExcel($sdate, $edate, $showall)
    {
        $fileName = 'ledger-' . date('YmdHis') . '.xlsx';
        return (new LedgerExport($sdate, $edate, $showall))->download($fileName);
    }

    public function ledgerDebttype($dataType, $sdate, $edate, $showall)
    {
        $debts = [];
        $paidOutOfDates = '';
        $index = 0;

        $objPaidOutOfDates = \DB::select("SELECT payment_id, debt_id FROM nrhosp_acc_payment_detail WHERE (
                                    payment_id in (
                                        SELECT payment_id FROM nrhosp_acc_payment
                                        WHERE (paid_date NOT BETWEEN '$sdate' AND '$edate') 
                                        AND (paid_stat='Y')
                                    )
                                )");

        foreach($objPaidOutOfDates as $p) {
            if(++$index == count($objPaidOutOfDates)) {
                $paidOutOfDates .= "'" .$p->debt_id. "'";
            }
            else {
                $paidOutOfDates .= "'" .$p->debt_id. "',";
            }
        }

        $sql = "SELECT d.debt_type_id, dt.debt_type_name,
                SUM(d.debt_total) as credit,
                SUM(CASE WHEN (d.debt_id NOT IN ($paidOutOfDates)) THEN pd.rcpamt END) as debit
                FROM nrhosp_acc_debt d 
                LEFT JOIN nrhosp_acc_debt_type dt ON (d.debt_type_id=dt.debt_type_id)
                LEFT JOIN nrhosp_acc_payment_detail pd ON (d.debt_id=pd.debt_id) "; //#AND (d.debt_status NOT IN ('3','4'))

        if($showall == '1') {
            $sql .= "WHERE (d.debt_date BETWEEN '$sdate' AND '$edate') ";
        }

        $sql .= "GROUP BY d.debt_type_id, dt.debt_type_name
                 ORDER BY debt_type_id";

        $debts = \DB::select($sql);

        return view('accounts.ledger-debttype', [
            "debts"             => $debts,
            "sdate"             => $sdate,
            "edate"             => $edate,
            "showall"           => $showall,
        ]);
    }

    public function ledgerDebttypeExcel($sdate, $edate, $showall)
    {
        $fileName = 'ledger-debttype' . date('YmdHis') . '.xlsx';
        return (new LedgerDebttypeExport($sdate, $edate, $showall))->download($fileName);
    }
}
