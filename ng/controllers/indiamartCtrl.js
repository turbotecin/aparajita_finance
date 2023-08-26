app.controller('indiamartCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', '$window', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams, $window){
	
    console.log($routeParams, moment());

    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }
    
    $scope.setCurPage("India Mart");
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
    
	
	$scope.get_data = function() 
    {
        var key = "mR27Ebtl7H3EQfep7nKC7liIplrHmTFh";
        var from = "24-Aug-2023";
        var to = "25-Aug-2023";
        var url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2/?glusr_crm_key="+key+"&start_time="+from+"&end_time="+to;

        $http.get(url)
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                
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

    $scope.get_data();
    
    
}]);