app.controller('customerCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams){
	
    // $rootScope.showMessage("testing the message", "error", true);

    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }
    
    $scope.setCurPage("customer");
	console.log($scope.cur_page, $scope.action);

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
        $scope.formData.customerName = "";
        $scope.formData.customerAddress = "";
        $scope.formData.customerPhoneNo = "";
    }
	
	$scope.get_data = function() 
    {
        $http.get($rootScope.appLaravelApiUrl + '/customers')
        .then(function(data) {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.customerList = data.response;
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
        console.log($scope.formData);
        $http.post($rootScope.appLaravelApiUrl + '/customers', {'inputData' : $scope.formData})
        .then(function(data) 
        {
            var data = data.data;
            console.log(data);
            if (data.status == 'success') 
            {
                $scope.change_route("customer");
            } 
            else if (data.status == 'valid_error') 
            {
                //console.log(data.errors);
                // $scope.showServerFormErrors(data.errors);
            } 
            else if (data.status == 'error') {}

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