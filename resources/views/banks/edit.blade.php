@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขบัญชีธนาคาร : {{ $bankacc->bank_acc_id }}
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขบัญชีธนาคาร</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="bankAccCtrl" ng-init="getBankAcc('{{ $bankacc->bank_acc_id }}')">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มแก้ไขบัญชีธนาคาร</h3>
                    </div>

                    <form id="frmEditBankAcc" name="frmEditBankAcc" method="post" action="{{ url('/bankacc/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">
                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankAcc.bank_acc_no.$invalid}">
                                    <label class="control-label">เลขที่บัญชีธนาคาร :</label>
                                    <input
                                        type="text"
                                        id="bank_acc_no"
                                        name="bank_acc_no"
                                        ng-model="bankacc.bank_acc_no"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankAcc.bank_acc_no.$error.required">
                                        กรุณาระบุเลขที่บัญชีธนาคาร
                                    </div>
                                </div> 
                                
                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankAcc.bank_acc_name.$invalid}">
                                    <label class="control-label">ชื่อบัญชีธนาคาร :</label>
                                    <input
                                        type="text"
                                        id="bank_acc_name"
                                        name="bank_acc_name"
                                        ng-model="bankacc.bank_acc_name"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankAcc.bank_acc_name.$error.required">
                                        กรุณาระบุชื่อบัญชีธนาคาร
                                    </div>
                                </div> 

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankAcc.bankacc_type_id.$invalid}">
                                    <label class="control-label">ประเภทบัญชี :</label>
                                    <select id="bankacc_type_id"
                                            name="bankacc_type_id"
                                            ng-model="bankacc.bankacc_type_id"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;" required>
                                            
                                        <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                        @foreach($bankTypes as $type)

                                            <option value="{{ $type->bankacc_type_id }}">
                                                {{ $type->bankacc_type_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                    <div class="help-block" ng-show="frmEditBankAcc.bankacc_type_id.$error.required">
                                        กรุณาเลือกประเภทบัญชี
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankAcc.bank_id.$invalid}">
                                    <label class="control-label">ธนาคาร :</label>
                                    <select id="bank_id"
                                            name="bank_id"
                                            ng-model="bankacc.bank_id"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;" required>
                                            
                                        <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                        @foreach($banks as $bank)

                                            <option value="{{ $bank->bank_id }}">
                                                {{ $bank->bank_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                    <div class="help-block" ng-show="frmEditBankAcc.bank_id.$error.required">
                                        กรุณาเลือกธนาคาร
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankAcc.bank_branch_id.$invalid}">
                                    <label class="control-label">สาขาธนาคาร :</label>
                                    <select id="bank_branch_id"
                                            name="bank_branch_id"
                                            ng-model="bankacc.bank_branch_id"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;" required>
                                            
                                        <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                        @foreach($branches as $branch)

                                            <option value="{{ $branch->bank_branch_id }}">
                                                {{ $branch->bank_branch_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                    <div class="help-block" ng-show="frmEditBankAcc.bank_branch_id.$error.required">
                                        กรุณาเลือกสาขาธนาคาร
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="update($event, frmEditBankAcc, '{{ $bankacc->bank_acc_id }}')"
                                class="btn btn-warning pull-right"
                            >
                                แก้ไข
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()
        });
    </script>

@endsection