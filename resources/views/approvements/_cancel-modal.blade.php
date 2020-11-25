<div class="modal fade" id="dlgCancelForm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">บันทึกยกเลิกรายการขออนุมัติ</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">

                <form action="#" id="frmCancelApprovement" name="frmCancelApprovement">
                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                    <input type="hidden" id="app_id" name="app_id" ng-model="cancelData.app_id">
                    {{ csrf_field() }}

                    <div class="form-group" ng-class="{ 'has-error': frmCancelApprovement.reason.$error.required }">
                        <label for="">เหตุผลการยกเลิก</label>
                        <textarea
                            id="reason"
                            name="reason"
                            ng-model="cancelData.reason"
                            cols="30"
                            rows="10"
                            class="form-control"
                            required
                        >
                        </textarea>
                        <div class="help-block" ng-show="frmCancelApprovement.reason.$error.required">
                            กรุณาระบุเหตุผลการยกเลิก
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    ng-click="doCancel($event, frmCancelApprovement)">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>