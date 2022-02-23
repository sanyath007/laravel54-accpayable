<h3 class="box-title">บัญชีแยกประเภทเจ้าหนี้</h3>

@foreach($creditors as $creditor)

    <div>
        <h4 style="margin-bottom: 0">{{ 1 }}.{{ $creditor->supplier_name }} ({{ $creditor->supplier_id }})</h4>

        <table class="table table-bordered table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <th style="width: 5%; text-align: center;">รหัสรายการ</th>
                    <th style="width: 8%; text-align: center;">วันที่ลงบัญชี</th>
                    <th style="width: 8%; text-align: center;">เลขที่เอกสาร</th>
                    <th style="width: 15%; text-align: left;">ประเภทหนี้</th>
                    <th style="text-align: left;">รายการ</th>
                    <th style="width: 8%; text-align: center;">เครดิต</th>
                    <th style="width: 8%; text-align: center;">เดบิต</th>
                    <th style="width: 8%; text-align: center;">ยอดคงเหลือ</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    $index = 0;
                    $credit = 0;
                    $debit = 0;
                    $balance = 0;
                ?>
                @foreach($debts as $debt)
                    @if($debt->supplier_id == $creditor->supplier_id)
                        <?php
                            $index++;
                            $credit += $debt->debt_amount;
                            $debit += $debt->rcpamt;
                            $balance += $debt->rcpamt ? ($debt->rcpamt - $debt->debt_amount) : $debt->debt_amount;
                        ?>
                        <tr>
                            <td style="text-align: center;">{{ $index }}</td>
                            <td style="text-align: center;">{{ $debt->debt_id }}</td>
                            <td style="text-align: center;">{{ convThDateFromDb($debt->debt_date) }}</td>
                            <td style="text-align: center;">{{ $debt->deliver_no }}</td>
                            <td style="text-align: left;">{{ $debt->debt_type_name }}</td>
                            <td style="text-align: left;">{{ $debt->debt_type_detail }}</td>
                            <td style="text-align: right;">{{ number_format($debt->debt_amount, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($debt->rcpamt, 2) }}</td>
                            <td style="text-align: right;">
                                {{ number_format($debt->rcpamt ? ($debt->rcpamt - $debt->debt_amount) : $debt->debt_amount, 2) }}
                            </td>
                        </tr>

                    @endif
                @endforeach

                <tr>
                    <td style="text-align: center;" colspan="6">รวม</td>
                    <td style="text-align: right;">{{ number_format($credit, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($debit, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($balance, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>

@endforeach
