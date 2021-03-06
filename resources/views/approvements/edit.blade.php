@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขรายการขออนุมัติเบิก-จ่ายหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/approve/list') }}">รายการขออนุมัติเบิก-จ่ายหนี้</a></li>
            <li class="breadcrumb-item active">แก้ไขรายการขออนุมัติเบิก-จ่ายหนี้</li>
            <li class="breadcrumb-item active">{{ $approvement->app_id }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="approveCtrl" ng-init="initData({{ $approvement }})">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">แก้ไขรายการขออนุมัติเบิก-จ่ายหนี้ [เลขที่ {{ $approvement->app_id }}]</h3>
                    </div>

                    <form id="frmEditApprove" name="frmEditApprove" method="post" action="{{ url('/approve/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">                                

                                    <div class="form-group" ng-class="{ 'has-error': frmEditApprove.creditor_id.$error.required }">
                                        <label>เจ้าหนี้ :</label>
                                        <select id="creditor_id" 
                                                name="creditor_id"
                                                ng-model="approve.creditor_id"
                                                ng-change="clearSupplierDebtData()"
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
                                        <div class="help-block" ng-show="frmEditApprove.creditor_id.$error.required">
                                            กรุณาเลือกเจ้าหนี้
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    
                                    <div class="form-group" ng-class="{ 'has-error': frmEditApprove.app_doc_no.$error.required }">
                                        <label>เลขที่ขออนุมัติ :</label>
                                        <input  type="text" 
                                                id="app_doc_no" .
                                                name="app_doc_no" 
                                                ng-model="approve.app_doc_no" 
                                                class="form-control"
                                                tabindex="4" required>
                                        <div class="help-block" ng-show="frmEditApprove.app_doc_no.$error.required">
                                            กรุณาระบุเลขที่ขออนุมัติ
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmEditApprove.app_recdoc_no.$error.required }">
                                        <label>เลขที่รับเอกสาร :</label>
                                        <input  type="text" 
                                                id="app_recdoc_no" 
                                                name="app_recdoc_no" 
                                                ng-model="approve.app_recdoc_no" 
                                                class="form-control"
                                                tabindex="8" required>
                                        <div class="help-block" ng-show="frmEditApprove.app_recdoc_no.$error.required">
                                            กรุณาระบุเลขที่รับเอกสาร
                                        </div>
                                    </div>

                                </div><!-- /.col -->

                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{ 'has-error': frmEditApprove.app_date.$error.required }">
                                        <label>วันที่ขออนุมัติ :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="app_date" 
                                                    name="app_date" 
                                                    ng-model="approve.app_date" 
                                                    class="form-control pull-right"
                                                    tabindex="1" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmEditApprove.app_date.$error.required">
                                            กรุณาเลือกวันที่ขออนุมัติ
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmEditApprove.app_recdoc_date.$error.required }">
                                        <label>วันที่รับเอกสาร :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="app_recdoc_date" 
                                                    name="app_recdoc_date" 
                                                    ng-model="approve.app_recdoc_date" 
                                                    class="form-control pull-right"
                                                    tabindex="5" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmEditApprove.app_recdoc_date.$error.required">
                                            กรุณาเลือกวันที่รับเอกสาร
                                        </div>
                                    </div>
                                    
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                    
                            <ul  class="nav nav-tabs">
                                <li class="active">
                                    <a  href="#1a" data-toggle="tab">รายการหนี้</a>
                                </li>
                            </ul>

                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a" style="padding: 10px;">

                                    <div class="col-md-12">       
                                        <a class="btn btn-primary" ng-click="showSupplierDebtList($event)">เพิ่ม</a>
                                        <a class="btn btn-danger" ng-click="removeSupplierDebt()">ลบ</a>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" style="font-size: 12px; margin-top: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 3%; text-align: center;">#</th>
                                                        <th style="width: 6%; text-align: center;">รหัส</th>
                                                        <th style="width: 8%; text-align: center;">วันที่ลงบัญชี</th>
                                                        <th style="width: 15%; text-align: center;">เลขที่ใบส่งของ</th>
                                                        <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th>
                                                        <th style="text-align: left;">ประเภทหนี้</th>
                                                        <th style="width: 7%; text-align: right;">ยอดหนี้</th>
                                                        <th style="width: 7%; text-align: right;">ภาษี</th>
                                                        <th style="width: 7%; text-align: right;">สุทธิ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="(index, debt) in supplierDebtData">
                                                        <td class="text-center">
                                                            <input
                                                                type="checkbox" 
                                                                ng-click="addSupplierDebtToRemove($event, debt)"
                                                            >
                                                        </td>
                                                        <td class="text-center">@{{ debt.debt_id }}</td>
                                                        <td class="text-center">@{{ debt.debt_date | thdate }}</td>
                                                        <td class="text-center">@{{ debt.deliver_no }}</td>
                                                        <td class="text-center">@{{ debt.deliver_date | thdate }}</td>
                                                        <td>@{{ debttypes[debt.debt_type_id] }}</td>
                                                        <td class="text-right">@{{ debt.debt_amount | number:2 }}</td>
                                                        <td class="text-right">@{{ debt.debt_vat | number:2 }}</td>
                                                        <td class="text-right">@{{ debt.debt_total | number:2 }}</td>
                                                    </tr>   
                                                </tbody>
                                            </table>
                                        </div>

                                        <hr style="margin: 0; margin-bottom: 10px;">

                                        <div class="col-md-6">
                                            <div class="form-group col-md-6" ng-class="{ 'has-error': frmEditApprove.budget_id.$error.required }">
                                                <label>ประเภทงบประมาณ :</label>
                                                <select id="budget_id" 
                                                        name="budget_id"
                                                        ng-model="approve.budget_id"
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
                                                <div class="help-block" ng-show="frmEditApprove.budget_id.$error.required">
                                                    กรุณาเลือกประเภทงบประมาณ
                                                </div>
                                            </div>

                                            <span class="col-md-12" style="margin: 10px 5px; font-weight: bold;">
                                                (@{{ approve.cheque_str }})
                                            </span>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div
                                                class="alert alert-danger"
                                                style="padding: 5px; margin-bottom: 10px;"
                                            >
                                                <span><b>หมายเหตุ :</b> ไม่ต้องใส่เครื่องหมาย Comma (,)</span>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>ฐานภาษี :</label>
                                                <input type="text" 
                                                        id="net_val" 
                                                        name="net_val"
                                                        ng-model="approve.net_val" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>                                            
                                            <div class="form-group col-md-6">
                                                <label>ภาษีมูลค่าเพิ่ม :</label>
                                                <input type="text" 
                                                        id="vatamt" 
                                                        name="vatamt"
                                                        ng-model="approve.vatamt" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ส่วนลด :</label>
                                                <input type="text" 
                                                        id="discount" 
                                                        name="discount"
                                                        ng-model="approve.discount" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ค่าปรับ :</label>
                                                <input type="text" 
                                                        id="fine" 
                                                        name="fine"
                                                        ng-model="approve.fine" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>VAT (%) :</label>
                                                <input type="text" 
                                                        id="vatrate" 
                                                        name="vatrate"
                                                        ng-model="approve.vatrate" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ภาษีหัก ณ ที่จ่าย :</label>
                                                <input type="text" 
                                                        id="tax_val" 
                                                        name="tax_val"
                                                        ng-model="approve.tax_val" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>                                            
                                            <div class="form-group col-md-6">
                                                <label>ยอดสุทธิ :</label>
                                                <input type="text" 
                                                        id="net_total" 
                                                        name="net_total"
                                                        ng-model="approve.net_total" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>ยอดจ่ายเช็ค :</label>
                                                <input type="text" 
                                                        id="cheque" 
                                                        name="cheque"
                                                        ng-model="approve.cheque" 
                                                        class="form-control text-right"
                                                        tabindex="1" required>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                            
                        </div><!-- /.box-body -->

                        <div class="box-footer clearfix">
                            <button ng-click="update($event, frmEditApprove)" class="btn btn-warning pull-right">
                                แก้ไข
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

                <!-- Modal -->
                @include('approvements._debt-modal')
                <!-- Modal -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()

            // $('#app_date').datepicker({
            //     autoclose: true,
            //     language: 'th',
            //     format: 'dd/mm/yyyy',
            //     thaiyear: true,
            //     orientation: 'bottom'
            // });

            // $('#app_recdoc_date').datepicker({
            //     autoclose: true,
            //     language: 'th',
            //     format: 'dd/mm/yyyy',
            //     thaiyear: true,
            //     orientation: 'bottom'
            // });
        });
    </script>

@endsection