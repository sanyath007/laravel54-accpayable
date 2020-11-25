@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการขออนุมัติเบิก-จ่ายหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการขออนุมัติเบิก-จ่ายหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="approveCtrl" ng-init="getData()">

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
                                            class="form-control pull-right"
                                            id="approveFromDate"
                                        >
                                    </div>
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ถึงวันที่:</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            class="form-control pull-right"
                                            id="approveToDate"
                                        >
                                    </div>
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label>เจ้าหนี้</label>
                                    <input type="text" id="searchKey" ng-keyup="getData($event)" class="form-control">
                                </div><!-- /.form group -->
                            </div>
                            <div class="col-md-6" style="height: 60px; display: flex; align-items: flex-end;">
                                <div class="checkbox">
                                    <label>
                                        <input
                                            type="checkbox"
                                            id="showall"
                                            name="showall"
                                            ng-click="getDebtData('/approve/search/0')"
                                        > แสดงทั้งหมด
                                    </label>
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                  
                        <div class="box-footer">
                            <a href="{{ url('/approve/add') }}" class="btn btn-success"> สร้างรายการขออนุมัติ</a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">รายการขออนุมัติเบิก-จ่ายหนี้</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 5%; text-align: center;">รหัส</th>
                                    <th style="width: 8%; text-align: center;">เลขที่ขออนุมัติ</th>
                                    <th style="width: 8%; text-align: center;">วันที่ขออนุมัติ</th>
                                    <th style="text-align: left;">สั่งจ่าย</th>
                                    <th style="width: 8%; text-align: center;">ฐานภาษี</th>
                                    <th style="width: 8%; text-align: center;">ภาษีหัก ณ ที่จ่าย</th>
                                    <th style="width: 8%; text-align: center;">ยอดสุทธิ</th>
                                    <th style="width: 8%; text-align: center;">ยอดเช็ค</th>
                                    <!-- <th style="width: 5%; text-align: center;">สถานะ</th> -->
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, approvement) in approvements">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ approvement.app_id }}</td>
                                    <td style="text-align: center;">@{{ approvement.app_doc_no }}</td>
                                    <td style="text-align: center;">@{{ approvement.app_date | thdate }}</td>
                                    <td style="text-align: left;">@{{ approvement.pay_to }}</td>
                                    <td style="text-align: right;">@{{ approvement.net_val | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ approvement.tax_val | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ approvement.net_total | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ approvement.cheque | number: 2 }}</td>
                                    <!-- <td style="text-align: center;">@{{ approvement.app_stat }}</td> -->
                                    <td style="text-align: center;">
                                        <a
                                            ng-click="edit(approvement.app_id)"
                                            class="btn btn-warning btn-xs"
                                            title="แก้ไขรายการ"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a
                                            ng-click="popupApproveDebtList(approvement)"
                                            class="btn btn-primary btn-xs"
                                            title="ดูรายละเอียด"
                                        >
                                            <i class="fa fa-search"></i>
                                        </a>

                                        <a
                                            ng-click="popupCancelForm(approvement.app_id)"
                                            class="btn btn-default btn-xs"
                                            title="ยกเลิกรายการ"
                                        >
                                            <i class="fa fa-times"></i>
                                        </a>

                                        @if(Auth::user()->person_id == '1300200009261')

                                            <a
                                                ng-click="delete(approvement.app_id)"
                                                class="btn btn-danger btn-xs"
                                                title="ลบรายการ"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </a>

                                        @endif
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <div class="col-md-6" style="font-size: 12px;">
                            Total @{{ pager.total | currency : "" : 0 }} รายการ
                        </div>
                        
                        <ul class="pagination pagination-sm no-margin pull-right">

                            <li ng-if="pager.current_page !== 1">
                                <a ng-click="getDataWithURL(pager.path + '?page=1')" aria-label="Previous">
                                    <span aria-hidden="true">First</span>
                                </a>
                            </li>
                        
                            <li ng-class="{'disabled': (pager.current_page==1)}">
                                <a ng-click="getDataWithURL(pager.prev_page_url)" aria-label="Prev">
                                    <span aria-hidden="true">Prev</span>
                                </a>
                            </li>
                           
                            <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                <a href="@{{ pager.url(pager.current_page + 10) }}">
                                    ...
                                </a>
                            </li>
                        
                            <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                <a ng-click="getDataWithURL(pager.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>

                            <li ng-if="pager.current_page !== pager.last_page">
                                <a ng-click="getDataWithURL(pager.path+ '?page=' +pager.last_page)" aria-label="Previous">
                                    <span aria-hidden="true">Last</span>
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.box-footer -->

                </div><!-- /.box -->

                <!-- Modal -->
                @include('approvements._cancel-modal')

                @include('approvements._debt-list-modal')
                <!-- Modal -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()

            $('#approveFromDate').datepicker({
                autoclose: true,
                orientation: 'bottom',
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            }, function(e) {
                console.log(e);
            });
        });
    </script>
@endsection