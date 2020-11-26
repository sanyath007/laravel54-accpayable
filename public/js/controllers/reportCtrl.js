app.controller('reportCtrl', function(CONFIG, $scope, $http, toaster, PaginateService, StringFormatService) {
/** ################################################################################## */
    $scope.debts = [];
    $scope.pager = [];
    $scope.pages = [];
    $scope.loading = false;

    $scope.getDebtData = function(URL) {
        $scope.debts = [];
        $scope.pager = [];
        $scope.loading = true;
        
        let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
        let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
        let debtType = ($("#debtType").val() != '') ? $("#debtType").val() : 0;
        let showAll = $("#showall").is(":checked") ? 1 : 0;

        $http.get(`${CONFIG.baseUrl}/${URL}/${debtType}/${sDate}/${eDate}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.pager.data;
            $scope.pager = res.data.pager;

            $scope.pages = PaginateService.createPagerNo($scope.pager);

            console.log($scope.pages);
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        })
    }

    $scope.getDataWithURL = function(URL) {
        console.log(URL);
        $scope.debts = [];
        $scope.pager = [];
        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.pager.data;
            $scope.pager = res.data.pager;

            $scope.pages = PaginateService.createPagerNo($scope.pager);

            console.log($scope.pages);
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.debtCreditorToExcel = function() {
        console.log($scope.debts);

        if($scope.debts.length == 0) {
            toaster.pop('warning', "", "ไม่พบข้อมูล !!!");
        } else {
            let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
            let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
            let creditor = ($("#debtType").val() == '') ? '0' : $("#debtType").val();
            let showAll = $("#showall").is(":checked") ? 1 : 0;

            window.location.href = `${CONFIG.baseUrl}/report/debt-creditor/excel/${creditor}/${sDate}/${eDate}/${showAll}`;
        }
    };

    $scope.debttypeToExcel = function() {
        console.log($scope.debts);

        if($scope.debts.length == 0) {
            toaster.pop('warning', "", "ไม่พบข้อมูล !!!");
        } else {
            let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
            let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
            let debtType = ($("#debtType").val() == '') ? '0' : $("#debtType").val();
            let showAll = $("#showall").is(":checked") ? 1 : 0;
            
            window.location.href = `${CONFIG.baseUrl}/report/debt-debttype/excel/${debtType}/${sDate}/${eDate}/${showAll}`;
        }
    };

    $scope.getSumArrearData = function() {
        $scope.loading = true;

        $scope.debts = [];
        $scope.pager = [];

        let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
        let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
        let showAll = $("#showall").is(":checked") ? 1 : 0;
        
        $http.get(`${CONFIG.baseUrl}/report/sum-arrear/json/${sDate}/${eDate}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.pager.data;
            $scope.pager = res.data.pager;

            $scope.pages = PaginateService.createPagerNo($scope.pager);

            console.log($scope.pages);
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.sumArrearToExcel = function() {
        console.log($scope.debts);

        if($scope.debts.length == 0) {
            toaster.pop('warning', "", "ไม่พบข้อมูล !!!");
        } else {
            let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
            let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
            let showAll = $("#showall").is(":checked") ? 1 : 0;
            
            window.location.href = `${CONFIG.baseUrl}/report/sum-arrear/excel/${sDate}/${eDate}/${showAll}`;
        }
    };
});