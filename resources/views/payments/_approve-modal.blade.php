<div class="modal fade" id="dlgApproveSelection" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">รายการขออนุมัติ</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 12px; margin-top: 20px;">
                        <thead>
                            <tr>
                                <th style="width: 2%; text-align: center;">#</th>
                                <th style="width: 5%; text-align: center;">รหัส</th>
                                <th style="width: 8%; text-align: center;">เลขที่ บค.</th>
                                <th style="width: 10%; text-align: center;">วันที่ บค.</th>
                                <th style="text-align: left;">รายการหนี้</th>
                                <th style="width: 6%; text-align: center;">ยอดหนี้</th>
                                <th style="width: 6%; text-align: center;">ภาษี</th>
                                <th style="width: 6%; text-align: center;">สุทธิ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, approvement) in approvements">
                                <td class="text-center">
                                    <input
                                        type="checkbox" 
                                        ng-click="addSupplierApproveData($event, approvement)"
                                    >
                                </td>
                                <td class="text-center">@{{ approvement.app_id }}</td>
                                <td class="text-center">@{{ approvement.app_doc_no }}</td>
                                <td class="text-center">@{{ approvement.app_date | thdate }}</td>
                                <td>
                                    <ul class="tag__list">
                                        <li ng-repeat="(index, detail) in approvement.app_detail">
                                            <span class="label label-info">@{{ detail.debt_id }}</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-right">@{{ approvement.net_val | number:2 }}</td>
                                <td class="text-right">@{{ approvement.net_amt | number:2 }}</td>
                                <td class="text-right">@{{ approvement.net_total | number:2 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->

            </div>
            <div class="modal-footer">
                <ul class="pagination pagination-sm no-margin pull-left">
                    <li ng-if="approvePager.current_page !== 1">
                        <a ng-click="getSupplierApproveDataWithURL(approvePager.path+ '?page=1')" aria-label="Previous">
                            <span aria-hidden="true">First</span>
                        </a>
                    </li>
                
                    <li ng-class="{'disabled': (approvePager.current_page==1)}">
                        <a ng-click="getSupplierApproveDataWithURL(approvePager.prev_page_url)" aria-label="Prev">
                            <span aria-hidden="true">Prev</span>
                        </a>
                    </li>
                    
                    <li ng-if="approvePager.current_page < approvePager.last_page && (approvePager.last_page - approvePager.current_page) > 10">
                        <a href="@{{ approvePager.url(approvePager.current_page + 10) }}">
                            ...
                        </a>
                    </li>
                
                    <li ng-class="{'disabled': (approvePager.current_page==approvePager.last_page)}">
                        <a ng-click="getSupplierApproveDataWithURL(approvePager.next_page_url)" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>

                    <li ng-if="approvePager.current_page !== approvePager.last_page">
                        <a ng-click="getSupplierApproveDataWithURL(approvePager.path+ '?page=' +approvePager.last_page)" aria-label="Previous">
                            <span aria-hidden="true">Last</span>
                        </a>
                    </li>
                </ul>

                <span class="pull-left" style="margin: 5px 10px;">
                    @{{ approvePager.current_page+ ' of '+approvePager.last_page }} pages
                </span>

                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>