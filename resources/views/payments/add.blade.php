@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            สร้างรายการตัดจ่ายหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">สร้างรายการตัดจ่ายหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="paymentCtrl" ng-init="initData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">สร้างรายการตัดจ่ายหนี้</h3>
                    </div>

                    <form id="frmNewPayment" name="frmNewPayment" method="post" action="{{ url('/payment/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">

                                <!-- Left Column -->
                                <div class="col-md-12">

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.creditor_id.$error.required }">
                                        <label>เจ้าหนี้ :</label>
                                        <select id="creditor_id" 
                                                name="creditor_id"
                                                ng-model="payment.creditor_id"
                                                ng-change="clearSupplierApproveData()"
                                                class="form-control select2"
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="0" required>
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($creditors as $creditor)

                                                <option value="{{ $creditor->supplier_id }}">
                                                    {{ $creditor->supplier_name }}
                                                </option>

                                            @endforeach
                                            
                                        </select>
                                        <div class="help-block" ng-show="frmNewPayment.creditor_id.$error.required">
                                            กรุณาเลือกเจ้าหนี้
                                        </div>
                                    </div>
                                </div>

                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.paid_doc_no.$error.required }">
                                        <label>เลขที่ บค. :</label>
                                        <input  type="text" 
                                                id="paid_doc_no" .
                                                name="paid_doc_no" 
                                                ng-model="payment.paid_doc_no" 
                                                class="form-control"
                                                tabindex="4" required>
                                        <div class="help-block" ng-show="frmNewPayment.paid_doc_no.$error.required">
                                            กรุณาระบุเลขที่ บค.
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.cheque_no.$error.required }">
                                        <label>เลขที่เช็ค :</label>
                                        <input  type="text" 
                                                id="cheque_no" 
                                                name="cheque_no" 
                                                ng-model="payment.cheque_no" 
                                                class="form-control"
                                                tabindex="8" required>
                                        <div class="help-block" ng-show="frmNewPayment.cheque_no.$error.required">
                                            กรุณาระบุเลขที่เช็ค
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.bank_acc_id.$error.required }">
                                        <label>ธนาคาร :</label>
                                        <select id="bank_acc_id" 
                                                name="bank_acc_id"
                                                ng-model="payment.bank_acc_id"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2" required>
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($banks as $bank)

                                                <option value="{{ $bank->bank_acc_id }}">
                                                    {{ $bank->bank_acc_no. '-' .$bank->bank_acc_name }}
                                                </option>

                                            @endforeach
                                            
                                        </select>
                                        <div class="help-block" ng-show="frmNewPayment.bank_acc_id.$error.required">
                                            กรุณาเลือกประเภทงบประมาณ
                                        </div>
                                    </div>

                                </div><!-- /.col -->
                                <!-- Left Column -->

                                <!-- Right Column -->
                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.paid_date.$error.required }">
                                        <label>วันที่ บค. :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="paid_date" 
                                                    name="paid_date" 
                                                    ng-model="payment.paid_date" 
                                                    class="form-control pull-right"
                                                    tabindex="1" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmNewPayment.paid_date.$error.required">
                                            กรุณาเลือกวันที่ บค.
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.cheque_date.$error.required }">
                                        <label>วันที่เช็ค :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="cheque_date" 
                                                    name="cheque_date" 
                                                    ng-model="payment.cheque_date" 
                                                    class="form-control pull-right"
                                                    tabindex="5" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmNewPayment.cheque_date.$error.required">
                                            กรุณาเลือกวันที่เช็ค
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewPayment.cheque_receiver.$error.required }">
                                        <label>ผู้รับเช็ค :</label>
                                        <input  type="text" 
                                                id="cheque_receiver" 
                                                name="cheque_receiver" 
                                                ng-model="payment.cheque_receiver" 
                                                class="form-control"
                                                tabindex="8" required>
                                        <div class="help-block" ng-show="frmNewPayment.cheque_receiver.$error.required">
                                            กรุณาระบุผู้รับเช็ค
                                        </div>
                                    </div>
                                    
                                </div><!-- /.col -->
                                <!-- Right Column -->

                            </div><!-- /.row -->
                            
                            <!-- Tab Component -->
                            <ul  class="nav nav-tabs">
                                <li class="active">
                                    <a  href="#1a" data-toggle="tab">รายการหนี้</a>
                                </li>
                            </ul>

                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a" style="padding: 10px;">

                                    <div class="col-md-12">
                                        <a class="btn btn-primary" ng-click="popupApproveSelection($event)">เพิ่ม</a>
                                        <a class="btn btn-danger" ng-click="removeSupplierApprove()">ลบ</a>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" style="font-size: 12px; margin-top: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 3%; text-align: center;">#</th>
                                                        <th style="width: 5%; text-align: center;">รหัส</th>
                                                        <th style="width: 10%; text-align: center;">เลขที่ขออนุมัติ</th>
                                                        <th style="width: 10%; text-align: center;">วันที่ขออนุมัติ</th>
                                                        <th style="text-align: left;">รายการหนี้</th>
                                                        <th style="width: 8%; text-align: center;">ฐานภาษี</th>
                                                        <th style="width: 8%; text-align: center;">ยอดหนี้สุทธิ</th>
                                                        <th style="width: 8%; text-align: center;">ภาษีหัก ณ ที่จ่าย</th>
                                                        <th style="width: 8%; text-align: center;">ยอดเช็ค</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="(index, approvement) in supplierApproveData">
                                                        <td class="text-center">
                                                            <input
                                                                type="checkbox" 
                                                                ng-click="addSupplierApproveToRemove($event, approvement)"
                                                            >
                                                        </td>
                                                        <td class="text-center">@{{ approvement.app_id }}</td>
                                                        <td class="text-center">@{{ approvement.app_doc_no }}</td>
                                                        <td class="text-center">@{{ approvement.app_date | thdate }}</td>
                                                        <td>
                                                            <ul class="tag__list">
                                                                <li ng-repeat="(index, detail) in approvement.app_detail">
                                                                    <a href="#" ng-click="showApproveDebtDetail($event, detail.debt_id)">
                                                                        <span class="label label-info">@{{ detail.debt_id }}</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td class="text-right">@{{ approvement.amount | number:2 }}</td>
                                                        <td class="text-right">@{{ approvement.net_total | number:2 }}</td>
                                                        <td class="text-right">@{{ approvement.net_amt | number:2 }}</td>
                                                        <td class="text-right">@{{ approvement.cheque | number:2 }}</td>
                                                    </tr>   
                                                </tbody>
                                            </table>
                                        </div>

                                        <hr style="margin: 0; margin-bottom: 10px;">
                                        
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-group" ng-class="{ 'has-error': frmNewPayment.budget_id.$error.required }">
                                                <label>ประเภทงบประมาณ :</label>
                                                <select id="budget_id" 
                                                        name="budget_id"
                                                        ng-model="payment.budget_id"
                                                        class="form-control select2" 
                                                        style="width: 100%; font-size: 12px;"
                                                        tabindex="2" required>
                                                    <option value="" selected="selected">-- กรุณาเลือก --</option>

                                                    @foreach($budgets as $budget)

                                                        <option value="{{ $budget->budget_id }}">
                                                            {{ $budget->budget_name }}
                                                        </option>

                                                    @endforeach
                                                    
                                                </select>
                                                <div class="help-block" ng-show="frmNewPayment.budget_id.$error.required">
                                                    กรุณาเลือกประเภทงบประมาณ
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>หมายเหตุ :</label>
                                                <textarea class="form-control"></textarea>
                                            </div>

                                            <span style="margin: 10px 5px; font-weight: bold;">
                                                (@{{ payment.totalstr }})
                                            </span>
                                        </div><!-- Left Column -->
                                        
                                        <!-- Right Column -->
                                        <div class="col-md-6">                                            
                                            <div class="form-group col-md-6">
                                                <label>ฐานภาษี :</label>
                                                <input type="text" 
                                                        id="net_val" 
                                                        name="net_val"
                                                        ng-model="payment.net_val" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>                                            
                                            <div class="form-group col-md-6">
                                                <label>ยอดหนี้สุทธิ :</label>
                                                <input type="text" 
                                                        id="net_total" 
                                                        name="net_total"
                                                        ng-model="payment.net_total" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ส่วนลด :</label>
                                                <input type="text" 
                                                        id="discount" 
                                                        name="discount"
                                                        ng-model="payment.discount" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ค่าปรับ :</label>
                                                <input type="text" 
                                                        id="fine" 
                                                        name="fine"
                                                        ng-model="payment.fine" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ภาษีหัก ณ ที่จ่าย :</label>
                                                <input type="text" 
                                                        id="net_amt" 
                                                        name="net_amt"
                                                        ng-model="payment.net_amt" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ยอดจ่าย :</label>
                                                <input type="text" 
                                                        id="total" 
                                                        name="total"
                                                        ng-model="payment.total" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                        </div><!-- Right Column -->

                                    </div><!-- /.col-md-12 -->

                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                            <!-- Tab Component -->
                            
                        </div><!-- /.box-body -->

                        <div class="box-footer clearfix">
                            <button ng-click="store($event, frmNewPayment)" class="btn btn-success pull-right">
                                บันทึก
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

                <!-- Modal -->
                @include('payments._approve-modal')

                @include('payments._debt-detail-modal')
                <!-- Modal -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()

            $('#paid_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
            
            $('#cheque_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection