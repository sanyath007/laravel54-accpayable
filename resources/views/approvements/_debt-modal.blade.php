<div class="modal fade" id="dlgSupplierDebtList" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="">รายการหนี้</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 12px; margin-top: 20px;">
                        <thead>
                            <tr>
                                <th style="width: 2%; text-align: center;">#</th>
                                <th style="width: 5%; text-align: center;">รหัส</th>
                                <th style="width: 10%; text-align: center;">วันที่ลงบัญชี</th>
                                <th style="width: 12%; text-align: center;">เลขที่ใบส่งของ</th>
                                <!-- <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th> -->
                                <th style="text-align: left;">ประเภทหนี้</th>
                                <th style="width: 6%; text-align: center;">ยอดหนี้</th>
                                <th style="width: 6%; text-align: center;">ภาษี</th>
                                <th style="width: 6%; text-align: center;">สุทธิ</th>
                                <!-- <th style="width: 6%; text-align: center;">สถานะ</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, debt) in debts">
                                <td class="text-center">
                                    <input
                                        type="checkbox" 
                                        ng-click="addSupplierDebtData($event, debt)"
                                    >
                                </td>
                                <td class="text-center">@{{ debt.debt_id }}</td>
                                <td class="text-center">@{{ debt.debt_date | thdate }}</td>
                                <td class="text-center">@{{ debt.deliver_no }}</td>
                                <!-- <td>@{{ debt.deliver_date }}</td> -->
                                <td>@{{ debt.debttype.debt_type_name }}</td>
                                <td class="text-right">@{{ debt.debt_amount | number:2 }}</td>
                                <td class="text-right">@{{ debt.debt_vat | number:2 }}</td>
                                <td class="text-right">@{{ debt.debt_total | number:2 }}</td>
                                <!-- <td>@{{ debt.debt_status }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->

            </div>
            <div class="modal-footer">
                <ul class="pagination pagination-sm no-margin pull-left">
                    <li ng-if="debtPager.current_page !== 1">
                        <a ng-click="getSupplierDebtDataWithURL(debtPager.first_page_url)" aria-label="Previous">
                            <span aria-hidden="true">First</span>
                        </a>
                    </li>
                
                    <li ng-class="{'disabled': (debtPager.current_page==1)}">
                        <a ng-click="getSupplierDebtDataWithURL(debtPager.first_page_url)" aria-label="Prev">
                            <span aria-hidden="true">Prev</span>
                        </a>
                    </li>
                    
                    <li ng-if="debtPager.current_page < debtPager.last_page && (debtPager.last_page - debtPager.current_page) > 10">
                        <a href="@{{ debtPager.url(debtPager.current_page + 10) }}">
                            ...
                        </a>
                    </li>
                
                    <li ng-class="{'disabled': (debtPager.current_page==debtPager.last_page)}">
                        <a ng-click="getSupplierDebtDataWithURL(debtPager.next_page_url)" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>

                    <li ng-if="debtPager.current_page !== debtPager.last_page">
                        <a ng-click="getSupplierDebtDataWithURL(debtPager.last_page_url)" aria-label="Previous">
                            <span aria-hidden="true">Last</span>
                        </a>
                    </li>
                </ul>

                <span class="pull-left" style="margin: 5px 10px;">
                    @{{ debtPager.current_page+ ' of '+debtPager.last_page }} pages
                </span>

                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>