app.controller('cashLoanCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams){
	
    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }
    
    $scope.setCurPage("Cash Loan");
	console.log($scope.cur_page, $scope.action, moment());

	if($scope.checkEmpty($rootScope.appData)){
        console.log("App Data is empty");
        $scope.loadAppData();
    }else{
        console.log("App data in not empty");
    }
    
    $scope.form = {};
    $scope.formError = {};
    $scope.formData = {};
    $scope.customerList = {};
    
    $scope.init_formData = function(){
        $scope.formData.customerId = 1;
        $scope.formData.date = "";
        $scope.formData.loanAmount = 0;
        $scope.formData.loanProcessingCharge = 0;
        $scope.formData.disbursementAmount = 0;
        $scope.formData.loanCategoryId = 2; // 2 = Cash Loan
    }

    $scope.calculateDisbursementAmount = function () {
        $scope.formData.disbursementAmount = $scope.formData.loanAmount - $scope.formData.loanProcessingCharge;
    }
	
	$scope.get_data = function() 
    {
        $http.get($rootScope.appLaravelApiUrl + '/loans')
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.cashLoanList = data.response;
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

            if (!$rootScope.checkEmpty(data.msg)) $rootScope.showMessage(data.msg, data.status, true);

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

    if($scope.action == "add"){
        $scope.init_formData();
    }else{
        $scope.get_data();
    }
    
    
}]);