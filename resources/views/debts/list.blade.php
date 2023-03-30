@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการรับหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการรับหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="debtCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ระหว่างวันที่:</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="debtFromDate"
                                            class="form-control pull-right"
                                            autocomplete="off"
                                        />
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- /.col-md-6 -->
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ถึงวันที่:</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="debtToDate"
                                            class="form-control pull-right"
                                            autocomplete="off"
                                        />
                                    </div><!-- /.input group -->
                                </div><!-- /.form group -->
                            </div><!-- /.col-md-6 -->

                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input
                                            type="checkbox"
                                            id="showall"
                                            name="showall"
                                            checked="checked"
                                            ng-click="getData()"> แสดงทั้งหมด
                                    </label>
                                </div>
                            </div><!-- /.col-md-6 -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>เจ้าหนี้</label>
                                    <select
                                        id="cboSupplier"
                                        name="cboSupplier"
                                        ng-model="cboSupplier"
                                        ng-change="getData()"
                                        class="form-control select2"
                                        style="width: 100%; font-size: 12px;"
                                    >

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>
                                        @foreach($creditors as $creditor)

                                            <option value="{{ $creditor->supplier_id }}">
                                                {{ $creditor->supplier_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div><!-- /.col-md-6 -->
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <a ng-click="add($event)" class="btn btn-primary">
                                บันทึกรับหนี้
                            </a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">รายการรับหนี้</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">


                        <!-- รายการรอดำเนินการ -->
                        <div class="table-responsive">

                            <!-- <div class="form-group pull-right">
                                <input  type="text" 
                                        id="table_search" 
                                        name="table_search"
                                        ng-model="searchKeyword"
                                        class="form-control pull-right" 
                                        placeholder="ค้นหาเลขที่ใบส่งของ">                                       
                            </div> -->

                            <table class="table table-bordered table-striped" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th style="width: 3%; text-align: center;">#</th>
                                        <th style="width: 6%; text-align: center;">รหัสรายการ</th>
                                        <th style="width: 7%; text-align: center;">วันที่ลงบัญชี</th>
                                        <th style="width: 8%; text-align: center;">เลขที่ใบส่งของ</th>
                                        <th style="width: 7%; text-align: center;">วันที่ใบส่งของ</th>
                                        <th style="text-align: left;">เจ้าหนี้</th>
                                        <th style="text-align: left;">ประเภทหนี้</th>
                                        <th style="width: 7%; text-align: center;">ยอดหนี้</th>
                                        <th style="width: 7%; text-align: center;">ภาษี</th>
                                        <th style="width: 7%; text-align: center;">สุทธิ</th>
                                        <th style="width: 6%; text-align: center;">สถานะ</th>
                                        <th style="width: 10%; text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="(index, debt) in debts" ng-show="debts.length == 0">
                                        <td style="text-align: center;">@{{ index+pager.from }}</td>
                                        <td style="text-align: center;">@{{ debt.debt_id }}</td>
                                        <td style="text-align: center;">@{{ debt.debt_date | thdate }}</td>
                                        <td style="text-align: center;">@{{ debt.deliver_no }}</td>
                                        <td style="text-align: center;">@{{ debt.deliver_date | thdate }}</td>
                                        <td style="text-align: left;">@{{ debt.supplier_name }}</td>
                                        <td style="text-align: left;">@{{ debt.debttype.debt_type_name }}</td>
                                        <td style="text-align: right;">@{{ debt.debt_amount | number: 2 }}</td>
                                        <td style="text-align: right;">@{{ debt.debt_vat | number: 2 }}</td>
                                        <td style="text-align: right;">@{{ debt.debt_total | number: 2 }}</td>
                                        <td style="text-align: center;">
                                            <span class="label label-info" ng-show="paid.debt_status!=0">
                                                @{{ (debt.debt_status==1) ? 'ขออนุมัติ' : 
                                                    (debt.debt_status==2) ? 'ชำระเงินแล้ว' : 
                                                    (debt.debt_status==3) ? 'ยกเลิก' : 'รอดำเนินการ' }}
                                            </span>
                                        </td>             
                                        <td style="text-align: center;">
                                            <a  ng-click="setzero(debt.debt_id)" 
                                                ng-show="(debt.debt_status==0)" 
                                                class="btn btn-primary btn-xs" 
                                                title="ลดหนี้ศูนย์">
                                                <i class="fa fa-credit-card"></i>
                                            </a>

                                            <a  ng-click="edit($event, debt.debt_id)" 
                                                ng-show="(debt.debt_status==0)" 
                                                class="btn btn-warning btn-xs"
                                                title="แก้ไขรายการ">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a  ng-click="delete(debt.debt_id)" 
                                                ng-show="(debt.debt_status==0)" 
                                                class="btn btn-danger btn-xs"
                                                title="ลบรายการ">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>             
                                    </tr>
                                    <tr ng-show="debts.length == 0">
                                        <td colspan="11" style="text-align: center; color: red;">
                                            -- ไม่พบข้อมูล --
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Box Footer with pagination -->
                        @include('debts._list-pagination')
                        <!-- Box Footer with pagination -->
                        
                    </div><!-- /.box-body -->

                    <!-- Loading (remove the following to stop the loading)-->
                    <div ng-show="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()

            $('#debtFromDate').datepicker({
                autoclose: true,
                orientation: 'bottom',
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
            
            $('#debtToDate').datepicker({
                autoclose: true,
                orientation: 'bottom',
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection