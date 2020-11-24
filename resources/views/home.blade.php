@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section
        class="content"
        ng-controller="homeCtrl"
        ng-init="getSumMonthData(); getSumYearData(); getCardData();">

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h4>@{{ cardData.supplier_num | currency:'':0 }}</h4>

                        <p><h4>เจ้าหนี้ทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h4>@{{ cardData.debt_total | currency:'':2 }}</h4><!-- <sup style="font-size: 20px">%</sup> -->

                        <p><h4>ยอดหนี้ทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h4>@{{ cardData.paid | currency:'':2 }}</h4>

                        <p><h4>ยอดหนี้ชำระทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h4>@{{ cardData.arrear | currency:'':2 }}</h4>

                        <p><h4>ยอดหนี้ค้างชำระทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
                
                <div id="barContainer1" style="width: 100%; height: 400px; margin: 0 auto; margin-top: 20px;"></div>

            </section><!-- /.Left col -->

            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable">

               <div id="barContainer2" style="width: 100%; height: 400px; margin: 0 auto; margin-top: 20px;"></div>

            </section><!-- right col -->

        </div><!-- /.row (main row) -->

    </section>

@endsection