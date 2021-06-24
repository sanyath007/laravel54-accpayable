<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Approvement;
use App\Models\Creditor;
use App\Models\Budget;
use App\Models\Bank;
use App\Models\Debt;
use App\Models\DebtType;

class PaymentController extends Controller
{
    /** สถานะ 0=รอดำเนินการ,1=ขออนุมัติ,2=ชำระเงินแล้ว,3=ยกเลิก */

    public function index()
    {
        return view('payments.list');
    }

    public function search($dataType, $sdate, $edate, $searchKey, $showall)
    {
        $conditions = [];

        if($showall == 0) {
            if($sdate != 0 && $edate != 0) {
                array_push($conditions, ['paid_date', '>=', $sdate]);
                array_push($conditions, ['paid_date', '<=', $edate]);
            }
        }

        if($searchKey !== '0') array_push($conditions, ['pay_to', 'like', '%'.$searchKey.'%']);

        if($conditions == '0') {
            $payments = Payment::where(['paid_stat' => 'Y']);
        } else {
            $payments = Payment::where(['paid_stat' => 'Y'])
                            ->where($conditions);
        }

        if($dataType == 'excel') {
            $fileName = 'payment-list-' . date('YmdHis') . '.xlsx';

            $data = $payments->get();

            return \Excel::create($fileName, function($excel) use ($data, $sdate, $edate) {
                $excel->sheet('sheet1', function($sheet) use ($data, $sdate, $edate)
                {    
                    /** Use view */
                    $sheet->loadView('exports.payment-list-excel', [
                        'payments' => $data,
                        'sdate' => $sdate,
                        'edate' => $edate
                    ]);                
                });
            })->download();
        } else {
            return [
                'payments' => $payments->paginate(20),
            ];
        }
    }

    private function generateAutoId()
    {
        $payment = \DB::table('nrhosp_acc_payment')
                        ->select('payment_id')
                        ->orderBy('payment_id', 'DESC')
                        ->first();

        $startId = 'FN'.substr((date('Y') + 543), 2);
        $tmpLastId =  ((int)(substr($payment->payment_id, 4))) + 1;
        $lastId = $startId.sprintf("%'.07d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
        return view('payments.add', [
            'creditors' => Creditor::all(),
            'banks'	    => Bank::all(),
            'budgets'	=> Budget::all(),
        ]);
    }

    public function store(Request $req)
    {
        $payment = new Payment();
        $payment->payment_id = $this->generateAutoId();
        $payment->paid_date = $req['paid_date'];
        $payment->app_id = ''; // Not null
        $payment->paid_doc_no = $req['paid_doc_no'];
        $payment->cheque_no = $req['cheque_no'];
        $payment->cheque_date = $req['cheque_date'];
        $payment->bank_acc_id = $req['bank_acc_id'];

        $payment->supplier_id = $req['creditor_id'];
        $payment->pay_to = $req['pay_to'];
        $payment->cheque_receiver = $req['cheque_receiver'];
        $payment->budget_id = $req['budget_id'];
        $payment->tax_type_id = '01'; // Not null
        $payment->paid_num = $req['paid_num']; // จำนวนรายการหนี้
        $payment->remark = $req['remark'];

        $payment->net_val = $req['net_val']; // ฐานภาษี
        $payment->net_amt = $req['net_amt']; // ภาษีหัก ณ ที่จ่าย
        $payment->net_total = $req['net_total']; // ยอดหนี้สุทธิ
        $payment->discount = $req['discount']; // ส่วนลด
        $payment->fine = $req['fine'];  // ค่าปรับ
        $payment->remain = $req['remain'];  //ค้าง
        $payment->paid_amt = $req['paid_amt']; //ยอดจ่าย
        $payment->total = $req['total']; //ยอดจ่าย
        $payment->totalstr = $req['totalstr']; //ยอดจ่าย (ตัวอักษร)
        /** User info */
        $payment->computer = ''; // Not null
        $payment->paid_empid = $req['cr_userid']; // Not null
        $payment->cr_userid = $req['cr_userid'];
        $payment->cr_date = date("Y-m-d H:i:s");
        $payment->chg_userid = $req['chg_userid'];
        $payment->chg_date = date("Y-m-d H:i:s");
        /** Status */
        $payment->account_confirm = 'Y';
        $payment->paid_stat = 'Y';

        if($payment->save()) {
            $index = 0;
            foreach ($req['app_debts'] as $debt) {
                /** Added Payment Detail */
                $detail = new PaymentDetail();
                $detail->payment_id = $payment->payment_id;
                $detail->debt_id = $debt['debt_id'];
                $detail->seq_no = ++$index;
                $detail->app_id = $debt['app_id'];
                $detail->rcpamt = $debt['debt_total'];  // ยอดหนี้สุทธิ
                $detail->net_val = $debt['debt_amount']; // ฐานภาษี
                $detail->vat1_amt = number_format($debt['debt_amount']*0.01, 2); // ภาษีหัก ณ ที่จ่าย
                $detail->cheque_amt = $debt['debt_total'];  // ยอดจ่ายเช็ค
                $detail->save();
                
                /** Updated debt status to 2=ชำระเงินแล้ว */
                Debt::find($debt['debt_id'])->update(['debt_status' => 2]);
                /** Updated approvement status to 2=ชำระเงินแล้ว */
                Approvement::find($debt['app_id'])->update(['app_stat' => 2]);
            }

            return [
                "status"    => "success",
                "message"   => "Insert success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Insert failed.",
            ];
        }
    }

    public function detail($appid)
    {   
        $debttypes = [];

        foreach (DebtType::all()->toArray() as $type) {
            $debttypes[$type['debt_type_id']] = $type['debt_type_name'];
        }        

        return [
            'paymentdetails' => PaymentDetail::where(['payment_id' => $appid])
                                                ->with('debt')
                                                ->orderBy('seq_no', 'ASC')
                                                ->get(),
            'debttypes' => $debttypes,
        ];
    }
}
