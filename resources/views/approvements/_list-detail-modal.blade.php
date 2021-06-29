<div class="modal fade" id="dlgApproveDebtList" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80vw;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">รายละเอียดรายการขออนุมัติ</h4>
            </div>
            <div class="modal-body" style="padding-top: 10; padding-bottom: 0;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p>รหัสรายการ : <span style="font-weight: bold;">@{{ approveData.app_id }}</span></p>
                                        <p>เลขที่ขออนุมัติ : <span style="font-weight: bold;">@{{ approveData.app_date | thdate }}</span></p>
                                        <p>วันที่ขออนุมัติ : <span style="font-weight: bold;">@{{ approveData.app_doc_no }}</span></p>
                                        <p>เจ้าหนี้ : <span style="font-weight: bold;">@{{ approveData.pay_to }}</span></p>
                                    </div>

                                    <div class="col-md-4">
                                        <p>ฐานภาษี : <span style="font-weight: bold;">@{{ approveData.net_val | currency:'':2 }}</span></p>
                                        <p>ยอดหนี้ : <span style="font-weight: bold;">@{{ approveData.net_total | currency:'':2 }}</span></p>
                                        <p>ภาษีหัก ณ ที่จ่าย : <span style="font-weight: bold;">@{{ approveData.net_amt | currency:'':2 }}</span></p>
                                        <p>ยอดเช็ค : <span style="font-weight: bold;">@{{ approveData.cheque | currency:'':2 }}</span></p>
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
                                <th style="width: 5%; text-align: center;">รหัส</th>
                                <th style="width: 8%; text-align: center;">วันที่ลงบัญชี</th>
                                <th style="width: 10%; text-align: center;">เลขที่ใบส่งของ</th>
                                <!-- <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th> -->
                                <th style="text-align: left;">ประเภทหนี้</th>
                                <th style="width: 20%; text-align: left;">รายละเอียด</th>
                                <th style="width: 8%; text-align: right;">ยอดหนี้</th>
                                <th style="width: 8%; text-align: right;">VAT</th>
                                <th style="width: 8%; text-align: right;">สุทธิ</th>
                                <!-- <th style="width: 6%; text-align: center;">สถานะ</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, debt) in debts">
                                <td class="text-center">@{{ debt.seq_no }}</td>
                                <td class="text-center">@{{ debt.debt_id }}</td>
                                <td class="text-center">@{{ debt.debts.debt_date | thdate }}</td>
                                <td class="text-center">@{{ debt.debts.deliver_no }}</td>
                                <!-- <td>@{{ debt.deliver_date }}</td> -->
                                <td>@{{ debttypes[debt.debts.debt_type_id] }}</td>
                                <td>@{{ debt.debts.debt_type_detail }}</td>
                                <td class="text-right">@{{ debt.debts.debt_amount | number:2 }}</td>
                                <td class="text-right">@{{ debt.debts.debt_vat | number:2 }}</td>
                                <td class="text-right">@{{ debt.debts.debt_total | number:2 }}</td>
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