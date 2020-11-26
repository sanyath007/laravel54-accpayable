<h3>สรุปยอดหนี้ค้างชำระ</h3>
<table class="table table-bordered" style="font-size: 12px;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 8%; text-align: center;">รหัสเจ้าหนี้</th>
            <th style="text-align: left;">เจ้าหนี้</th>
            <th style="width: 10%; text-align: center;">น้อยกว่า 60d</th>
            <th style="width: 10%; text-align: center;">60-89d</th>
            <th style="width: 10%; text-align: center;">90-120d</th>
            <th style="width: 10%; text-align: center;">มากกว่า 120d</th>
            <th style="width: 10%; text-align: center;">รวม</th>
        </tr>
    </thead>
    <tbody>

        <?php $cx = 0; ?>
        @foreach($debts as $debt)

            <tr ng-repeat="(index, debt) in debts">
                <td style="text-align: center;">{{ ++$cx }}</td>
                <td style="text-align: center;">{{ $debt->supplier_id }}</td>
                <td style="text-align: left;">{{ $debt->supplier_name }}</td>
                <td style="text-align: right;">{{ number_format($debt->less60d, 2) }}</td>
                <td style="text-align: right;">{{ number_format($debt->b6089d, 2) }}</td>
                <td style="text-align: right;">{{ number_format($debt->b90119d, 2) }}</td>
                <td style="text-align: right;">{{ number_format($debt->great120d, 2) }}</td>
                <td style="text-align: right;">{{ number_format($debt->total, 2) }}</td>
            </tr>

        @endforeach

    </tbody>
</table>