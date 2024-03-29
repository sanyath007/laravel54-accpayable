app.controller('debtCtrl', function($rootScope, $scope, $http, CONFIG, toaster, StringFormatService, ReportService, PaginateService) {
/** ################################################################################## */
    $scope.loading = false;

    $scope.cboSupplier = "";
    $scope.txtKeyword = "";

    $scope.debts = [];
    $scope.apps = [];
    $scope.paids = [];
    $scope.setzeros = [];

    $scope.pager = {};
    $scope.appPager = {};
    $scope.paidPager = {};
    $scope.setzeroPager = {};

    $scope.pages = [];
    $scope.appPages = [];
    $scope.paidPages = [];
    $scope.setzeroPages = [];

    $scope.totalDebt = 0.00;

    $scope.debt = {
        debt_date: '',
        debt_doc_recno: '',
        debt_doc_recdate: '',
        deliver_no: '',
        deliver_date: '',
        debt_doc_no: '',
        debt_doc_date: '',
        withdraw_id:  '',
        po_no: '',
        po_date:  '',
        debt_type_id: '',
        debt_type_detail: '',
        debt_month: '',
        debt_year: '',
        supplier_id: '',
        supplier_name: '',
        doc_receive: '',
        debt_amount: '',
        debt_vatrate: '',
        debt_vat: '',
        debt_total: '',
        debt_remark: '',
        debt_creby: '',
        debt_userid: ''
    };

    $scope.barOptions = {};

    const dptDateOptions = {
        autoclose: true,
        orientation: 'bottom',
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    };

    $('#debtToDate').datepicker(dptDateOptions).on('changeDate', function(event){
        if($("#debtFromDate").val() == '') {
            alert('กรุณาเลือกระหว่างวันที่ก่อน !!!');
        }

        if($('#showall').is(":checked")) $('#showall').prop("checked", false);
        
        $scope.getData();
    });

    $scope.clearDebtObj = function() {
        $scope.debt.debt_date = '';
        $scope.debt.debt_doc_recno = '';
        $scope.debt.debt_doc_recdate = '';
        $scope.debt.deliver_no = '';
        $scope.debt.deliver_date = '';
        $scope.debt.debt_doc_no = '';
        $scope.debt.debt_doc_date = '';
        $scope.debt.withdraw_id = '';
        $scope.debt.po_no ='';
        $scope.debt.po_date = '';
        $scope.debt.debt_type_id = '';
        $scope.debt.debt_type_detail = '';
        $scope.debt.debt_month = '';
        $scope.debt.debt_year = '';
        $scope.debt.doc_receive = '';
        $scope.debt.debt_amount = '';
        $scope.debt.debt_vatrate = '';
        $scope.debt.debt_vat = '';
        $scope.debt.debt_total = '';
        $scope.debt.debt_remark = '';
        $scope.debt.debt_creby = '';
        $scope.debt.debt_userid = '';

        $('#debt_type_id').val('').trigger('change.select2');
    };

    $scope.getData = function() {
        $scope.loading = true;

        $scope.debts = [];
        $scope.pager = {};
        $scope.pages = [];

        let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
        let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
        let supplier = ($scope.cboSupplier != '') ? $scope.cboSupplier : 0;
        let showAll = $('#showall').is(":checked") ? 1 : 0;

        $http.get(`${CONFIG.baseUrl}/debt/search/json/${sDate}/${eDate}/${supplier}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.debts.data;
            $scope.pager = res.data.debts;
            $scope.pages = PaginateService.createPagerNo($scope.pager);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.loading = true;
        
        $scope.debts = [];
        $scope.pager = [];
        $scope.pages = [];


        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.debts.data;
            $scope.pager = res.data.debts;
            $scope.pages = PaginateService.createPagerNo($scope.pager);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getAppWithURL = function(URL) {
        console.log(URL);
        $scope.apps = [];
        $scope.appPager = [];
        $scope.appPages = [];

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.apps = res.data.apps.data;
            $scope.appPager = res.data.apps;
            $scope.appPages = PaginateService.createPagerNo($scope.appPager);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getPaidWithURL = function(URL) {
        console.log(URL);
        $scope.paids = [];
        $scope.paidPager = [];
        $scope.paidPages = [];

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.paids = res.data.paids.data;
            $scope.paidPager = res.data.paids;
            $scope.paidPages = PaginateService.createPagerNo($scope.paidPager);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getSetzeroWithURL = function(URL) {
        console.log(URL);
        $scope.setzeros = [];
        $scope.setzeroPager = [];
        $scope.setzeroPages = [];
        
        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.setzeros = res.data.setzeros.data;
            $scope.setzeroPager = res.data.setzeros;
            $scope.setzeroPages = PaginateService.createPagerNo($scope.setzeroPager);
            
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.suppliers = [];
    $scope.suppliers_pager = null;
    $scope.showSuppliersList = function() {
        $http.get(`${CONFIG.apiUrl}/creditors`)
        .then(res => {
            $scope.setSuppliers(res);

            $('#suppliers-list').modal('show');
        }, err => {
            console.log(err);
        });
    };

    $scope.getSuppliers = function() {
        $scope.suppliers = [];
        $scope.suppliers_pager = [];
        $scope.loading = true;

        let name = $scope.txtKeyword == '' ? '' : $scope.txtKeyword;

        $http.get(`${CONFIG.apiUrl}/creditors?name=${name}`)
        .then(function(res) {
            $scope.setSuppliers(res);
            
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getSuppliersWithUrl = function(e, url, cb) {
        /** Check whether parent of clicked a tag is .disabled just do nothing */
        if ($(e.currentTarget).parent().is('li.disabled')) return;

        $scope.suppliers = [];
        $scope.suppliers_pager = [];
        $scope.loading = true;

        let name = $scope.txtKeyword == '' ? '' : $scope.txtKeyword;

        console.log(url);
        $http.get(url+ `&name=${name}`)
        .then(function(res) {
            cb(res);
            
            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.setSuppliers = function(res) {
        const { data, ...pager } = res.data.creditors;

        $scope.suppliers = data;
        $scope.suppliers_pager = pager;
    };

    $scope.onSelectedSupplier = function(e, supplier) {
        if (supplier) {
            $scope.debt.supplier_id = supplier.supplier_id;
            $scope.debt.supplier_name = supplier.supplier_name;
        }

        $('#suppliers-list').modal('hide');
    };

    $scope.isTmpDebt = false;
    $scope.tmpDebts = [];
    $scope.tmpDebts_pager = null;
    $scope.showTmpDebtsList = function() {
        $scope.isTmpDebt = false;

        $http.get(`${CONFIG.apiUrl}/tmp-debts`)
        .then(res => {
            $scope.setTmpDebts(res);

            $('#tmp-debts-list').modal('show');
        }, err => {
            console.log(err);
        });
    };

    $scope.getTmpDebts = function() {
        $scope.loading = true;
        $scope.tmpDebts = [];
        $scope.tmpDebts_pager = null;

        let supplier = $scope.cboSupplier == "" ? '' : $scope.cboSupplier;
        let deliver_no = $scope.txtKeyword == "" ? '' : $scope.txtKeyword;

        $http.get(`${CONFIG.apiUrl}/tmp-debts?supplier=${supplier}&deliver_no=${deliver_no}`)
        .then(res => {
            $scope.setTmpDebts(res);

            $scope.loading = false;
        }, err => {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getTmpDebtsWithUrl = function(e, url, cb) {
        /** Check whether parent of clicked a tag is .disabled just do nothing */
        if ($(e.currentTarget).parent().is('li.disabled')) return;

        $scope.loading = true;
        $scope.tmpDebts = [];
        $scope.tmpDebts_pager = null;

        let supplier = $scope.cboSupplier == "" ? '' : $scope.cboSupplier;
        let deliver_no = $scope.txtKeyword == "" ? '' : $scope.txtKeyword;

        $http.get(`${url}&supplier=${supplier}&deliver_no=${deliver_no}`)
        .then(res => {
            cb(res);

            $scope.loading = false;
        }, err => {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.setTmpDebts = function(res) {
        const { data, ...pager } = res.data.debts;

        $scope.tmpDebts = data;
        $scope.tmpDebts_pager = pager;
    };

    $scope.onSelectedTmpDebt = function(e, debt) {
        if (debt) {
            $scope.isTmpDebt = true;

            $scope.debt.debt_date = StringFormatService.convFromDbDate(moment().format('YYYY-MM-DD'));
            $scope.debt.withdraw_id = debt.withdraw_id;
            $scope.debt.debt_doc_no = debt.withdraw_no;
            $scope.debt.po_no = debt.po_no;
            $scope.debt.po_date = debt.po_date;
            $scope.debt.deliver_no = debt.deliver_no;
            $scope.debt.deliver_date = StringFormatService.convFromDbDate(debt.deliver_date);
            $scope.debt.debt_year = debt.year;
            // $scope.debt.debt_type_detail = `${debt.items}, ${debt.desc}`;
            $scope.debt.supplier_id = debt.supplier.supplier_id.toString();
            $scope.debt.supplier_name = debt.supplier.supplier_name;
            $scope.debt.debt_amount = debt.amount;
            $scope.debt.debt_vatrate = debt.vatrate;
            $scope.debt.debt_vat = debt.vat;
            $scope.debt.debt_total = debt.total;
            $scope.debt.debt_remark = debt.remark;

            /** Update value of all datepicker input */
            $('#debt_date')
                .datepicker(dptDateOptions)
                .datepicker('update', moment().toDate());

            $('#deliver_date')
                .datepicker(dptDateOptions)
                .datepicker('update', moment(debt.deliver_date).toDate());

            $('#debt_doc_date')
                .datepicker(dptDateOptions)
                .datepicker('update', moment(debt.withdraw_date).toDate());
        }

        $('#tmp-debts-list').modal('hide');
    };

    $scope.getDebtChart = function (creditorId) {
        ReportService.getSeriesData('/report/debt-chart/', creditorId)
        .then(function(res) {
            console.log(res);

            var debtSeries = [];
            var paidSeries = [];
            var setzeroSeries = [];

            angular.forEach(res.data, function(value, key) {
                let debt = (value.debt) ? parseFloat(value.debt.toFixed(2)) : 0;
                let paid = (value.paid) ? parseFloat(value.paid.toFixed(2)) : 0;
                let setzero = (value.setzero) ? parseFloat(value.setzero.toFixed(2)) : 0;
                
                debtSeries.push(debt);
                paidSeries.push(paid);
                setzeroSeries.push(setzero);
            });

            var categories = ['ยอดหนี้']
            $scope.barOptions = ReportService.initBarChart("barContainer", "", categories, 'จำนวน');
            $scope.barOptions.series.push({
                name: 'หนี้คงเหลือ',
                data: debtSeries
            }, {
                name: 'ตัดจ่ายแล้ว',
                data: paidSeries
            }, {
                name: 'ลดหนี้ศูนย์',
                data: setzeroSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getDebtData = function(URL) {
        $scope.debts = [];
        $scope.apps = [];
        $scope.paids = [];
        $scope.setzeros = [];

        $scope.debtPager = [];
        $scope.appPager = [];
        $scope.paidPager = [];
        $scope.setzeroPager = [];

        $scope.debtPages = [];
        $scope.appPages = [];
        $scope.paidPages = [];
        $scope.setzeroPages = [];

        $scope.loading = true;
        
        let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
        let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
        let debtType = ($("#debtType").val() != '') ? $("#debtType").val() : 0;
        let showAll = ($('#showall').is(":checked")) ? 1 : 0;

        $http.get(`${CONFIG.baseUrl}${URL}/${debtType}/${sDate}/${eDate}/${showAll}`)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.debts.data;
            $scope.debtPager = res.data.debts;
            $scope.debtPages = PaginateService.createPagerNo($scope.debtPager);

            $scope.apps = res.data.apps.data;
            $scope.appPager = res.data.apps;
            $scope.appPages = PaginateService.createPagerNo($scope.appPager);

            $scope.paids = res.data.paids.data;
            $scope.paidPager = res.data.paids;
            $scope.paidPages = PaginateService.createPagerNo($scope.paidPager);

            $scope.setzeros = res.data.setzeros.data;
            $scope.setzeroPager = res.data.setzeros;
            $scope.setzeroPages = PaginateService.createPagerNo($scope.setzeroPager);

            $scope.totalDebt = res.data.totalDebt;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDebt = function(debtId) {
        $http.get(`${CONFIG.baseUrl}/debt/get-debt/${debtId}`)
        .then(function(res) {
            console.log(res);
            $scope.debt = res.data.debt;

            /** Convert db date to thai date. */
            $scope.debt.debt_date = StringFormatService.convFromDbDate($scope.debt.debt_date);
            $scope.debt.po_date = StringFormatService.convFromDbDate($scope.debt.po_date);
            $scope.debt.debt_doc_recdate = StringFormatService.convFromDbDate($scope.debt.debt_doc_recdate);
            $scope.debt.deliver_date = StringFormatService.convFromDbDate($scope.debt.deliver_date);
            $scope.debt.debt_doc_date = ($scope.debt.debt_doc_date) ? StringFormatService.convFromDbDate($scope.debt.debt_doc_date) : '';
            $scope.debt.doc_receive = StringFormatService.convFromDbDate($scope.debt.doc_receive);
        }, function(err) {
            console.log(err);
        });
    }

    $scope.calculateVat = function(amount, vatRate) {
        let vat = parseInt(vatRate) ? parseInt(vatRate) : 0;
        
        $scope.debt.debt_vat = ((parseFloat(amount) * vat) / 100).toFixed(2);
        $scope.debt.debt_total = (parseFloat(amount) + parseFloat($scope.debt.debt_vat)).toFixed(2);
    }

    $scope.add = function(event) {
        event.preventDefault();

        window.location.href = `${CONFIG.baseUrl}/debt/add`;
    }

    $scope.store = function(event, form) {
        event.preventDefault();

        if (form.$invalid) {
            console.log(form.$error);
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            $scope.loading = true;

            /** Convert thai date to db date. */
            // $scope.debt.debt_date = StringFormatService.convToDbDate($scope.debt.debt_date);
            // $scope.debt.debt_doc_recdate = StringFormatService.convToDbDate($scope.debt.debt_doc_recdate);
            // $scope.debt.deliver_date = StringFormatService.convToDbDate($scope.debt.deliver_date);
            // $scope.debt.debt_doc_date = ($scope.debt.debt_doc_date) ? StringFormatService.convToDbDate($scope.debt.debt_doc_date) : '';
            // $scope.debt.doc_receive = StringFormatService.convToDbDate($scope.debt.doc_receive);

            /** Get supplier data */
            // $scope.debt.supplier_name = ($("#supplier_id").find('option:selected').text()).trim();

            /** Get user id */
            $scope.debt.debt_creby = $("#user").val();
            $scope.debt.debt_userid = $("#user").val();

            $http.post(`${CONFIG.baseUrl}/debt/store`, $scope.debt)
            .then(function(res) {
                if (res.data.status == 1) {
                    toaster.pop('success', "ผลการทำงาน", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');

                    /** TODO: If was receiving debt from e-plan should update data in e-plan as well */
                    if ($scope.isTmpDebt) {
                        $http.put(`${CONFIG.eplanApiUrl}/withdrawals/${res.data.debt.withdraw_id}/set-debt`, { debt_id: res.data.debt.debt_id })
                        .then(function(res) {
                            if (res.data.status == 1) {
                                toaster.pop('success', "ผลการทำงาน", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');

                                $scope.isTmpDebt = false;

                                /** TODO: should clear all input control values instead of redirect route */

                                /** Redirect to debt list */
                                // $rootScope.redirectToIndex('debt/list');
                            } else {
                                toaster.pop('error', "ผลการทำงาน", 'พบข้อผิดพลาด !!!');
                            }
                        }, function(err) {
                            console.log(err);
                            toaster.pop('error', "ผลการทำงาน", 'พบข้อผิดพลาด !!!');
                        });
                    }

                    $scope.clearDebtObj();
                } else {
                    toaster.pop('error', "ผลการทำงาน", 'พบข้อผิดพลาด !!!');
                }

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                toaster.pop('error', "ผลการทำงาน", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }
    }

    $scope.edit = function(event, debtId) {
        event.preventDefault();

        window.location.href = `${CONFIG.baseUrl}/debt/edit/${debtId}`;
    };

    $scope.update = function(event, form, debtId) {
        console.log(debtId);
        event.preventDefault();

        if (form.$invalid) {
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {            
            /** Convert thai date to db date. */
            // $scope.debt.debt_date = StringFormatService.convToDbDate($scope.debt.debt_date);
            // $scope.debt.debt_doc_recdate = StringFormatService.convToDbDate($scope.debt.debt_doc_recdate);
            // $scope.debt.deliver_date = StringFormatService.convToDbDate($scope.debt.deliver_date);
            // $scope.debt.debt_doc_date = ($scope.debt.debt_doc_date) ? StringFormatService.convToDbDate($scope.debt.debt_doc_date) : '';
            // $scope.debt.doc_receive = StringFormatService.convToDbDate($scope.debt.doc_receive);

            /** Get supplier data */
            $scope.debt.supplier_id = $("#supplier_id").val();
            $scope.debt.supplier_name = $("#supplier_name").val();

            /** Get user id */
            $scope.debt.debt_creby = $("#user").val();
            $scope.debt.debt_userid = $("#user").val();

            if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + debtId + " ใช่หรือไม่?")) {
                $scope.loading = true;

                $http.put(`${CONFIG.baseUrl}/debt/update`, $scope.debt)
                .then(function(res) {
                    console.log(res);
                    toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');

                    $scope.loading = false;

                    /** Redirect to debt list */
                    $rootScope.redirectToIndex('debt/list');
                }, function(err) {
                    console.log(err);
                    toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                    $scope.loading = false;
                });
            }
            // else {
            //     $scope.debt.debt_date = StringFormatService.convFromDbDate($scope.debt.debt_date);
            //     $scope.debt.debt_doc_recdate = StringFormatService.convFromDbDate($scope.debt.debt_doc_recdate);
            //     $scope.debt.deliver_date = StringFormatService.convFromDbDate($scope.debt.deliver_date);
            //     $scope.debt.debt_doc_date = ($scope.debt.debt_doc_date) ? StringFormatService.convFromDbDate($scope.debt.debt_doc_date) : '';
            //     $scope.debt.doc_receive = StringFormatService.convFromDbDate($scope.debt.doc_receive);
            // }
        }
    };

    $scope.delete = function(debtId) {
        console.log(debtId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + debtId + " ใช่หรือไม่?")) {
            $scope.loading = true;

            $http.delete(`${CONFIG.baseUrl}/debt/delete/${debtId}`)
            .then(function(res) {
                console.log(res);

                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');

                $scope.loading = false;
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');

                $scope.loading = false;
            });
        }

        /** Get debt list and re-render chart */
        $scope.getData();
        // $scope.getDebtChart($scope.cboDebtType);
    };

    $scope.setzero = function(debtId) {
        console.log(debtId);

        if(confirm("คุณต้องลดหนี้เป็นศูนย์รายการหนี้เลขที่ " + debtId + " ใช่หรือไม่?")) {
            $scope.loading = false;

            $http.post(`${CONFIG.baseUrl}/debt/setzero`, { debt_id: debtId })
            .then(function(res) {
                if(res.data.status == 'success') {
                    toaster.pop('success', "ระบบทำการงลดหนี้เป็นศูนย์สำเร็จแล้ว", "");

                    $scope.loading = false;
                } else { 
                    toaster.pop('error', "พบข้อผิดพลาดในระหว่างการดำเนินการ !!!", "");

                    $scope.loading = false;
                }
            }, function(err) {
                console.log(err);

                toaster.pop('error', "พบข้อผิดพลาดในระหว่างการดำเนินการ !!!", "");

                $scope.loading = false;
            });
        }

        /** Get debt list and re-render chart */
        $scope.getData(); 
        // $scope.getDebtChart($scope.cboDebtType);
    };

    $scope.exportListToExcel = function() {
        if($scope.debts.length == 0) {
            toaster.pop('warning', "", "ไม่พบข้อมูล !!!");
        } else {
            let sDate = ($("#debtFromDate").val() != '') ? StringFormatService.convToDbDate($("#debtFromDate").val()) : 0;
            let eDate = ($("#debtToDate").val() != '') ? StringFormatService.convToDbDate($("#debtToDate").val()) : 0;
            let supplier = ($scope.cboSupplier != '') ? $scope.cboSupplier : 0;
            let showAll = $('#showall').is(":checked") ? 1 : 0;
            
            window.location.href = `${CONFIG.baseUrl}/debt/search/excel/${sDate}/${eDate}/${supplier}/${showAll}`;
        }
    }
});