@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการตัดจ่ายหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการตัดจ่ายหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="paymentCtrl" ng-init="getData()">

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
                                            id="paymentFromDate"
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
                                            id="paymentToDate"
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
                            <a href="{{ url('/payment/add') }}" class="btn btn-success"> สร้างรายการตัดจ่ายหนี้</a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">รายการตัดจ่ายหนี้</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <!-- <th style="width: 5%; text-align: center;">รหัส</th> -->
                                    <th style="width: 10%; text-align: center;">เลขที่ บค.</th>
                                    <th style="width: 7%; text-align: center;">วันที่</th>
                                    <th style="width: 8%; text-align: center;">เลขที่เช็ค</th>
                                    <th style="width: 7%; text-align: center;">วันที่เช็ค</th>
                                    <th style="text-align: left;">สั่งจ่าย</th>
                                    <th style="width: 8%; text-align: center;">ฐานภาษี</th>
                                    <th style="width: 8%; text-align: center;">ณ ที่จ่าย</th>
                                    <th style="width: 8%; text-align: center;">ยอดสุทธิ</th>
                                    <!-- <th style="width: 5%; text-align: center;">สถานะ</th> -->
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, payment) in payments">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <!-- <td style="text-align: center;">@{{ payment.payment_id }}</td> -->
                                    <td style="text-align: center;">@{{ payment.paid_doc_no }}</td>
                                    <td style="text-align: center;">@{{ payment.paid_date | thdate }}</td>
                                    <td style="text-align: center;">@{{ payment.cheque_no }}</td>
                                    <td style="text-align: center;">@{{ payment.cheque_date | thdate }}</td>
                                    <td style="text-align: left;">@{{ payment.pay_to }}</td>
                                    <td style="text-align: right;">@{{ payment.net_val | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ payment.net_amt | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ payment.total | number: 2 }}</td>
                                    <!-- <td style="text-align: center;">@{{ payment.paid_stat }}</td> -->
                                    <td style="text-align: center;">
                                        <a
                                            ng-click="edit(payment.payment_id)"
                                            class="btn btn-warning btn-xs"
                                            title="แก้ไขรายการ"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a
                                            ng-click="popupPaymentDetail(payment)"
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
                                                ng-click="delete(payment.payment_id)"
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

                    <!-- Box Footer with pagination -->
                    @include('payments._list-pagination')
                    <!-- Box Footer with pagination -->

                </div><!-- /.box -->

                <!-- Modal -->
                @include('payments._list-detail-modal')
                <!-- Modal -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()

            $('#paymentFromDate').datepicker({
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