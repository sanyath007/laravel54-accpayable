<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function cardData($year)
    {
        // $sdate = $month . '-01';
        // $edate = date("Y-m-t", strtotime($sdate));
        $sdate = (int)$year - 1 . '-10-01';
        $edate = (int)$year . '-09-30';

        $sql = "SELECT 
                COUNT(DISTINCT supplier_id) AS supplier_num,
                SUM(debt_total) AS debt_total,
                SUM(CASE WHEN (debt_status='2') THEN debt_total END) AS paid,
                SUM(CASE WHEN (debt_status='4') THEN debt_total END) AS zero,
                SUM(CASE WHEN (debt_status IN ('0', '1')) THEN debt_total END) AS arrear
                FROM nrhosp_acc_debt
                WHERE (debt_status <> '3')
                AND (debt_date BETWEEN :sdate AND :edate)";

        return \DB::select($sql, [
            'sdate' => '2020-10-01',
            'edate' => '2021-09-30',
        ]);
    }

    
    public function sumMonth($year)
    {
        $sdate = (int)$year - 1 . '-10-01';
        $edate = (int)$year . '-09-30';

        $sql = "SELECT CONCAT(YEAR(debt_date),'-',MONTH(debt_date)) AS yyyymm, 
                SUM(CASE WHEN (debt_status IN ('0','1')) THEN debt_total END) AS debt,
                SUM(CASE WHEN (debt_status IN ('2')) THEN debt_total END) AS paid,
                SUM(CASE WHEN (debt_status IN ('4')) THEN debt_total END) AS setzero

                FROM nrhosp_acc_debt d 
                WHERE (debt_date BETWEEN ? AND ?)
                GROUP BY CONCAT(YEAR(debt_date),'-',MONTH(debt_date))
                ORDER BY CONCAT(YEAR(debt_date),'-',MONTH(debt_date))";

        return \DB::select($sql, [$sdate, $edate]);
    }

    public function sumYear($year)
    {
        $syear = (int)$year - 2;
        $eyear = $year;

        $sql = "SELECT YEAR(debt_date) AS yyyy, 
                SUM(CASE WHEN (debt_status IN ('0','1')) THEN debt_total END) AS debt,
                SUM(CASE WHEN (debt_status IN ('2')) THEN debt_total END) AS paid,
                SUM(CASE WHEN (debt_status IN ('4')) THEN debt_total END) AS setzero
                FROM nrhosp_acc_debt d 
                WHERE (YEAR(debt_date) BETWEEN ? AND ?)
                GROUP BY YEAR(debt_date)
                ORDER BY YEAR(debt_date)";

        return \DB::select($sql, [$syear, $eyear]);
    }
}
