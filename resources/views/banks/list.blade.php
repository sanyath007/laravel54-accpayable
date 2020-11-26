@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการบัญชีธนาคาร
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการบัญชีธนาคาร</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="bankAccCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-12">                                
                                <div class="form-group">
                                    <label>ค้นหาบัญชีธนาคาร</label>
                                    <input type="text" id="searchKey" ng-keyup="getData($event)" class="form-control">
                                </div><!-- /.form group -->
                            </div>

                        </div><!-- /.box-body -->
                  
                        <div class="box-footer">
                            <a href="{{ url('/bankacc/add') }}" class="btn btn-primary"> เพิ่มบัญชีธนาคาร</a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                      <h3 class="box-title">รายการบัญชีธนาคาร</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                      <table class="table table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 6%; text-align: center;">รหัส</th>
                                    <th style="width: 10%; text-align: center;">เลขบัญชี</th>
                                    <th style="text-align: center;">บัญชีธนาคาร</th>
                                    <th style="width: 15%; text-align: center;">ธนาคาร</th>
                                    <th style="width: 10%; text-align: center;">สาขา</th>
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, bankacc) in bankaccs">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ bankacc.bank_acc_id }}</td>
                                    <td style="text-align: center;">@{{ bankacc.bank_acc_no }}</td>
                                    <td style="text-align: left;">@{{ bankacc.bank_acc_name }}</td>
                                    <td style="text-align: center;">@{{ bankacc.bank.bank_name }}</td>
                                    <td style="text-align: center;">@{{ bankacc.branch.bank_branch_name }}</td>
                                    <td style="text-align: center;">
                                        <a ng-click="edit(bankacc.bank_acc_id)" class="btn btn-warning btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        @if(Auth::user()->person_id == '1300200009261')

                                            <a ng-click="delete(bankacc.bank_acc_id)" class="btn btn-danger btn-xs">
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
                                <a ng-click="getDataWithURL(pager.path+ '?page=1')" aria-label="First">
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
                                <a ng-click="getDataWithURL(pager.path+ '?page=' +pager.last_page)" aria-label="Last">
                                    <span aria-hidden="true">Last</span>
                                </a>
                            </li>

                        </ul>
                    </div><!-- /.box-footer -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

@endsection