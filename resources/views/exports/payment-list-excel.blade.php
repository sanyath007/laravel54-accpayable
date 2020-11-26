<div class="box">

    <div class="box-header with-border">
        <h3 class="box-title">รายการตัดจ่ายหนี้</h3>
        <p>ระหว่างวันที่ {{ convThDateFromDb($sdate) }} - {{ convThDateFromDb($edate) }}</p>
    </div><!-- /.box-header -->

    <div class="box-body">
        <table class="table table-bordered" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <th style="width: 5%; text-align: center;">รหัส</th>
                    <th style="width: 10%; text-align: center;">เลขที่ บค.</th>
                    <th style="width: 7%; text-align: center;">วันที่</th>
                    <th style="width: 8%; text-align: center;">เลขที่เช็ค</th>
                    <th style="width: 7%; text-align: center;">วันที่เช็ค</th>
                    <th style="text-align: left;">สั่งจ่าย</th>
                    <th style="width: 8%; text-align: center;">ฐานภาษี</th>
                    <th style="width: 8%; text-align: center;">ณ ที่จ่าย</th>
                    <th style="width: 8%; text-align: center;">ยอดสุทธิ</th>
                    <!-- <th style="width: 5%; text-align: center;">สถานะ</th> -->
                </tr>
            </thead>
            <tbody>

                <?php $cx = 0; ?>
                @foreach($payments as $payment)

                    <tr>
                        <td style="text-align: center;">{{ ++$cx }}</td>
                        <td style="text-align: center;">{{ $payment->payment_id }}</td>
                        <td style="text-align: center;">{{ $payment->paid_doc_no }}</td>
                        <td style="text-align: center;">{{ convThDateFromDb($payment->paid_date) }}</td>
                        <td style="text-align: center;">{{ $payment->cheque_no }}</td>
                        <td style="text-align: center;">{{ convThDateFromDb($payment->cheque_date) }}</td>
                        <td style="text-align: left;">{{ $payment->pay_to }}</td>
                        <td style="text-align: right;">{{ number_format($payment->net_val, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($payment->net_amt, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($payment->total, 2) }}</td>
                        <!-- <td style="text-align: center;">@{{ $payment->paid_stat }}</td> -->
                    </tr>

                @endforeach

            </tbody>
        </table>
    </div><!-- /.box-body -->

</div><!-- /.box -->