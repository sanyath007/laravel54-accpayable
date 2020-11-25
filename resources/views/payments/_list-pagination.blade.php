<div class="box-footer clearfix">
    <div class="col-md-6" style="font-size: 12px;">
        Total @{{ pager.total | currency : "" : 0 }} รายการ
    </div>

    <ul class="pagination pagination-sm no-margin pull-right">

        <li ng-if="pager.current_page !== 1">
            <a ng-click="getDataWithURL(pager.path + '?page=1')" aria-label="Previous">
                <span aria-hidden="true">First</span>
            </a>
        </li>
    
        <li ng-class="{'disabled': (pager.current_page==1)}">
            <a ng-click="getDataWithURL(pager.prev_page_url)" aria-label="Prev">
                <span aria-hidden="true">Prev</span>
            </a>
        </li>
        
        <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
            <a href="@{{ pager.url(pager.current_page + 10) }}">
                ...
            </a>
        </li>
    
        <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
            <a ng-click="getDataWithURL(pager.next_page_url)" aria-label="Next">
                <span aria-hidden="true">Next</span>
            </a>
        </li>

        <li ng-if="pager.current_page !== pager.last_page">
            <a ng-click="getDataWithURL(pager.path+ '?page=' +pager.last_page)" aria-label="Previous">
                <span aria-hidden="true">Last</span>
            </a>
        </li>
    </ul>
</div><!-- /.box-footer -->