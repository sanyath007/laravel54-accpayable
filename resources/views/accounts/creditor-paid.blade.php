@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการเจ้าหนี้จ่ายชำระหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการเจ้าหนี้จ่ายชำระหนี้</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="accountCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>เจ้าหนี้</label>
                                        <select id="creditor" class="form-control select2" style="width: 100%; font-size: 12px;">

                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            @foreach($creditors as $creditor)

                                                <option value="{{ $creditor->supplier_id }}">
                                                    {{ $creditor->supplier_name }}
                                                </option>

                                            @endforeach
                                            
                                        </select>
                                    </div>
                                </div><!-- /.col-md-6 -->

                                <div class="col-md-6" style="margin-top: 20px;">
                                    <div class="checkbox">
                                        <label>
                                            <input
                                                type="checkbox"
                                                id="showall"
                                                name="showall"
                                                checked="checked"> แสดงทั้งหมด
                                        </label>
                                    </div>
                                </div><!-- /.col-md-6 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ระหว่างวันที่:</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="debtFromDate">
                                        </div>
                                    </div><!-- /.form group -->
                                </div><!-- /.col-md-6 -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ถึงวันที่:</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="debtToDate">
                                        </div>
                                    </div><!-- /.form group -->
                                </div><!-- /.col-md-6 -->

                            </div><!-- /.row -->
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button ng-click="getCreditorPaidData()" class="btn btn-info">
                                ค้นหา
                            </button>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">รายการเจ้าหนี้จ่ายชำระหนี้</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 5%; text-align: center;">รหัสรายการ</th>
                                    <th style="width: 5%; text-align: center;">วันที่รับเอกสาร</th>
                                    <th style="width: 5%; text-align: center;">วันที่ชำระ</th>
                                    <th style="width: 5%; text-align: center;">เลขที่เช็ค</th>
                                    <th style="width: 5%; text-align: center;">เลขที่เอกสาร</th>
                                    <th style="width: 12%; text-align: left;">ประเภทหนี้</th>
                                    <th style="width: 12%; text-align: left;">รายการ</th>
                                    <th style="text-align: left;">บริษัท</th>
                                    <th style="width: 5%; text-align: center;">ยอดหนี้สุทธิ</th>
                                    <th style="width: 5%; text-align: center;">ยอดหนี้</th>
                                    <th style="width: 5%; text-align: center;">ภาษีหัก ณ ที่จ่าย</th>
                                    <th style="width: 5%; text-align: center;">ยอดจ่ายเช็ค</th>
                                    <th style="width: 8%; text-align: center;">ธนาคาร</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, payment) in payments">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ payment.debt_id }}</td>
                                    <td style="text-align: center;">@{{ payment.debt_doc_recdate | thdate }}</td>
                                    <td style="text-align: center;">@{{ payment.paid_date | thdate }}</td>
                                    <td style="text-align: center;">@{{ payment.cheque_no }}</td>
                                    <td style="text-align: center;">@{{ payment.deliver_no }}</td>
                                    <td style="text-align: left;">@{{ payment.debt_type_name }}</td>
                                    <td style="text-align: left;">@{{ payment.debt_type_detail }}</td>
                                    <td style="text-align: left;">@{{ payment.pay_to }}</td>
                                    <td style="text-align: right;">@{{ payment.debt_total | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ payment.total | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ payment.net_amt | number: 2 }}</td>
                                    <td style="text-align: right;">@{{ payment.paid_amt | number: 2 }}</td>
                                    <td style="text-align: left;">@{{ payment.bank_name+ '(' +payment.bank_acc_no+ ')' }}</td>       
                                </tr>
                            </tbody>
                        </table>                        
                        
                    </div><!-- /.box-body -->

                    <!-- Loading (remove the following to stop the loading)-->
                    <div ng-show="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->

                    <div class="box-footer clearfix">
                        <a  ng-show="payments.length"
                            ng-click="creditorPaidToExcel()"
                            class="btn btn-success">
                            Excel
                        </a>

                        <ul ng-show="payments.length" class="pagination pagination-sm no-margin pull-right">                            
                            <li ng-if="pager.current_page !== 1">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.path+ '?page=1')" aria-label="First">
                                    <span aria-hidden="true">First</span>
                                </a>
                            </li>                            

                            <li ng-class="{'disabled': (pager.current_page==1)}">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.prev_page_url)" aria-label="Prev">
                                    <span aria-hidden="true">Prev</span>
                                </a>
                            </li>

                            <li ng-repeat="i in pages" ng-class="{'active': pager.current_page==i}">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.path + '?page=' +i)">
                                    @{{ i }}
                                </a>
                            </li>

                            <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.path)">
                                    ...
                                </a>
                            </li> -->
                            
                            <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>

                            <li ng-if="pager.current_page !== pager.last_page">
                                <a href="#" ng-click="getCreditorPaidWithURL(pager.path+ '?page=' +pager.last_page)" aria-label="Last">
                                    <span aria-hidden="true">Last</span>
                                </a>
                            </li>
                        </ul>
                    </div>

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