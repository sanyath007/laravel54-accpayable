<div class="modal fade" id="tmp-debts-list" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h5 class="modal-title">รายการส่งเบิกเงิน (จากพัสดุ)</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body" style="padding-bottom: 0;">
                    <!-- // TODO: Filtering controls -->
                    <div class="box">
                        <div class="box-body">
                            <!-- <div style="display: flex; gap: 5px;">
                                <select
                                    type="text"
                                    id="cboCategory"
                                    name="cboCategory"
                                    ng-model="cboCategory"
                                    ng-change="handleInputChange('cboCategory', cboCategory); getItems();"
                                    class="form-control"
                                >
                                    <option value="">-- เลือกประเภทสินค้า/บริการ --</option>
                                    <option ng-repeat="category in forms.categories" value="@{{ category.id }}">
                                        @{{ category.name }}
                                    </option>
                                </select>

                                <input
                                    type="text"
                                    ng-model="searchKey"
                                    class="form-control"
                                    ng-keyup="handleInputChange('searchKey', searchKey); getItems();"
                                />
                            </div> -->
                        </div><!-- /.box-body -->
                    </div>
                    <!-- // TODO: Filtering controls -->

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center;">#</th>
                                <th style="width: 10%; text-align: center;">เอกสารเบิกจ่ายเงิน</th>
                                <th style="width: 25%;">เจ้าหนี้</th>
                                <th>รายละเอียด</th>
                                <th style="width: 10%; text-align: center;">เลขที่ใบส่งของ</th>
                                <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th>
                                <th style="width: 8%; text-align: right;">ยอดหนี้</th>
                                <th style="width: 8%; text-align: right;">VAT</th>
                                <th style="width: 8%; text-align: right;">ยอดหนี้สุทธิ</th>
                                <th style="width: 5%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, debt) in tmpDebts">
                                <td style="text-align: center;">
                                    @{{ tmpDebts_pager.from + index }}
                                </td>
                                <td>
                                    <p>เลขที่ @{{ debt.withdraw_no }}</p>
                                    <p>ลวท. @{{ debt.withdraw_date | thdate }}</p>
                                </td>
                                <td>@{{ debt.supplier.supplier_name }}</td>
                                <td>@{{ debt.items }}, @{{ debt.desc }}</td>
                                <td style="text-align: center;">@{{ debt.deliver_no }}</td>
                                <td style="text-align: center;">@{{ debt.deliver_date | thdate }}</td>
                                <td style="text-align: right;">
                                    @{{ debt.amount | currency:'':2 }}
                                </td>
                                <td style="text-align: right;">
                                    @{{ debt.vat | currency:'':2 }}
                                </td>
                                <td style="text-align: right;">
                                    @{{ debt.total | currency:'':2 }}
                                </td>
                                <td style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-sm" ng-click="onSelectedTmpDebt($event, debt)">
                                        เลือก
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="loading-wrapper" ng-show="items.length === 0">
                        <!-- Loading (remove the following to stop the loading)-->
                        <div ng-show="loading" class="overlay">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <!-- end loading -->
                    </div>

                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="pull-left" style="margin-top: 5px;">
                                หน้า @{{ tmpDebts_pager.current_page }} จาก @{{ tmpDebts_pager.last_page }} | 
                                จำนวน @{{ tmpDebts_pager.total }} รายการ
                            </span>
                        </div>
                        <div class="col-md-4">
                            <ul class="pagination pagination-sm no-margin">
                                <li ng-if="tmpDebts_pager.current_page !== 1">
                                    <a ng-click="getItemsWithUrl($event, tmpDebts_pager.path+ '?page=1', setTmpDebts)" aria-label="Previous">
                                        <span aria-hidden="true">First</span>
                                    </a>
                                </li>

                                <li ng-class="{'disabled': (tmpDebts_pager.current_page==1)}">
                                    <a ng-click="getItemsWithUrl($event, tmpDebts_pager.prev_page_url, setTmpDebts)" aria-label="Prev">
                                        <span aria-hidden="true">Prev</span>
                                    </a>
                                </li>

                                <!-- <li ng-if="tmpDebts_pager.current_page < tmpDebts_pager.last_page && (tmpDebts_pager.last_page - tmpDebts_pager.current_page) > 10">
                                    <a href="@{{ tmpDebts_pager.url(tmpDebts_pager.current_page + 10) }}">
                                        ...
                                    </a>
                                </li> -->

                                <li ng-class="{'disabled': (tmpDebts_pager.current_page==tmpDebts_pager.last_page)}">
                                    <a ng-click="getItemsWithUrl($event, tmpDebts_pager.next_page_url, setTmpDebts)" aria-label="Next">
                                        <span aria-hidden="true">Next</span>
                                    </a>
                                </li>

                                <li ng-if="tmpDebts_pager.current_page !== tmpDebts_pager.last_page">
                                    <a ng-click="getItemsWithUrl($event, tmpDebts_pager.path+ '?page=' +tmpDebts_pager.last_page, setTmpDebts)" aria-label="Previous">
                                        <span aria-hidden="true">Last</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger" ng-click="onSelectedTmpDebt($event, null)">
                                ปิด
                            </button>
                        </div>
                    </div>
                </div><!-- /.modal-footer -->
            </form>
        </div>
    </div>
</div>
