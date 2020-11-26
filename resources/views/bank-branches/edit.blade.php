@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขสาขาธนาคาร : {{ $branch->bank_branch_id }}
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขสาขาธนาคาร</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="bankBranchCtrl" ng-init="getBranch('{{ $branch->bank_branch_id }}')">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มแก้ไขสาขาธนาคาร</h3>
                    </div>

                    <form id="frmEditBankBranch" name="frmEditBankBranch" method="post" action="{{ url('/bank-branch/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">                                
                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankBranch.bank_branch_name.$invalid}">
                                    <label class="control-label">ชื่อสาขาธนาคาร :</label>
                                    <input
                                        type="text"
                                        id="bank_branch_name"
                                        name="bank_branch_name"
                                        ng-model="branch.bank_branch_name"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankBranch.bank_branch_name.$error.required">
                                        กรุณาระบุชื่อสาขาธนาคาร
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankBranch.bank_id.$invalid}">
                                    <label class="control-label">ธนาคาร :</label>
                                    <select id="bank_id"
                                            name="bank_id"
                                            ng-model="branch.bank_id"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;" required>
                                            
                                        <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                        @foreach($banks as $bank)

                                            <option value="{{ $bank->bank_id }}">
                                                {{ $bank->bank_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                    <div class="help-block" ng-show="frmEditBankBranch.bank_id.$error.required">
                                        กรุณาเลือกธนาคาร
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankBranch.bank_branch_addr.$invalid}">
                                    <label class="control-label">ที่อยู่ :</label>
                                    <input
                                        type="text"
                                        id="bank_branch_addr"
                                        name="bank_branch_addr"
                                        ng-model="branch.bank_branch_addr"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankBranch.bank_branch_addr.$error.required">
                                        กรุณาระบุที่อยู่
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankBranch.bank_branch_tel.$invalid}">
                                    <label class="control-label">โทรศัพท์ :</label>
                                    <input
                                        type="text"
                                        id="bank_branch_tel"
                                        name="bank_branch_tel"
                                        ng-model="branch.bank_branch_tel"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankBranch.bank_branch_tel.$error.required">
                                        กรุณาระบุโทรศัพท์
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{ 'has-error' : frmEditBankBranch.bank_branch_fax.$invalid}">
                                    <label class="control-label">โทรสาร :</label>
                                    <input
                                        type="text"
                                        id="bank_branch_fax"
                                        name="bank_branch_fax"
                                        ng-model="branch.bank_branch_fax"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmEditBankBranch.bank_branch_fax.$error.required">
                                        กรุณาระบุโทรสาร
                                    </div>
                                </div>

                            </div>
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="update($event, frmEditBankBranch, '{{ $branch->bank_branch_id }}')"
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