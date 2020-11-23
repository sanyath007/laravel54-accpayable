app.controller('paymentCtrl', function($scope, $http, toaster, CONFIG, ModalService, StringFormatService) {
/** ################################################################################## */
    $scope.loading = false;

    $scope.supplierApproveData = [];
    $scope.supplierApproveToRemoveData = [];

    $scope.debttypes = [];
    $scope.approvements = [];
    $scope.approvePager = [];
    $scope.debtDetail = {};

    $scope.payment = {};
    $scope.payments = [];
    $scope.pager = [];

    $('#paymentToDate').datepicker({
        autoclose: true,
        orientation: 'bottom',
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).on('changeDate', function(event){
        if($("#paymentFromDate").val() == '') {
            alert('กรุณาเลือกระหว่างวันที่ก่อน !!!');
        }

        $scope.getData();
    });

    $scope.initData = function() {
        $scope.payment = {
            payment_id: '',
            paid_date: '',
            paid_doc_no: '',
            cheque_no: '',
            cheque_date: '',
            bank_acc_id: '',
            creditor_id: '',
            pay_to: '',
            cheque_receiver: '',
            // paid_empid: '',
            budget_id: '',
            // tax_type_id: '',
            paid_num: '',
            remark: '',
            net_amt: '0.00',
            net_val: '0.00',
            net_total: '0.00',
            fine: '0.00',
            discount: '0.00',
            remain: '0.00',
            paid_amt: '0.00',
            total: '0.00',
            totalstr: ' ตัวอักษร ',
            // computer: '',
            cr_userid: '',
            chg_userid: '',
            app_debts: [],
        };
    }

    $scope.getData = function(event) {
        $scope.loading = true;
        
        $scope.payments = [];
        
        let sDate = ($("#paymentFromDate").val() != '') ? StringFormatService.convToDbDate($("#paymentFromDate").val()) : 0;
        let eDate = ($("#paymentToDate").val() != '') ? StringFormatService.convToDbDate($("#paymentToDate").val()) : 0;
        var searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        let showAll = ($("#showall:checked").val() == 'on') ? 1 : 0;

        $http.get(`${CONFIG.baseUrl}/payment/search/${sDate}/${eDate}/${searchKey}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.payments = res.data.payments.data;
            $scope.pager = res.data.payments;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;
        $scope.payments = [];

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.payments = res.data.payments.data;
            $scope.pager = res.data.payments;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.gePayment = function(paymentId) {
        $scope.loading = true;
        
        $http.get(`${CONFIG.baseUrl}/payment/get-payment/${paymentId}`)
        .then(function(res) {
            console.log(res);
            $scope.payment = res.data.payment;

            $scope.loading = false;
    	}, function(err) {
            console.log(err);
            
            $scope.loading = false;
        });
    }

    $scope.edit = function(creditorId) {
        console.log(creditorId);

        window.location.href = `${CONFIG.baseUrl}/payment/edit/${paymentId}`;
    };

    $scope.store = function(event, form) {
        event.preventDefault();

        if (form.$invalid) {
            console.log(form.$error);
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            $scope.loading = true;
            /** Get supplier name */
            $scope.payment.pay_to = $('#creditor_id option:selected').text().trim();
            /** Convert thai date to db date. */
            $scope.payment.paid_date = StringFormatService.convToDbDate($scope.payment.paid_date);
            $scope.payment.cheque_date = StringFormatService.convToDbDate($scope.payment.cheque_date);
            /** Format to number */
            $scope.payment.net_val = StringFormatService.numberFormat($scope.payment.net_val);
            $scope.payment.net_amt = StringFormatService.numberFormat($scope.payment.net_amt);
            $scope.payment.net_total = StringFormatService.numberFormat($scope.payment.net_total);
            $scope.payment.discount = StringFormatService.numberFormat($scope.payment.discount);
            $scope.payment.fine = StringFormatService.numberFormat($scope.payment.fine);
            $scope.payment.remain = StringFormatService.numberFormat($scope.payment.remain);
            $scope.payment.total = StringFormatService.numberFormat($scope.payment.total);
            $scope.payment.paid_amt = StringFormatService.numberFormat($scope.payment.paid_amt);
            /** Get number of debts */
            $scope.payment.paid_num = $scope.payment.app_debts.length;
            /** Get user id */
            $scope.payment.cr_userid = $("#user").val();
            $scope.payment.chg_userid = $("#user").val();
            console.log($scope.payment);

            $http.post(`${CONFIG.baseUrl}/payment/store`, $scope.payment)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }

        /** Redirect to payment list */
        setTimeout(function (){
            window.location.href = `${CONFIG.baseUrl}/payment/list`;
        }, 2000);
    }

    $scope.edit = function(paymentId) {
        console.log(paymentId);

        window.location.href = `${CONFIG.baseUrl}/payment/edit/${paymentId}`;
    };

    $scope.update = function(event, form, paymentId) {
        console.log(paymentId);
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขเจ้าหนี้เลขที่ " + paymentId + " ใช่หรือไม่?")) {
            $scope.loading = true;
            /** Convert thai date to db date. */
            $scope.payment.paid_date = StringFormatService.numberFormat($scope.payment.paid_date);
            $scope.payment.cheque_date = StringFormatService.numberFormat($scope.payment.cheque_date);
            /** Format to number */
            $scope.payment.net_val = StringFormatService.numberFormat($scope.payment.net_val);
            $scope.payment.net_amt = StringFormatService.numberFormat($scope.payment.net_amt);
            $scope.payment.net_total = StringFormatService.numberFormat($scope.payment.net_total);
            $scope.payment.discount = StringFormatService.numberFormat($scope.payment.discount);
            $scope.payment.fine = StringFormatService.numberFormat($scope.payment.fine);
            $scope.payment.remain = StringFormatService.numberFormat($scope.payment.remain);
            $scope.payment.total = StringFormatService.numberFormat($scope.payment.total);
            $scope.payment.paid_amt = StringFormatService.numberFormat($scope.payment.paid_amt);
            /** Get number of debts */
            $scope.payment.paid_num = $scope.payment.app_debts.length;
            /** Get user id */
            $scope.payment.chg_userid = $("#user").val();
            console.log($scope.payment);

            $http.put(`${CONFIG.baseUrl}/payment/update`, $scope.payment)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    };

    $scope.delete = function(paymentId) {
        console.log(paymentId);

        if(confirm("คุณต้องลบเจ้าหนี้เลขที่ " + paymentId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.delete(`${CONFIG.baseUrl}/payment/delete/${paymentId}`)
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

    $scope.popupApproveSelection = function(event) {
        $scope.loading = true;
        $scope.approvements = [];

        let creditor = $("#creditor_id").val();

        if (!creditor) {
            toaster.pop('error', "", 'กรุณาเลือกเจ้าหนี้ก่อน !!!');
            return;
        }

        $http.get(`${CONFIG.baseUrl}/approve/get-all-bysupplier/${creditor}`)
        .then(function (res) {
            // console.log(res);
            $scope.approvements = res.data.approvements.data;
            $scope.approvePager = res.data.approvements;

            $('#dlgApproveSelection').modal('show');

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

            $scope.loading = false;
        });
    };

    $scope.getSupplierApproveDataWithURL = function(URL) {
        $scope.loading = true;
        console.log(URL);

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.approvements = res.data.approvements.data;
            $scope.approvePager = res.data.approvements;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getApproveDebtsData = function(data) {
        let detail = data.app_detail;
        console.log(detail);

        detail.forEach(ad => {            
            $http.get(`${CONFIG.baseUrl}/debt/get-debt/${ad.debt_id}`)
            .then(res => {
                console.log(res);
                $scope.payment.app_debts.push({
                    app_detail_id: ad.app_detail_id,
                    app_id: ad.app_id,
                    debt_id: ad.debt_id,
                    debt_date: res.data.debt.debt_date,
                    deliver_no: res.data.debt.deliver_no,
                    debt_amount: res.data.debt.debt_amount,
                    debt_vatrate: res.data.debt.debt_vatrate,
                    debt_vat: res.data.debt.debt_vat,
                    debt_total: res.data.debt.debt_total
                });
            }, err => {
                console.log(err);
            });
        });
    }

    $scope.addSupplierApproveData = function(event, supplierApprove) {
        if ($(event.target).is(':checked')) {            
            $scope.supplierApproveData.push(supplierApprove);

            $scope.getApproveDebtsData(supplierApprove);
        } else {
            let removeIndex = $scope.supplierApproveData.findIndex(approve => {
                return approve.app_id === supplierApprove.app_id;
            });

            $scope.supplierApproveData.splice(removeIndex, 1);
        }

        calculateSupplierApprove();
    };

    $scope.addSupplierApproveToRemove = function(event, debt) {
        console.log($scope.supplierApproveData);
        let tmp = [];

        if ($(event.target).is(':checked')) {            
            $scope.supplierApproveToRemoveData.push(debt.debt_id);
            console.log($scope.supplierApproveToRemoveData);
        } else {
            $scope.supplierApproveToRemoveData.splice(approve.app_id, 1)
        }
    }

    $scope.removeSupplierApprove = function() {
        if ($scope.supplierApproveToRemoveData.length < 1) {
            toaster.pop('error', "", 'ไม่พบรายการที่คุณต้องการลบ กรุณาเลือกรายการก่อน !!!');
            return;
        }

        tmp = $scope.supplierApproveData.filter(approve => {
            return $scope.supplierApproveToRemoveData.indexOf(approve.app_id);
        });
        
        $scope.supplierApproveData = tmp;
        // calculateSupplierApprove();

        $scope.supplierApproveToRemoveData = [];
    };

    $scope.showApproveDebtDetail = function(event, debtId) {
        event.preventDefault();
        $scope.debtDetail = {};

        $scope.debtDetail = $scope.payment.app_debts.filter(debt => debt.debt_id == debtId);

        $('#dlgApproveDebtDetail').modal('show');
    }

    $scope.clearSupplierApproveData = function() {
        if ($scope.supplierApproveData.length > 0) {
            $scope.supplierApproveData = [];
        }

        /** Set supplier name to approve.pay_to */
        // $scope.approve.pay_to = $("#creditor_id option:selected").text().trim();
    };

    $scope.popupApproveDebtList = function(appid) {
        $scope.loading = true;

        $http.get(`${CONFIG.baseUrl}/payment/detail/${appid}`)
        .then(res => {
            console.log(res);
            $scope.debts = res.data.appdetails;
            $scope.debttypes = res.data.debttypes;

            $('#dlgPaymentDebtList').modal('show');
            
            $scope.loading = false;
        }, err => {
            console.log(err);
            $scope.loading = false;
        });
    };

    function calculateSupplierApprove() {
        let netVal  = 0.0; // ฐานภาษี
        // let vatRate = $("#vatrate").val(); // อัตราภาษีมูลค่าเพิ่ม
        // let vatAmt  = 0.0; // ภาษีมูลค่าเพิ่ม
        let netTotal = 0.0; // ยอดหนี้สุทธิ
        let taxVal  = 0.0; // ภาษีหัก ณ ที่จ่าย
        let total = 0.0; // ยอดจ่ายเช็ค

        angular.forEach($scope.supplierApproveData, function(approvement) {
            netVal += parseFloat(approvement.amount);
            netTotal += parseFloat(approvement.net_total);
            taxVal += parseFloat(approvement.tax_val);
            total += parseFloat(approvement.cheque);
        });

        $scope.payment.net_val = StringFormatService.currencyFormat(netVal); // ฐานภาษี
        // $scope.payment.vatamt = StringFormatService.currencyFormat(vatAmt); // ภาษีมูลค่าเพิ่ม
        $scope.payment.net_total = StringFormatService.currencyFormat(netTotal); // ยอดหนี้สุทธิ
        $scope.payment.net_amt = StringFormatService.currencyFormat(taxVal); // ภาษีหัก ณ ที่จ่าย
    
        $scope.payment.paid_amt = StringFormatService.currencyFormat(total); // ยอดจ่าย
        $scope.payment.total = StringFormatService.currencyFormat(total); // ยอดจ่าย
        $scope.payment.totalstr = ArabicNumberToText(total.toFixed(2)); // ยอดจ่าย (ตัวอักษร)
    }
});