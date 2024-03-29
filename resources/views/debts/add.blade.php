@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มข้อมูลหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มข้อมูลหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="debtCtrl">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">เพิ่มข้อมูลหนี้</h3>
                            </div>
                            <div class="col-md-6">
                                <button
                                    type="button"
                                    class="btn btn-primary pull-right"
                                    ng-click="showTmpDebtsList();"
                                >
                                    <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                    ตั้งหนี้จากรายการส่งเบิกเงิน
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="frmNewDebt" name="frmNewDebt" method="post" action="{{ url('/debt/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.supplier_id.$error.required }">
                                        <label class="control-label">เจ้าหนี้ :</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="supplier_name" 
                                                name="supplier_name"
                                                ng-model="debt.supplier_name" 
                                                class="form-control"
                                                tabindex="2"
                                            />
                                            <input
                                                type="hidden"
                                                id="supplier_id" 
                                                name="supplier_id"
                                                ng-model="debt.supplier_id"
                                                required
                                            />
                                            <span class="input-group-btn">
                                                <button
                                                    type="button"
                                                    id="search-btn"
                                                    name="search"
                                                    ng-click="showSuppliersList();"
                                                    class="btn btn-primary"
                                                >
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="help-block" ng-show="frmNewDebt.supplier_id.$error.required">
                                            กรุณาเลือกเจ้าหนี้
                                        </div>
                                    </div>                                

                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_type_id.$error.required }">
                                        <label>ประเภทหนี้ :</label>
                                        <select id="debt_type_id" 
                                                name="debt_type_id"
                                                ng-model="debt.debt_type_id" 
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2" required>
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($debttypes as $debttype)

                                                <option value="{{ $debttype->debt_type_id }}">
                                                    {{ $debttype->debt_type_name }}
                                                </option>

                                            @endforeach
                                            
                                        </select>
                                        <div class="help-block" ng-show="frmNewDebt.debt_type_id.$error.required">
                                            กรุณาเลือกประเภทหนี้
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>เลขที่ PO :</label>
                                        <div
                                            id="po_no" .
                                            name="po_no"
                                            class="form-control"
                                            tabindex="4"
                                            readonly
                                        >
                                            @{{ debt.po_no }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>เลขที่รับหนังสือ :</label>
                                        <input  type="text" 
                                                id="debt_doc_recno" .
                                                name="debt_doc_recno" 
                                                ng-model="debt.debt_doc_recno" 
                                                class="form-control"
                                                tabindex="4">
                                    </div>

                                    <div class="form-group">
                                        <label>เลขที่หนังสือส่งเบิกเงิน (พัสดุ) :</label>
                                        <input  type="text" 
                                                id="debt_doc_no" 
                                                name="debt_doc_no" 
                                                ng-model="debt.debt_doc_no" 
                                                class="form-control"
                                                tabindex="6">
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.deliver_no.$error.required }">
                                        <label>เลขที่ใบส่งของ/ใบกำกับภาษี :</label>
                                        <input  type="text" 
                                                id="deliver_no" 
                                                name="deliver_no" 
                                                ng-model="debt.deliver_no" 
                                                class="form-control"
                                                tabindex="8" required>
                                        <div class="help-block" ng-show="frmNewDebt.deliver_no.$error.required">
                                            กรุณาระบุเลขที่ใบส่งของ/ใบกำกับภาษี
                                        </div>
                                    </div>

                                </div><!-- /.col -->

                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_date.$error.required }">
                                        <label>วันที่ลงบัญชี :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="debt_date" 
                                                    name="debt_date" 
                                                    ng-model="debt.debt_date" 
                                                    class="form-control pull-right"
                                                    tabindex="1" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmNewDebt.debt_date.$error.required">
                                            กรุณาเลือกวันที่ลงบัญชี
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>รายการ :</label>
                                        <input  type="text" 
                                                id="debt_type_detail" 
                                                name="debt_type_detail" 
                                                ng-model="debt.debt_type_detail" 
                                                class="form-control"
                                                tabindex="3">
                                    </div>

                                    <div class="form-group">
                                        <label>วันที่ใบ PO :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <div 
                                                id="po_date"
                                                name="po_date"
                                                class="form-control"
                                                readonly
                                            >
                                                <span ng-show="debt.po_date">@{{ debt.po_date | thdate }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_doc_recdate.$error.required }">
                                        <label>วันที่รับหนังสือ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="debt_doc_recdate" 
                                                    name="debt_doc_recdate" 
                                                    ng-model="debt.debt_doc_recdate" 
                                                    class="form-control pull-right"
                                                    tabindex="5" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmNewDebt.debt_doc_recdate.$error.required">
                                            กรุณาเลือกวันที่รับหนังสือ
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>วันที่หนังสือส่งเบิกเงิน (พัสดุ) :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="debt_doc_date" 
                                                    name="debt_doc_date" 
                                                    ng-model="debt.debt_doc_date" 
                                                    class="form-control pull-right"
                                                    tabindex="7">
                                        </div><!-- /.input group -->
                                    </div>

                                    <div class="form-group" ng-class="{ 'has-error': frmNewDebt.deliver_date.$error.required }">
                                        <label>วันที่ใบส่งของ/ใบกำกับภาษี :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="deliver_date" 
                                                    name="deliver_date" 
                                                    ng-model="debt.deliver_date" 
                                                    class="form-control pull-right"
                                                    tabindex="9" required>
                                        </div><!-- /.input group -->
                                        <div class="help-block" ng-show="frmNewDebt.deliver_date.$error.required">
                                            กรุณาเลือกวันที่ใบส่งของ/ใบกำกับภาษี
                                        </div>
                                    </div>
                                    
                                </div><!-- /.col -->
                            </div><!-- /.row -->

                            <ul  class="nav nav-tabs">
                                <li class="active">
                                    <a  href="#1a" data-toggle="tab">ยอดหนี้</a>
                                </li>
                            </ul>

                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a" style="padding: 10px;">
                                    <div class="col-md-6">       
                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_amount.$error.required || frmNewDebt.debt_amount.$error.pattern }">
                                            <label>ยอดหนี้ :</label>
                                            <input  type="text" 
                                                    id="debt_amount" 
                                                    name="debt_amount" 
                                                    ng-model="debt.debt_amount"
                                                    class="form-control"
                                                    pattern="^[-+]?[0-9]*\.?[0-9]+$"
                                                    tabindex="10" required>
                                            <div class="help-block" ng-show="frmNewDebt.debt_amount.$error.required">
                                                กรุณาระบุยอดหนี้
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.debt_amount.$error.pattern">
                                                กรุณากรอกยอดหนี้เป็นตัวเลข
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_vat.$error.required || frmNewDebt.debt_vat.$error.pattern }">
                                            <label>จำนวนภาษี :</label>
                                            <input  type="text" 
                                                    id="debt_vat" 
                                                    name="debt_vat" 
                                                    ng-model="debt.debt_vat"
                                                    class="form-control"
                                                    pattern="^[-+]?[0-9]*\.?[0-9]+$"
                                                    tabindex="12" required>
                                            <div class="help-block" ng-show="frmNewDebt.debt_vat.$error.required">
                                                กรุณาระบุจำนวนภาษี
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.debt_vat.$error.pattern">
                                                กรุณากรอกจำนวนภาษีเป็นตัวเลข
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">       
                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_vatrate.$error.required || frmNewDebt.debt_vatrate.$error.pattern }">
                                            <label>VAT(%) :</label>
                                            <input  type="text" 
                                                    id="debt_vatrate" 
                                                    name="debt_vatrate" 
                                                    ng-model="debt.debt_vatrate"
                                                    ng-keyup="calculateVat(debt.debt_amount, debt.debt_vatrate)"
                                                    class="form-control"
                                                    pattern="^[-+]?[0-9]*\.?[0-9]+$"
                                                    tabindex="11" required>
                                            <div class="help-block" ng-show="frmNewDebt.debt_vatrate.$error.required">
                                                กรุณาระบุอัตราภาษี (%)
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.debt_vatrate.$error.pattern">
                                                กรุณากรอกอัตราภาษี (%) เป็นตัวเลข
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_total.$error.required || frmNewDebt.debt_total.$error.pattern }">
                                            <label>ยอดหนี้สุทธิ :</label>
                                            <input  type="text" 
                                                    id="debt_total" 
                                                    name="debt_total" 
                                                    ng-model="debt.debt_total"
                                                    class="form-control"
                                                    pattern="^[-+]?[0-9]*\.?[0-9]+$"
                                                    tabindex="13" required>
                                            <div class="help-block" ng-show="frmNewDebt.debt_total.$error.required">
                                                กรุณาระบุยอดหนี้สุทธิ
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.debt_total.$error.pattern">
                                                กรุณากรอกยอดหนี้สุทธิป็นตัวเลข
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul  class="nav nav-tabs">
                                <li class="active">
                                    <a  href="#1a" data-toggle="tab">เพิ่มเติม</a>
                                </li>
                            </ul>

                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a" style="padding: 10px;">
                                    <div class="col-md-6">       
                                        <div class="form-group">
                                            <label>ประจำเดือน :</label>
                                            <input  type="text" 
                                                    id="debt_month" 
                                                    name="debt_month"
                                                    ng-model="debt.debt_month"
                                                    class="form-control"
                                                    tabindex="14">
                                        </div>

                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.debt_year.$error.required || frmNewDebt.debt_year.$error.pattern }">
                                            <label>ปีงบประมาณ (พ.ศ.) :</label>
                                            <input  type="text" 
                                                    id="debt_year" 
                                                    name="debt_year" 
                                                    ng-model="debt.debt_year"
                                                    class="form-control"
                                                    pattern="[0-9]{4}"
                                                    tabindex="16" required>
                                            <div class="help-block" ng-show="frmNewDebt.debt_year.$error.required">
                                                กรุณาระบุปีงบประมาณ
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.debt_year.$error.pattern">
                                                กรุณาระบุปีงบประมาณเป็นตัวเลข 4 หลัก
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">       
                                        <div class="form-group" ng-class="{ 'has-error': frmNewDebt.doc_receive.$error.required }">
                                            <label>วันที่รับเอกสาร :</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input  type="text" 
                                                        id="doc_receive" 
                                                        name="doc_receive" 
                                                        ng-model="debt.doc_receive" 
                                                        class="form-control pull-right"
                                                        tabindex="15" required>
                                            </div>
                                            <div class="help-block" ng-show="frmNewDebt.doc_receive.$error.required">
                                                กรุณาเลือกวันที่รับเอกสาร
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>หมายเหตุ :</label>
                                            <input  type="text" 
                                                    id="debt_remark" 
                                                    name="debt_remark" 
                                                    ng-model="debt.debt_remark" 
                                                    class="form-control"
                                                    tabindex="17">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @include('shared._tmp-debts-list')
                            @include('shared._suppliers-list')

                        </div><!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <button ng-click="store($event, frmNewDebt)" class="btn btn-success pull-right">
                                บันทึก
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            $('#debt_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });

            $('#debt_doc_recdate').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });

            $('#debt_doc_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });

            $('#deliver_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });

            $('#doc_receive').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection