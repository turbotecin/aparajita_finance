'use strict';

app.controller('loginCtrl', function($scope, loginService){
	$scope.errorLogin = false;
	// console.log('@ login', $scope.cur_page);
	$scope.page_name = "login";
	$scope.setCurPage($scope.page_name);
	// console.log($scope.cur_page);
	
	$scope.login = function(user){
		loginService.login(user, $scope);
	}

	$scope.clearMsg = function(){
		$scope.errorLogin = false;
	}
});