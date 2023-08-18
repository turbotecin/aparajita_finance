app.controller('cashFlowCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', '$window', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams, $window){
	
    console.log($routeParams, moment());

    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }
    
    $scope.setCurPage("Cash Flow");
	// console.log($scope.cur_page, $scope.action, moment());

	if($scope.checkEmpty($rootScope.appData)){
        // console.log("App Data is empty");
        $scope.loadAppData();
    }else{
        // console.log("App data in not empty");
    }
    
    $scope.customerInfo = false;
    $scope.form = {};
    $scope.formError = {};
    $scope.formData = {};
    $scope.customerData = {};
    $scope.customerData.message = "";
    $scope.cashLoanList = {};
    
    $scope.init_formData = function(){
        $scope.formData.loanCategoryId = 2; // 2 = Cash Loan
        $scope.formData.date = "";
        $scope.formData.customerId = "";
        $scope.formData.customerInfo = "";
        $scope.formData.loanAmount = 0;
        $scope.formData.loanProcessingCharge = 0;
        $scope.formData.disbursementAmount = 0;
    }

    $scope.getCustomerDetails = function() {
        $scope.searchingRef = $scope.formData.customerInfo;
        // console.log($scope.searchingRef);
        $http.get($rootScope.appLaravelApiUrl + '/customers/'+$scope.searchingRef)
        .then(function(data) {
            var data = data.data;
            // console.log(data);

            // $scope.customerInfo = (data.status == 'success') ? true : false;
            $scope.customerInfo = true;
            $scope.customerData = data.response;
            $scope.formData.customerId = $scope.customerData.id;
            console.log($scope.customerInfo, $scope.customerData);

            /* if (data.status == 'success') 
            {
                $scope.customerInfo = true;
                $scope.customerData = data.response;
                console.log($scope.customerData);
            } 
            else if (data.status == 'error') 
            {
                $scope.customerInfo = false;
                $scope.customerData = data.response;
                console.log($scope.customerData);
            } */
        }, function(response) {
            console.log("Errror Loading ", response);
        })
        .catch(function onError(response) {

            console.log("Network Errror ", response);
        })
        .finally(function() {});
    }

    $scope.calculateDisbursementAmount = function () {
        $scope.formData.disbursementAmount = $scope.formData.loanAmount - $scope.formData.loanProcessingCharge;
    }
	
	$scope.get_data = function() 
    {
        // 2 = Cash Loan
        $scope.param = {};
        $scope.param.fromDate = '01-08-2023';
        $scope.param.toDate = '31-08-2023';

        $http.post($rootScope.appLaravelApiUrl + '/cashflow', {'param' : $scope.param})
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.cashFlowList = data.response;
                $scope.cashFlowExpenses = $scope.cashFlowList.expenses;
                $scope.cashFlowIncomes = $scope.cashFlowList.incomes;
            } 
            else if (data.status == 'error') 
            {

            }
        }, function(response) {
            console.log("Errror Loading ", response);
        })
        .catch(function onError(response) {

            console.log("Network Errror ", response);
        })
        .finally(function() {});
	};
	
	$scope.get_printData = function(loanId) 
    {
        // 2 = Cash Loan
        $http.get($rootScope.appLaravelApiUrl + '/loanprint/'+loanId)
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.printData = data.response;
                $scope.loanDetails = $scope.printData.loanDetails;
                $scope.loanDisburseDate = moment($scope.loanDetails.disbursement_date).format("dddd, MMMM Do YYYY");
                $scope.installmentDetails = $scope.printData.installmentDetails;

                // console.log($scope.loanDetails.disbursement_date, $scope.loanDisburseDate);
            } 
            else if (data.status == 'error') 
            {

            }
        }, function(response) {
            console.log("Errror Loading ", response);
        })
        .catch(function onError(response) {

            console.log("Network Errror ", response);
        })
        .finally(function() {});
	};

    $scope.submit_form = function() {
        $scope.formData.disbursementDate = moment($scope.formData.date).format('YYYY-MM-DD');
        console.log($scope.formData);
        // return;
        $http.post($rootScope.appLaravelApiUrl + '/loans', {'inputData' : $scope.formData})
        .then(function(data) 
        {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') {
                $scope.change_route("cashloan");
            } 
            else if (data.status == 'valid_error') {
                //console.log(data.errors);
                // $scope.showServerFormErrors(data.errors);
            } 
            else if (data.status == 'error') {

            }

            // if (!$rootScope.checkEmpty(data.msg)) $rootScope.showMessage(data.msg, data.status, true);

        }, function(response) 
        {
            console.log("Errror Loading ", response);
        })
        .catch(function onError(response) {

            console.log("Network Errror ", response);
        })
        .finally(function() {
        });
    };

    $scope.PrintDIV = function () {
        var contents = document.getElementById("dvContents").innerHTML;
        var body = document.getElementsByTagName("BODY")[0];

        //Create a dynamic IFRAME.
        var frame1 = document.createElement("IFRAME");
        frame1.name = "frame1";
        frame1.setAttribute("style", "position:absolute;top:-1000000px");
        body.appendChild(frame1);

        //Create a Frame Document.
        var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();

        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>DIV Contents</title>');
        frameDoc.document.write('</head><body>');

        //Append the external CSS file.
        frameDoc.document.write('<link rel="stylesheet" href="assets/css/main.css">');

        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();

        $window.setTimeout(function () {
            $window.frames["frame1"].focus();
            $window.frames["frame1"].print();
            body.removeChild(frame1);
        }, 500);
    };

    if($scope.action == "add"){
        $scope.init_formData();
    }else if($scope.action == "print"){
        $scope.get_printData($routeParams.loanId);
    }else{
        $scope.get_data();
    }
    
    
}]);