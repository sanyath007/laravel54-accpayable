<div class="modal fade" id="suppliers-list" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h5 class="modal-title">รายการเจ้าหนี้</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body" style="padding-bottom: 0;">
                    <!-- // TODO: Filtering controls -->
                    <div class="box">
                        <div class="box-body">
                            <div style="display: flex; gap: 5px;">
                                <input
                                    type="text"
                                    ng-model="searchKey"
                                    class="form-control"
                                    ng-keyup="handleInputChange('searchKey', searchKey); getItems();"
                                />
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                    <!-- // TODO: Filtering controls -->

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center;">#</th>
                                <th style="width: 5%; text-align: center;">รหัส</th>
                                <th>เจ้าหนี้</th>
                                <th style="width: 30%; text-align: center;">ที่อยู่</th>
                                <th style="width: 12%; text-align: center;">เลขที่ใบกำกับภาษี</th>
                                <th style="width: 5%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, supplier) in suppliers">
                                <td style="text-align: center;">
                                    @{{ suppliers_pager.from + index }}
                                </td>
                                <td>@{{ supplier.supplier_id }}</td>
                                <td>@{{ supplier.supplier_name }}</td>
                                <td>@{{ supplier.supplier_address1 }}</td>
                                <td style="text-align: center;">@{{ supplier.supplier_taxid }}</td>
                                <td style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-sm" ng-click="onSelectedSupplier($event, supplier)">
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
                                หน้า @{{ suppliers_pager.current_page }} จาก @{{ suppliers_pager.last_page }} | 
                                จำนวน @{{ suppliers_pager.total }} รายการ
                            </span>
                        </div>
                        <div class="col-md-4">
                            <ul class="pagination pagination-sm no-margin">
                                <li ng-if="suppliers_pager.current_page !== 1">
                                    <a ng-click="getItemsWithUrl($event, suppliers_pager.path+ '?page=1', setSuppliers)" aria-label="Previous">
                                        <span aria-hidden="true">First</span>
                                    </a>
                                </li>

                                <li ng-class="{'disabled': (suppliers_pager.current_page==1)}">
                                    <a ng-click="getItemsWithUrl($event, suppliers_pager.prev_page_url, setSuppliers)" aria-label="Prev">
                                        <span aria-hidden="true">Prev</span>
                                    </a>
                                </li>

                                <!-- <li ng-if="suppliers_pager.current_page < suppliers_pager.last_page && (suppliers_pager.last_page - suppliers_pager.current_page) > 10">
                                    <a href="@{{ suppliers_pager.url(suppliers_pager.current_page + 10) }}">
                                        ...
                                    </a>
                                </li> -->

                                <li ng-class="{'disabled': (suppliers_pager.current_page==suppliers_pager.last_page)}">
                                    <a ng-click="getItemsWithUrl($event, suppliers_pager.next_page_url, setSuppliers)" aria-label="Next">
                                        <span aria-hidden="true">Next</span>
                                    </a>
                                </li>

                                <li ng-if="suppliers_pager.current_page !== suppliers_pager.last_page">
                                    <a ng-click="getItemsWithUrl($event, suppliers_pager.path+ '?page=' +suppliers_pager.last_page, setSuppliers)" aria-label="Previous">
                                        <span aria-hidden="true">Last</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger" ng-click="onSelectedSupplier($event, null)">
                                ปิด
                            </button>
                        </div>
                    </div>
                </div><!-- /.modal-footer -->
            </form>
        </div>
    </div>
</div>
