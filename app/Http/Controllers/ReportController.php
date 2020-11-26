<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Models\DebtType;
use App\Models\Creditor;

use App\Exports\DebtCreditorExport;
use App\Exports\DebttypeExport;

class ReportController extends Controller
{
    public function debtCreditor()
    {
    	return view('reports.debt-creditor', [
    		"creditors" => Creditor::all(),
    	]);
    }

    public function debtCreditorRpt(Request $req, $creditor, $sdate, $edate, $showall)
    {
        $perpage = 10;
        $page = (isset($req['page'])) ? $req['page'] : 1;
        $offset = ($page * $perpage) - $perpage;

        $sql = "SELECT b.debt_id,b.debt_date,b.deliver_no,c.debt_type_name,b.debt_type_detail,
                b.supplier_id,b.supplier_name,b.debt_amount,b.debt_vatrate,b.debt_vat,b.debt_total, 
                CASE WHEN (b.debt_status='0') THEN 'ตั้งหนี้' 
                WHEN (b.debt_status='1') THEN 'ขออนุมัติ' 
                WHEN (b.debt_status='2') THEN 'ตัดจ่าย' END AS debt_status 
                FROM nrhosp_acc_debt b 
                LEFT JOIN nrhosp_acc_debt_type c ON (b.debt_type_id=c.debt_type_id)
                WHERE (b.debt_status <> '3') ";

        if($creditor != 0) {
            $sql .= "AND (b.supplier_id='$creditor')";
        } 

        if($showall == 0) {
            $sql .= "AND (b.debt_date BETWEEN '$sdate' AND '$edate')";
        }

        $sql .= "ORDER BY b.debt_date ";
        
        $count = count(\DB::select($sql));

        $sql .= "LIMIT $offset, $perpage ";

        $items = \DB::select($sql);
        
        $paginator = new Paginator($items, $count, $perpage, $page, [
            'path' => $req->url(),
            'query' => $req->query()
        ]);

        return [
            'pager' => $paginator,
            'page' => $page
        ];
    }

    public function debtDebttype()
    {
    	return view('reports.debt-debttype', [
    		"debttypes" => DebtType::all(),
    	]);
    }

    public function debtDebttypeRpt(Request $req, $debttype, $sdate, $edate, $showall)
    {
        $perpage = 10;
        $page = (isset($req['page'])) ? $req['page'] : 1;
        $offset = ($page * $perpage) - $perpage;

        $sql = "SELECT b.debt_id,b.debt_date,b.deliver_no,c.debt_type_name,b.debt_type_detail,";
        $sql .= "b.supplier_id,b.supplier_name,b.debt_amount,b.debt_vatrate,b.debt_vat,b.debt_total, ";
        $sql .= "CASE WHEN (b.debt_status='0') THEN 'ตั้งหนี้' ";
        $sql .= "WHEN (b.debt_status='1') THEN 'ขออนุมัติ' ";
        $sql .= "WHEN (b.debt_status='2') THEN 'ตัดจ่าย' END AS debt_status "; //, pa.paid_date 
        $sql .= "FROM nrhosp_acc_debt b ";
        $sql .= "LEFT JOIN nrhosp_acc_debt_type c ON (b.debt_type_id=c.debt_type_id)";
        /*$sql .= "LEFT JOIN ("; 
            $sql .= "SELECT pd.debt_id, p.paid_date, p.cheque_no, p.cheque_date ";
            $sql .= "FROM nrhosp_acc_payment_detail pd ";
            $sql .= "LEFT JOIN nrhosp_acc_payment p ON (pd.payment_id=p.payment_id)";
        $sql .= ") AS pa ON (b.debt_id=pa.debt_id)";*/        
        $sql .= "WHERE (b.debt_status IN ('0', '1'))";

        if($debttype != 0) {
            $sql .= "AND (b.debt_type_id='$debttype')";
        }

        if($showall == 0) {
            $sql .= "AND (b.debt_date BETWEEN '$sdate' AND '$edate')";
        }

        $sql .= "ORDER BY b.supplier_id, b.debt_date ";

        $count = count(\DB::select($sql));

        $sql .= "LIMIT $offset, $perpage ";

        $items = \DB::select($sql);
        
        $paginator = new Paginator($items, $count, $perpage, $page, [
            'path' => $req->url(),
            'query' => $req->query()
        ]);

        return [
            'pager' => $paginator,
            'page' => $page
        ];
    }

    public function debtChart($creditorId)
    {
        /** 0=รอดำเนินการ,1=ขออนุมัติ,2=ตัดจ่าย,3=ยกเลิก,4=ลดหนี้ศุนย์ */
        $sql = "SELECT
                SUM(CASE WHEN (debt_status IN ('0','1')) THEN debt_total END) as debt, 
                SUM(CASE WHEN (debt_status IN ('2')) THEN debt_total END) as paid,
                SUM(CASE WHEN (debt_status IN ('4')) THEN debt_total END) as setzero
                FROM nrhosp_acc_debt
                WHERE (supplier_id='$creditorId') ";

        return \DB::select($sql);
    }

    public function debtCreditorExcel($creditor, $sdate, $edate, $showall)
    {
        $fileName = 'debt-type-' . date('YmdHis') . '.xlsx';

        $sql = "SELECT b.debt_id,b.debt_date,b.deliver_no,c.debt_type_name,b.debt_type_detail,
                b.supplier_id,b.supplier_name,b.debt_amount,b.debt_vatrate,b.debt_vat,b.debt_total, 
                CASE WHEN (b.debt_status='0') THEN 'ตั้งหนี้' 
                WHEN (b.debt_status='1') THEN 'ขออนุมัติ' 
                WHEN (b.debt_status='2') THEN 'ตัดจ่าย' END AS debt_status 
                FROM nrhosp_acc_debt b 
                LEFT JOIN nrhosp_acc_debt_type c ON (b.debt_type_id=c.debt_type_id)
                WHERE (b.debt_status <> '3') ";

        if($creditor != 0) {
            $sql .= "AND (b.supplier_id='$creditor')";
        } 

        if($showall == 0) {
            $sql .= "AND (b.debt_date BETWEEN '$sdate' AND '$edate')";
        }

        $sql .= "ORDER BY b.debt_date ";

        /** Use raw array */
        // $data = collect(\DB::select($sql))->map(function($x){ return (array) $x; })->toArray();

        /** Use view */
        $data = \DB::select($sql);

        return \Excel::create($fileName, function($excel) use ($data) {
            $excel->sheet('sheet1', function($sheet) use ($data)
            {
                /** Use raw array */
                // $sheet->fromArray($data);

                /** Use view */
                $sheet->loadView('exports.debt-creditor-excel', [
                    'debts' => $data
                ]);                
            });
        })->download();
    }

    public function debtDebttypeExcel($dataType, $debttype, $sdate, $edate, $showall) {
        $fileName = 'debt-type-' . date('YmdHis') . '.xlsx';

        $data = \DB::select($sql);

        return \Excel::create($fileName, function($excel) use ($data) {
            $excel->sheet('sheet1', function($sheet) use ($data)
            {
                /** Use raw array */
                // $sheet->fromArray($data);

                /** Use view */
                $sheet->loadView('exports.debt-creditor-excel', [
                    'debts' => $data
                ]);                
            });
        })->download();
    }

    public function sumArrear()
    {
        return view('reports.sum-arrear');
    }

    public function sumArrearData(Request $req, $dataType, $sdate, $edate, $showall)
    {
        $fileName = 'sum-arrear-' . date('YmdHis') . '.xlsx';

        $perpage = 10;
        $page = (isset($req['page'])) ? $req['page'] : 1;
        $offset = ($page * $perpage) - $perpage;

        $sql = "SELECT d.supplier_id, s.supplier_name,
            SUM(CASE WHEN (DATEDIFF(NOW(), debt_date) < 60) THEN d.debt_total END) as less60d,
            SUM(CASE WHEN (DATEDIFF(NOW(), debt_date) >= 60 AND DATEDIFF(NOW(), debt_date) < 90) THEN d.debt_total END) as b6089d,
            SUM(CASE WHEN (DATEDIFF(NOW(), debt_date) >= 90 AND DATEDIFF(NOW(), debt_date) < 120) THEN d.debt_total END) as b90119d,
            SUM(CASE WHEN (DATEDIFF(NOW(), debt_date) > 120) THEN d.debt_total END) as great120d,
            SUM(d.debt_total) as total
            FROM nrhosp_acc_debt d
            LEFT JOIN stock_supplier s ON (d.supplier_id=s.supplier_id) 
            WHERE (d.debt_status IN ('0', '1'))
            GROUP BY d.supplier_id, s.supplier_name ";

        if($dataType == 'excel') {
            $data = \DB::select($sql);

            return \Excel::create($fileName, function($excel) use ($data) {
                $excel->sheet('sheet1', function($sheet) use ($data)
                {    
                    /** Use view */
                    $sheet->loadView('exports.sum-arrear-excel', [
                        'debts' => $data
                    ]);                
                });
            })->download();
        } else {
            $count = count(\DB::select($sql));

            $sql .= "LIMIT $offset, $perpage ";

            $items = \DB::select($sql);

            $paginator = new Paginator($items, $count, $perpage, $page, [
                'path' => $req->url(),
                'query' => $req->query()
            ]);
    
            return [
                'debts' => $paginator,
                'page' => $page
            ];
        }
    }
}
