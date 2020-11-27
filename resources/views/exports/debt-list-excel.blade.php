<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">รายการรับหนี้</h3>
        <p>ระหว่างวันที่ {{ convThDateFromDb($options['sdate']) }} - {{ convThDateFromDb($options['edate']) }}</p>
    </div><!-- /.box-header -->

    <div class="box-body">

        <table class="table table-bordered table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <th style="width: 6%; text-align: center;">รหัสรายการ</th>
                    <th style="width: 7%; text-align: center;">วันที่ลงบัญชี</th>
                    <th style="width: 8%; text-align: center;">เลขที่ใบส่งของ</th>
                    <th style="width: 7%; text-align: center;">วันที่ใบส่งของ</th>
                    <th style="text-align: left;">เจ้าหนี้</th>
                    <th style="text-align: left;">ประเภทหนี้</th>
                    <th style="width: 7%; text-align: center;">ยอดหนี้</th>
                    <th style="width: 7%; text-align: center;">ภาษี</th>
                    <th style="width: 7%; text-align: center;">สุทธิ</th>
                    <th style="width: 6%; text-align: center;">สถานะ</th>
                </tr>
            </thead>
            <tbody>

                <?php $cx = 0; ?>
                @foreach($debts as $debt)

                    <tr>
                        <td style="text-align: center;">{{ ++$cx }}</td>
                        <td style="text-align: center;">{{ $debt->debt_id }}</td>
                        <td style="text-align: center;">{{ convThDateFromDb($debt->debt_date) }}</td>
                        <td style="text-align: center;">{{ $debt->deliver_no }}</td>
                        <td style="text-align: center;">{{ convThDateFromDb($debt->deliver_date) }}</td>
                        <td style="text-align: left;">{{ $debt->supplier_name }}</td>
                        <td style="text-align: left;">{{ $debt->debttype->debt_type_name }}</td>
                        <td style="text-align: right;">{{ number_format($debt->debt_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($debt->debt_vat, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($debt->debt_total, 2) }}</td>
                        <td style="text-align: center;">
                            <span class="label label-info">
                                {{ 
                                    ($debt->debt_status==1) ? 'ขออนุมัติ' : 
                                    ($debt->debt_status==2) ? 'ชำระเงินแล้ว' : 
                                    ($debt->debt_status==3) ? 'ยกเลิก' : 'รอดำเนินการ' 
                                }}
                            </span>
                        </td>      
                    </tr>

                @endforeach

            </tbody>
        </table>
        
    </div><!-- /.box-body -->
</div><!-- /.box -->
