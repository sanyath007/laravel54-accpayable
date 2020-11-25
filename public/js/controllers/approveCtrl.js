app.controller('approveCtrl', function($rootScope, $scope, $http, toaster, CONFIG, ModalService, StringFormatService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.supplierDebtData = [];
    $scope.supplierDebtToRemoveData = [];

    $scope.debttypes = [];
    $scope.debts = [];
    $scope.debtPager = [];

    $scope.approvements = [];
    $scope.pager = [];
    $scope.approve = {};

    $scope.approveData = {};
    $scope.cancelData = {};

    $('#approveToDate').datepicker({
        autoclose: true,
        orientation: 'bottom',
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).on('changeDate', function(event){
        if($("#approveFromDate").val() == '') {
            alert('กรุณาเลือกระหว่างวันที่ก่อน !!!');
        }

        $scope.getData();
    });

    $scope.initData = function() {
        $scope.approve = {
            creditor_id: '',
            app_doc_no: '',
            app_date: '',
            app_recdoc_no: '',
            app_recdoc_date: '',
            pay_to: '',
            budget_id: '',
            amount: '0.00',
            tax_val: '0.00',
            discount: '0.00',
            fine: '0.00',
            vatrate: '1',
            vatamt: '0.00',
            net_val: '0.00',
            net_amt: '0.00',
            net_amt_str: ' ตัวอักษร ',
            net_total: '0.00',
            net_total_str: ' ตัวอักษร ',
            cheque: '0.00',
            cheque_str: ' ตัวอักษร ',
            cr_user: '',
            chg_user: '',
            debts: [],
        };

        $scope.supplierDebtData = [];
        $scope.supplierDebtToRemoveData = [];

        $scope.debts = [];
        $scope.debtPager = [];
    }

    $scope.getData = function(event) {
        $scope.approvements = [];
        $scope.loading = true;
        
        let sDate = ($("#approveFromDate").val() != '') ? StringFormatService.convToDbDate($("#approveFromDate").val()) : 0;
        let eDate = ($("#approveToDate").val() != '') ? StringFormatService.convToDbDate($("#approveToDate").val()) : 0;
        var searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        let showAll = ($("#showall:checked").val() == 'on') ? 1 : 0;

        $http.get(`${CONFIG.baseUrl}/approve/search/${sDate}/${eDate}/${searchKey}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.approvements = res.data.approvements.data;
            $scope.pager = res.data.approvements;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;

        console.log(URL);
        $scope.approvements = [];

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.approvements = res.data.approvements.data;
            $scope.pager = res.data.approvements;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }
    
    $scope.getApprove = function(approveId) {
        $http.get(`${CONFIG.baseUrl}/approve/get-creditor/${approveId}`)
        .then(function(res) {
            console.log(res);
            $scope.creditor = res.data.creditor;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.store = function(event, form) {
        event.preventDefault();

        if (form.$invalid) {
            console.log(form.$error);
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            /** Convert thai date to db date. */
            $scope.approve.app_date = StringFormatService.convToDbDate($scope.approve.app_date);
            $scope.approve.app_recdoc_date = StringFormatService.convToDbDate($scope.approve.app_recdoc_date);
            /** Get user id */
            $scope.approve.cr_user = $("#user").val();
            $scope.approve.chg_user = $("#user").val();
            /** Add debt to approvement */
            $scope.approve.debts =  $scope.supplierDebtData;
            console.log($scope.approve);

            $http.post(`${CONFIG.baseUrl}/approve/store`, $scope.approve)
                .then(function(res) {
                    console.log(res);
                    toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
                }, function(err) {
                    console.log(err);
                    toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
                });            
        }

        /** Redirect to approve list */
        $rootScope.redirectToIndex('approve/list');
    }

    $scope.edit = function(approveId) {
        console.log(approveId);

        window.location.href = `${CONFIG.baseUrl}/approve/edit/${approveId}`;
    };

    $scope.update = function(event, form, approveId) {
        console.log(approveId);
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขเจ้าหนี้เลขที่ " + approveId + " ใช่หรือไม่?")) {
            $http.put(`${CONFIG.baseUrl}/approve/update`, $scope.approve)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };

    $scope.delete = function(approveId) {
        console.log(approveId);

        if(confirm("คุณต้องลบเจ้าหนี้เลขที่ " + approveId + " ใช่หรือไม่?")) {
            $http.delete(`${CONFIG.baseUrl}/approve/delete/${approveId}`)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
                $scope.getData();
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };

    $scope.showSupplierDebtList = function(event) {
        $scope.loading = true;
        let creditor = $("#creditor_id").val();

        if (!creditor) {
            toaster.pop('error', "", 'กรุณาเลือกเจ้าหนี้ก่อน !!!');
            return;
        }

        $http.get(`${CONFIG.baseUrl}/debt/${creditor}/list`)
            .then(function (res) {
                console.log(res);
                $scope.getSupplierDebtData(res.data.debts.data, res.data.debts);

                $('#dlgSupplierDebtList').modal('show');

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                $scope.loading = false;
            });
    };

    $scope.getSupplierDebtData = function(data, pager) {
        let resData = data;
        $scope.debtPager = pager;

        if ($scope.supplierDebtData.length > 0) {
            $scope.debts = resData.filter(function(d) {
                let tmp = [];
                angular.forEach($scope.supplierDebtData, function(sd) {
                    tmp.push(sd.debt_id);
                });

                return tmp.indexOf(d.debt_id) === -1;
            });
        } else {
            $scope.debts = resData;
        }
    }

    $scope.getSupplierDebtDataWithURL = function(URL) {
        $scope.loading = true;

        console.log(URL);

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.getSupplierDebtData(res.data.debts.data, res.data.debts)

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.addSupplierDebtData = function(event, supplierDebt) {
        if ($(event.target).is(':checked')) {            
            $scope.supplierDebtData.push(supplierDebt);
        } else {
            let removeIndex = $scope.supplierDebtData.findIndex(function(debt) {
                return debt.debt_id === supplierDebt.debt_id;
            });

            $scope.supplierDebtData.splice(removeIndex, 1);
        }

        calculateSupplierDebt();
    };

    $scope.addSupplierDebtToRemove = function(event, debt) {
        console.log($scope.supplierDebtData);
        let tmp = [];

        if ($(event.target).is(':checked')) {            
            $scope.supplierDebtToRemoveData.push(debt.debt_id);
            console.log($scope.supplierDebtToRemoveData);
        } else {
            $scope.supplierDebtToRemoveData.splice(debt.debt_id, 1)
        }
    }

    $scope.removeSupplierDebt = function() {
        if ($scope.supplierDebtToRemoveData.length < 1) {
            toaster.pop('error', "", 'ไม่พบรายการที่คุณต้องการลบ กรุณาเลือกรายการก่อน !!!');
            return;
        }

        tmp = $scope.supplierDebtData.filter(function(d) {
            return $scope.supplierDebtToRemoveData.indexOf(d.debt_id);
        });
        
        $scope.supplierDebtData = tmp;
        calculateSupplierDebt();

        $scope.supplierDebtToRemoveData = [];
    };

    $scope.clearSupplierDebtData = function() {
        if ($scope.supplierDebtData.length > 0) {
            $scope.supplierDebtData = [];
        }

        /** Set supplier name to approve.pay_to */
        $scope.approve.pay_to = $("#creditor_id option:selected").text().trim();
    };

    $scope.popupApproveDebtList = function(appData) {
        $scope.loading = true;
        $scope.approveData = appData;

        $http.get(`${CONFIG.baseUrl}/approve/detail/${appData.app_id}`)
        .then(function (res) {
            console.log(res);
            $scope.debts = res.data.appdetails;
            $scope.debttypes = res.data.debttypes;

            $('#dlgApproveDebtList').modal('show');
            
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.popupCancelForm = function(appid) {
        console.log(appid);
        $scope.cancelData.app_id = appid;

        $('#dlgCancelForm').modal('show');
    };

    $scope.doCancel = function(e, form) {
        e.preventDefault();

        if (form.$invalid) {
            toaster.pop('warning', "", 'กรุณาระบุเหตุผลการยกเลิกก่อน !!!');
            return;
        } else {
            if(confirm("คุณต้องยกเลิกรายการขออนุมัติเลขที่ " + $scope.cancelData.app_id + " ใช่หรือไม่?")) {
                $scope.loading = true;
                $scope.cancelData.user = $("#user").val();

                $http.post(`${CONFIG.baseUrl}/approve/cancel`, $scope.cancelData)
                .then(function (res) {
                    console.log(res);
                    toaster.pop('success', "", 'ยกเลิกรายการเรียบร้อยแล้ว !!!');

                    $scope.approvements = $scope.approvements.filter(app => app.app_id !== $scope.cancelData.app_id);

                    $scope.loading = false;
                }, function(err) {
                    console.log(err);
                    toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                    $scope.loading = false;
                });

                $('#dlgCancelForm').modal('hide');
            }
        }
    }

    function calculateSupplierDebt() {
        let vatRate = $("#vatrate").val();
        let vatAmt  = 0.0;
        let taxVal  = 0.0;
        let netVal  = 0.0;
        let netTotal = 0.0;
        let cheque = 0.0;

        angular.forEach($scope.supplierDebtData, function(debt) {
            vatAmt += debt.debt_vat;
            netVal += debt.debt_amount;
        });

        taxVal = (netVal * 1) / 100;
        netTotal = netVal + vatAmt;
        cheque = netTotal - taxVal;

        $scope.approve.amount = StringFormatService.currencyFormat(netVal); // ฐานภาษี
        $scope.approve.net_val = StringFormatService.currencyFormat(netVal); // ฐานภาษี
        $scope.approve.vatamt = StringFormatService.currencyFormat(vatAmt); // ภาษีมูลค่าเพิ่ม
        $scope.approve.tax_val = StringFormatService.currencyFormat(taxVal); // ภาษีหัก ณ ที่จ่าย
        $scope.approve.net_amt = StringFormatService.currencyFormat(taxVal); // ภาษีหัก ณ ที่จ่าย
        $scope.approve.net_total = StringFormatService.currencyFormat(netTotal); // ยอดสุทธิ
        $scope.approve.cheque = StringFormatService.currencyFormat(cheque); // ยอดจ่ายเช็ค

        $scope.approve.net_amt_str = ArabicNumberToText(taxVal.toFixed(2)); // ภาษีหัก ณ ที่จ่าย
        $scope.approve.net_total_str = ArabicNumberToText(netTotal.toFixed(2)); // ยอดสุทธิ
        $scope.approve.cheque_str = ArabicNumberToText(cheque.toFixed(2)); // ยอดจ่ายเช็ค
    }
});