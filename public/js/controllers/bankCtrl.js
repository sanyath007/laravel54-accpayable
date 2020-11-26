app.controller('bankCtrl', function($rootScope, $scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.pager = [];
    $scope.banks = [];
    $scope.bank = {
        bank_id: '',
        bank_name: ''
    };

    $scope.getData = function(event) {
        $scope.loading = true;
        $scope.banks = [];
        
        var searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        console.log(`${CONFIG.baseUrl}/bank/search/${searchKey}`);

        $http.get(`${CONFIG.baseUrl}/bank/search/${searchKey}`)
        .then(function(res) {
            console.log(res);
            $scope.banks = res.data.banks.data;
            $scope.pager = res.data.banks;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;
        $scope.banks = [];

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.banks = res.data.banks.data;
            $scope.pager = res.data.banks;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.getBank = function(bankId) {
        $scope.loading = true;

        $http.get(`${CONFIG.baseUrl}/bank/get-bank/${bankId}`)
        .then(function(res) {
            console.log(res);
            $scope.bank = res.data.bank;

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

            $http.post(`${CONFIG.baseUrl}/bank/store`, $scope.bank)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bank/list');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });            
        }
    }

    $scope.edit = function(bankId) {
        console.log(bankId);

        window.location.href = `${CONFIG.baseUrl}/bank/edit/${bankId}`;
    };

    $scope.update = function(event, form, bankId) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + bankId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.put(`${CONFIG.baseUrl}/bank/update`, $scope.bank)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;

                /** Redirect to bank account list */
                $rootScope.redirectToIndex('bank/list');

            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };

    $scope.delete = function(bankId) {
        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + bankId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.delete(`${CONFIG.baseUrl}/bank/delete/${bankId}`)
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