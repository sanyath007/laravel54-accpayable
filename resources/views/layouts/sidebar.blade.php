<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>

                    @if (!Auth::guest())
                        {{ Auth::user()->person_firstname }} {{ Auth::user()->person_lastname }}
                    @endif

                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            <li class="active">
                <a href="{{ url('/home') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-address-book-o"></i>
                    <span>หนี้</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/debt/list') }}">
                            <i class="fa fa-circle-o"></i> รายการหนี้
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ url('/approve/list') }}">
                            <i class="fa fa-circle-o"></i> รายการชำระเงินแล้ว
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('payment/list') }}">
                            <i class="fa fa-circle-o"></i> รายการลดหนี้ศูนย์
                        </a>
                    </li> -->
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>ขออนุมัติเบิก-จ่าย</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/approve/list') }}">
                            <i class="fa fa-circle-o"></i> รายการขออนุมัติ
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-paper-plane-o"></i>
                    <span>ตัดจ่ายหนี้</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('payment/list') }}">
                            <i class="fa fa-circle-o"></i> รายการตัดจ่ายหนี้
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>บัญชี</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/account/ledger/0/0/0') }}">
                            <i class="fa fa-circle-o"></i> แยกประเภทเจ้าหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/account/ledger-debttype/0/0/0') }}">
                            <i class="fa fa-circle-o"></i> แยกประเภทหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/account/arrear') }}">
                            <i class="fa fa-circle-o"></i> ยอดหนี้ค้างจ่าย
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/account/creditor-paid') }}">
                            <i class="fa fa-circle-o"></i> เจ้าหนี้จ่ายชำระหนี้
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>รายงาน</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/report/debt-creditor/list') }}">
                            <i class="fa fa-circle-o"></i> ยอดหนี้รายเจ้าหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/report/debt-debttype/list') }}">
                            <i class="fa fa-circle-o"></i> ยอดหนี้รายประเภทหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/report/sum-arrear') }}">
                            <i class="fa fa-circle-o"></i> สรุปยอดหนี้ค้างชำระ
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i> <span>ข้อมูลพื้นฐาน</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/creditor/list') }}">
                            <i class="fa fa-circle-o"></i> เจ้าหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/debttype/list') }}">
                            <i class="fa fa-circle-o"></i> ประเภทหนี้
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/bank/list') }}">
                            <i class="fa fa-circle-o"></i> ธนาคาร
                        </a>
                    </li>
                </ul>
            </li>													
        </ul>
    </section><!-- /.sidebar -->

</aside>