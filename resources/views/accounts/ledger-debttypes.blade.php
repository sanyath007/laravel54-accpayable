@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            บัญชีแยกประเภทหนี้
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">บัญชีแยกประเภทหนี้</li>
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

                    <form id="frmSearch" name="frmSearch" role="form" action="{{ url(('/account/ledger-debttype')) }}" method="GET">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>ประเภทหนี้</label>
                                    <select id="debttype" class="form-control select2" style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>
                                        
                                        @foreach($debttypes as $debttype)

                                            <option value="{{ $debttype->debt_type_id }}">
                                                {{ $debttype->debt_type_name }}
                                            </option>

                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ระหว่างวันที่ :</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input  type="text" 
                                                id="sdate" 
                                                name="sdate"
                                                class="form-control pull-right"
                                                tabindex="1" required>
                                    </div><!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ถึงวันที่ :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input  type="text" 
                                                id="edate" 
                                                name="edate"
                                                class="form-control pull-right"
                                                tabindex="1" required>
                                    </div><!-- /.input group -->
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="showall" name="showall" /> แสดงทั้งหมด
                                    </label>
                                </div>
                            </div> -->
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button ng-click="getLedgerDebttypes($event, '/account/ledger-debttype')" class="btn btn-info">
                                ค้นหา
                            </button>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">บัญชีแยกประเภทหนี้</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <!-- Loading (remove the following to stop the loading)-->
                        <div ng-show="loading" class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <!-- end loading -->

                        <div ng-show="debttypes.length == 0 && !loading" style="text-align: center; color: red;">
                            <span >-- ยังไม่มีรายการ --</span>
                        </div>

                        <div ng-show="debttypes.length > 0" ng-repeat="(index, debttype) in debttypes">
                            <h4>@{{ pager.from+index }}.@{{ debttype.debt_type_name }} (@{{ debttype.debt_type_id }})</h4>

                            <table class="table table-bordered table-striped" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th style="width: 3%; text-align: center;">#</th>
                                        <th style="width: 5%; text-align: center;">รหัสรายการ</th>
                                        <th style="width: 8%; text-align: center;">วันที่ลงบัญชี</th>
                                        <th style="width: 8%; text-align: center;">เลขที่เอกสาร</th>
                                        <th style="width: 15%; text-align: left;">เจ้าหนี้</th>
                                        <th style="text-align: left;">รายการ</th>
                                        <th style="width: 8%; text-align: center;">ยอดหนี้</th>
                                        <th style="width: 8%; text-align: center;">ยอดชำระ</th>
                                        <th style="width: 8%; text-align: center;">ยอดคงเหลือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="(index, debt) in debttype.debts">
                                    <td style="text-align: center;">@{{ index+1 }}</td>
                                        <td style="text-align: center;">@{{ debt.debt_id }}</td>
                                        <td style="text-align: center;">@{{ debt.debt_date | thdate }}</td>
                                        <td style="text-align: center;">@{{ debt.deliver_no }}</td>
                                        <td style="text-align: left;">@{{ debt.supplier_name }}</td>
                                        <td style="text-align: left;">@{{ debt.debt_type_detail }}</td>
                                        <td style="text-align: right;">@{{ debt.debt_total | currency:'':2 }}</td>
                                        <td style="text-align: right;">@{{ debt.rcpamt | currency:'':2 }}</td>
                                        <td style="text-align: right;">
                                            @{{ debt.rcpamt ? (debt.rcpamt - debt.debt_total) : debt.debt_total  | currency:'':2 }}
                                        </td>
                                    </tr>
                                    <tr style="background-color: gray;">
                                        <td style="text-align: center;" colspan="6">รวม</td>
                                        <td style="text-align: right;">@{{ debttype.credit | currency:'':2 }}</td>
                                        <td style="text-align: right;">@{{ debttype.debit | currency:'':2 }}</td>
                                        <td style="text-align: right;">@{{ debttype.balance | currency:'':2 }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="row" ng-show="debttypes.length > 0">
                            <div class="col-md-4">
                                <a ng-click="ledgerDebttypesToExcel()" class="btn btn-success">
                                    Excel
                                </a>
                            </div>
                            <div class="col-md-4" style="text-align: center;">
                                <span>หน้า @{{ pager.current_page }} / @{{ pager.last_page }}</span>
                                <span>จำนวนทั้งสิ้น @{{ pager.total }} รายการ</span>
                            </div>
                            <div class="col-md-4">
                                <ul ng-show="debttypes.length > 0" class="pagination pagination-sm no-margin pull-right">                            
                                    <li ng-if="pager.current_page !== 1">
                                        <a href="#" ng-click="getLedgerDebttypesWithURL(pager.path+ '?page=1')" aria-label="First">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>                            

                                    <li ng-class="{'disabled': (pager.current_page==1)}">
                                        <a href="#" ng-click="getLedgerDebttypesWithURL(pager.prev_page_url)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>
                                    
                                    <li ng-repeat="i in pages" ng-class="{'active': pager.current_page==i}">
                                        <a href="#" ng-click="getLedgerDebttypesWithURL(pager.path + '?page=' +i)">
                                            @{{ i }}
                                        </a>
                                    </li>

                                    <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                        <a href="#" ng-click="getDataWithURL(pager.path)">
                                            ...
                                        </a>
                                    </li> -->
                                    
                                    <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                        <a href="#" ng-click="getLedgerDebttypesWithURL(pager.next_page_url)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="pager.current_page !== pager.last_page">
                                        <a href="#" ng-click="getLedgerDebttypesWithURL(pager.path+ '?page=' +pager.last_page)" aria-label="Last">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            const initDatePicker = {
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true,
                orientation: 'bottom'
            };

            //Initialize Select2 Elements
            $('.select2').select2()

            $('#sdate').datepicker(initDatePicker); //.datepicker("setDate", moment().format('YYYY-MM-DD'));

            $('#edate').datepicker(initDatePicker); //.datepicker("setDate", moment().format('YYYY-MM-DD'));
        });
    </script>

@endsection