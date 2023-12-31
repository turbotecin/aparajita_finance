'use strict';

app.controller('goldLoanCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', '$window', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams, $window){
	
    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }

	$scope.setCurPage("Gold Loan");
	// console.log($scope.cur_page, $rootScope.appData);

    if($scope.checkEmpty($rootScope.appData)){
        // console.log("App Data is empty", $rootScope.appData);
        $scope.loadAppData();
        // console.log("App Data called", $rootScope.appData);
    }else{
        // console.log("App data in not empty");
    }
    // console.log($rootScope.appData);

    
    $scope.form = {};
    $scope.formError = {};
    $scope.formData = {};
    $scope.productRow = {};
    $scope.product_list = [];
    
    $scope.init_formData = function(){
        $scope.formData.loanCategoryId = 1; // 1 = Gold Loan
        $scope.formData.date = "";
        $scope.formData.customerId = "";
        $scope.formData.customerInfo = "";
        $scope.formData.loanAmount = 0;
        $scope.formData.loanProcessingCharge = 0;
        $scope.formData.loanAdditionalCharge = 0;
        $scope.formData.loanAuctionCharge = 0;
        $scope.formData.disbursementAmount = 0;
        $scope.formData.productList = [];
    }

    $scope.init_productRow = function(){
        $scope.productRow.productKey = "";
        $scope.productRow.productId = "";
        $scope.productRow.productName = "";
        $scope.productRow.productQty = "";
        $scope.productRow.productWeight = "";
        $scope.productRow.productValue = "";
        $scope.productRow.caretKey = "";
        $scope.productRow.caretId = "";
        $scope.productRow.caretName = "";
        $scope.productRow.caretPercentage = "";
    }

    $scope.getCustomerDetails = function() {
        $scope.searchingRef = $scope.formData.customerInfo;
        // console.log($scope.searchingRef);
        $http.get($rootScope.appLaravelApiUrl + '/customers/'+$scope.searchingRef)
        .then(function(data) {
            var data = data.data;
            // console.log(data);

            $scope.customerInfo = true;
            $scope.customerData = data.response;

            $scope.formData.customerId = !$scope.checkEmpty($scope.customerData) ? $scope.customerData.id : "";
            console.log($scope.customerInfo, $scope.customerData);
            
        }, function(response) {
            console.log("Errror Loading ", response);
        })
        .catch(function onError(response) {

            console.log("Network Errror ", response);
        })
        .finally(function() {});
    }

    $scope.calculateDisbursementAmount = function () {
        $scope.loanAmount = $scope.formData.loanAmount;
        $scope.formData.loanProcessingCharge = ($scope.loanAmount * 1)/100;
        $scope.formData.loanAdditionalCharge = ($scope.loanAmount * 0.5)/100;
        $scope.formData.loanAuctionCharge = ($scope.loanAmount * 1)/100;
        $scope.formData.disbursementAmount = $scope.loanAmount - $scope.formData.loanProcessingCharge;

        $scope.formData.disbursementAmount = $scope.loanAmount - $scope.formData.loanProcessingCharge;
    }

    $scope.get_product_details = function(){
        $scope.productDate = $rootScope.appData.products[$scope.productRow.productKey];
        console.log($scope.productDate);
        // return;
        $scope.productRow.productId = $scope.productDate.id;
        $scope.productRow.productName = $scope.productDate.name;
    }

    $scope.get_caret_details = function(){
        $scope.caretData = $rootScope.appData.carets[$scope.productRow.caretKey];
        console.log($scope.caretData);
        // return;
        $scope.productRow.caretId = $scope.caretData.id;
        $scope.productRow.caretName = $scope.caretData.name;
        $scope.productRow.caretPercentage = $scope.caretData.percentage;
    };

    $scope.addProductRow = function() {
        console.log($rootScope.appData.gold_rate);
        $scope.productRow.productValue = ($scope.productRow.productWeight * $rootScope.appData.gold_rate) * $scope.productRow.caretPercentage/100;
        $scope.formData.productList.push(angular.copy($scope.productRow));
        console.log($scope.formData.productList);
        $scope.init_productRow();
    }

    $scope.removeProductRow = function(index) {
        $scope.formData.productList.splice(index, 1);
    }
	
	$scope.get_data = function() 
    {
        // 1 = Gold Loan
        $http.get($rootScope.appLaravelApiUrl + '/loans/1')
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.goldLoanList = data.response;
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
        // 2 = Gold Loan
        $http.get($rootScope.appLaravelApiUrl + '/goldloanprint/'+loanId)
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.printData = data.response;
                $scope.loanDetails = $scope.printData.loanDetails;
                $scope.loanDisburseDate = moment($scope.loanDetails.disbursement_date).format("dddd, MMMM Do YYYY");
                $scope.installmentDetails = $scope.printData.installmentDetails;
                $scope.loanProductDetails = $scope.printData.loanProductDetails;

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
                $scope.change_route("goldloan");
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
        frameDoc.document.write('<link rel="stylesheet" href="assets/css/custom.css">');

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
        $scope.init_productRow();
    }else if($scope.action == "print"){
        $scope.get_printData($routeParams.loanId);
    }else{
        $scope.get_data();
    }

}]);