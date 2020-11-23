<div class="modal fade" id="dlgApproveDebtDetail" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">รายละเอียดหนี้</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">
                <div class="row">
                    <div class="col-md-6">
                        <p>รหัสหนี้</p>
                        <p>วันที่ลงบัญชี</p>
                        <p>เลขที่ใบส่งสินค้า</p>
                        <p>ยอดหนี้</p>
                        <p>ภาษีมูลค่าเพิ่ม</p>
                        <p>ยอดหนี้สุทธิ</p>
                    </div>
                    <div class="col-md-6">
                        <p>@{{ debtDetail[0].debt_id }}</p>
                        <p>@{{ debtDetail[0].debt_date | thdate }}</p>
                        <p>@{{ debtDetail[0].deliver_no }}</p>
                        <p>@{{ debtDetail[0].debt_amount | currency:'':2 }}</p>
                        <p>@{{ debtDetail[0].debt_vat | currency:'':2  }}</p>
                        <p>@{{ debtDetail[0].debt_total | currency:'':2  }}</p>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>