@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มธนาคาร
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มธนาคาร</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="bankCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มเพิ่มธนาคาร</h3>
                    </div>

                    <form id="frmNewBank" name="frmNewBank" method="post" action="{{ url('/bank/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">     

                                <div class="form-group" ng-class="{ 'has-error' : frmNewBank.bank_name.$invalid}">
                                    <label class="control-label">ชื่อธนาคาร :</label>
                                    <input
                                        type="text"
                                        id="bank_name"
                                        name="bank_name"
                                        ng-model="bank.bank_name"
                                        class="form-control"
                                        required>
                                    <div class="help-block" ng-show="frmNewBank.bank_name.$error.required">
                                        กรุณาระบุชื่อธนาคาร
                                    </div>
                                </div>

                            </div>
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button ng-click="add($event, frmNewBank)" class="btn btn-success pull-right">
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
        });
    </script>

@endsection