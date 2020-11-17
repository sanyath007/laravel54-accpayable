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

    public function debtCreditorExcel($creditor, $sdate, $edate, $showall) {
        $fileName = 'debt-creditor-' . date('YmdHis') . '.xlsx';
        return (new DebtCreditorExport($creditor, $sdate, $edate, $showall))->download($fileName);
    }

    public function debtDebttypeExcel($debttype, $sdate, $edate, $showall) {
        $fileName = 'debt-type-' . date('YmdHis') . '.xlsx';
        return (new DebttypeExport($debttype, $sdate, $edate, $showall))->download($fileName);
    }

    public function sumMonth($month)
    {
        // $sdate = $month . '-01';
        // $edate = date("Y-m-t", strtotime($sdate));

        $sql = "SELECT CONCAT(YEAR(debt_date),'-',MONTH(debt_date)) AS yyyymm, 
                SUM(CASE WHEN (debt_status IN ('0','1')) THEN debt_total END) AS debt,
                SUM(CASE WHEN (debt_status IN ('2')) THEN debt_total END) AS paid,
                SUM(CASE WHEN (debt_status IN ('4')) THEN debt_total END) AS setzero

                FROM nrhosp_acc_debt d 
                WHERE (debt_date BETWEEN '2017-10-01' AND '2018-09-30')
                GROUP BY CONCAT(YEAR(debt_date),'-',MONTH(debt_date))
                ORDER BY CONCAT(YEAR(debt_date),'-',MONTH(debt_date))";

        return \DB::select($sql);
    }

    public function sumYear($month)
    {
        // $sdate = $month . '-01';
        // $edate = date("Y-m-t", strtotime($sdate));

        $sql = "SELECT YEAR(debt_date) AS yyyy, 
                SUM(CASE WHEN (debt_status IN ('0','1')) THEN debt_total END) AS debt,
                SUM(CASE WHEN (debt_status IN ('2')) THEN debt_total END) AS paid,
                SUM(CASE WHEN (debt_status IN ('4')) THEN debt_total END) AS setzero
                FROM nrhosp_acc_debt d 
                WHERE (YEAR(debt_date) BETWEEN '2011' AND '2018')
                GROUP BY YEAR(debt_date)
                ORDER BY YEAR(debt_date)";

        return \DB::select($sql);
    }
}
