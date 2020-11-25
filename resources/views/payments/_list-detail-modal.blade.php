<div class="modal fade" id="dlgPaymentDebtList" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">รายละเอียดรายการตัดจ่ายหนี้</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p>รหัสรายการ : <span style="font-weight: bold;">@{{ paymentData.payment_id }}</span></p>
                                        <p>วันที่ บค. : <span style="font-weight: bold;">@{{ paymentData.paid_date | thdate }}</span></p>
                                        <p>เลขที่ บค. : <span style="font-weight: bold;">@{{ paymentData.paid_doc_no }}</span></p>
                                        <p>เลขที่เช็ค : <span style="font-weight: bold;">@{{ paymentData.cheque_no }}</span></p>
                                        <p>วันที่เช็ค : <span style="font-weight: bold;">@{{ paymentData.cheque_date | thdate }}</span></p>
                                        <p>เจ้าหนี้ : <span style="font-weight: bold;">@{{ paymentData.pay_to }}</span></p>
                                    </div>

                                    <div class="col-md-4">
                                        <p>ฐานภาษี : <span style="font-weight: bold;">@{{ paymentData.net_val | currency:'':2 }}</span></p>
                                        <p>ยอดหนี้ : <span style="font-weight: bold;">@{{ paymentData.net_total | currency:'':2 }}</span></p>
                                        <p>ภาษีหัก ณ ที่จ่าย : <span style="font-weight: bold;">@{{ paymentData.net_amt | currency:'':2 }}</span></p>
                                        <p>ยอดเช็ค : <span style="font-weight: bold;">@{{ paymentData.total | currency:'':2 }}</span></p>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div><!-- ./col -->
                </div><!-- ./row -->

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th style="width: 2%; text-align: center;">#</th>
                                <th style="width: 5%; text-align: center;">รหัสขออนุมัติ</th>
                                <th style="width: 5%; text-align: center;">รหัสหนี้</th>
                                <th style="width: 7%; text-align: center;">วันที่ลงบัญชี</th>
                                <th style="width: 10%; text-align: center;">เลขที่ใบส่งของ</th>
                                <!-- <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th> -->
                                <th style="text-align: left;">ประเภทหนี้</th>
                                <th style="width: 20%; text-align: left;">รายละเอียด</th>
                                <th style="width: 8%; text-align: center;">ยอดหนี้</th>
                                <th style="width: 8%; text-align: center;">ภาษี</th>
                                <th style="width: 8%; text-align: center;">สุทธิ</th>
                                <!-- <th style="width: 6%; text-align: center;">สถานะ</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, debt) in debts">
                                <td class="text-center">@{{ debt.seq_no }}</td>
                                <td>@{{ debt.app_id }}</td>
                                <td>@{{ debt.debt_id }}</td>
                                <td>@{{ debt.debt.debt_date | thdate }}</td>
                                <td>@{{ debt.debt.deliver_no }}</td>
                                <!-- <td>@{{ debt.deliver_date }}</td> -->
                                <td>@{{ debttypes[debt.debt.debt_type_id] }}</td>
                                <td>@{{ debt.debt.debt_type_detail }}</td>
                                <td class="text-right">@{{ debt.debt.debt_amount | number:2 }}</td>
                                <td class="text-right">@{{ debt.debt.debt_vat | number:2 }}</td>
                                <td class="text-right">@{{ debt.debt.debt_total | number:2 }}</td>
                                <!-- <td>@{{ debt.debt_status }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>