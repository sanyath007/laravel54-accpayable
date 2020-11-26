app.controller('bankBranchCtrl', function($rootScope, $scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.pager = [];
    $scope.branches = [];
    $scope.branch = {
        bank_branch_id: '',
        bank_branch_name: '',
        bank_id: '',
        bank_branch_addr: '',
        bank_branch_tel: '',
        bank_branch_fax: '',
    };

    $scope.getData = function(event) {
        $scope.loading = true;
        $scope.branches = [];
        
        var searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();

        $http.get(`${CONFIG.baseUrl}/bank-branch/search/${searchKey}`)
        .then(function(res) {
            console.log(res);
            $scope.branches = res.data.branches.data;
            $scope.pager = res.data.branches;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;
        $scope.branches = [];

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.branches = res.data.branches.data;
            $scope.pager = res.data.branches;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.getBranch = function(bbId) {
        $scope.loading = true;

        $http.get(`${CONFIG.baseUrl}/bank-branch/get-branch/${bbId}`)
        .then(function(res) {
            console.log(res);
            $scope.branch = res.data.branch;
            
            $scope.branch.bank_id = $scope.branch.bank_id.toString();

            $scope.loading = false;
        }, function(err) {
            console.log(err);

            $scope.loading = false;
        });
    }

    $scope.add = function(event, form) {
        event.preventDefault();

        if (form.$invalid) {
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            $scope.loading = true;

            $http.post(`${CONFIG.baseUrl}/bank-branch/store`, $scope.branch)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
                
                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bank-branch/list');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });            
        }
    }

    $scope.edit = function(bbId) {
        console.log(bbId);

        window.location.href = `${CONFIG.baseUrl}/bank-branch/edit/${bbId}`;
    };

    $scope.update = function(event, form, baId) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + baId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.put(`${CONFIG.baseUrl}/bank-branch/update`, $scope.branch)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bank-branch/list');

            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };

    $scope.delete = function(bbId) {
        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + bbId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.delete(`${CONFIG.baseUrl}/bank-branch/delete/${bbId}`)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
                $scope.getData();

                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bank-branch/list');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };
});