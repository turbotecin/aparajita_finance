'use strict';

app.controller('homeCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', function($scope, loginService, $route, $rootScope, $location, $http){
	// console.log('@', $scope.cur_page);
	$scope.page_name = "home";
	$scope.setCurPage($scope.page_name);
	// console.log($scope.cur_page);

	if($scope.checkEmpty($rootScope.appData)){
        console.log("App Data is empty");
        $scope.loadAppData();
    }else{
        console.log("App data in not empty");
    }
	
	//fetch login user
	var userrequest = loginService.fetchuser();
	userrequest.then(function(response){
		$scope.user = response.data[0];
	});
}]);