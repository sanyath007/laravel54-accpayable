<div class="box">

    <div class="box-header with-border">
        <h3 class="box-title">รายการขออนุมัติเบิก-จ่ายหนี้</h3>
        <p>ระหว่างวันที่ {{ convThDateFromDb($sdate) }} - {{ convThDateFromDb($edate) }}</p>
    </div><!-- /.box-header -->

    <div class="box-body">
        <table class="table table-bordered" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <th style="width: 5%; text-align: center;">รหัส</th>
                    <th style="width: 8%; text-align: center;">เลขที่ขออนุมัติ</th>
                    <th style="width: 8%; text-align: center;">วันที่ขออนุมัติ</th>
                    <th style="width: 15%; text-align: center;">ประเภทหนี้</th>
                    <th style="text-align: left;">สั่งจ่าย</th>
                    <th style="width: 8%; text-align: center;">ฐานภาษี</th>
                    <th style="width: 8%; text-align: center;">ภาษีหัก ณ ที่จ่าย</th>
                    <th style="width: 8%; text-align: center;">ยอดสุทธิ</th>
                    <th style="width: 8%; text-align: center;">ยอดเช็ค</th>
                    <!-- <th style="width: 5%; text-align: center;">สถานะ</th> -->
                </tr>
            </thead>
            <tbody>

                <?php $cx = 0; ?>
                @foreach($approvements as $approvement)

                    <tr>
                        <td style="text-align: center;">{{ ++$cx }}</td>
                        <td style="text-align: center;">{{ $approvement->app_id }}</td>
                        <td style="text-align: center;">{{ $approvement->app_doc_no }}</td>
                        <td style="text-align: center;">{{ convThDateFromDb($approvement->app_date) }}</td>
                        <td style="text-align: left;">{{ getDebtTypeListOfApprovement($approvement->app_detail, $debttypes) }}</td>
                        <td style="text-align: left;">{{ $approvement->pay_to }}</td>
                        <td style="text-align: right;">{{ number_format($approvement->net_val, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($approvement->tax_val, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($approvement->net_total, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($approvement->cheque, 2) }}</td>
                        <!-- <td style="text-align: center;">{{ $approvement->app_stat }}</td> -->
                    </tr>

                @endforeach

            </tbody>
        </table>
    </div><!-- /.box-body -->

</div><!-- /.box -->

