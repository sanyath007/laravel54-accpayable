app.controller('bankAccCtrl', function($rootScope, $scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.pager = [];
    $scope.bankaccs = [];
    $scope.bankacc = {
        bank_acc_id: '',
        bank_acc_no: '',
        bank_acc_name: '',
        bank_id: '',
        bank_branch_id: '',
        bank_type_id: '',
        company_id: '01'
    };

    $scope.getData = function(event) {
        $scope.loading = true;
        $scope.debttypes = [];
        
        var searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        console.log(`${CONFIG.baseUrl}/bankacc/search/${searchKey}`);

        $http.get(`${CONFIG.baseUrl}/bankacc/search/${searchKey}`)
        .then(function(res) {
            console.log(res);
            $scope.bankaccs = res.data.bankaccs.data;
            $scope.pager = res.data.bankaccs;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;
        $scope.bankaccs = [];

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.bankaccs = res.data.bankaccs.data;
            $scope.pager = res.data.bankaccs;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.getBankAcc = function(baId) {
        $scope.loading = true;

        $http.get(`${CONFIG.baseUrl}/bankacc/get-bankacc/${baId}`)
        .then(function(res) {
            console.log(res);
            $scope.bankacc = res.data.bankacc;
            
            $scope.bankacc.bank_id = $scope.bankacc.bank_id.toString();
            $scope.bankacc.bank_branch_id = $scope.bankacc.bank_branch_id.toString();
            $scope.bankacc.bankacc_type_id = $scope.bankacc.bankacc_type_id.toString();

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
            $http.post(`${CONFIG.baseUrl}/bankacc/store`, $scope.bankacc)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });            
        }

        /** Redirect to bank account list */
        $rootScope.redirectToIndex('bankacc/list');
    }

    $scope.edit = function(baId) {
        console.log(baId);

        window.location.href = `${CONFIG.baseUrl}/bankacc/edit/${baId}`;
    };

    $scope.update = function(event, form, baId) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + baId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.put(`${CONFIG.baseUrl}/bankacc/update`, $scope.bankacc)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bankacc/list');

            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };

    $scope.delete = function(baId) {
        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + baId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.delete(`${CONFIG.baseUrl}/bankacc/delete/${baId}`)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
                $scope.getData();

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };
});