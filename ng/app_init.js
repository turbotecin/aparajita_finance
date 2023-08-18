'use strict';
app.controller('AppController', ['$scope', '$route', '$rootScope', '$location','$http', 'loginService', '$routeParams', '$timeout', function($scope, $route, $rootScope, $location, $http, loginService, $routeParams, $timeout) 
{
	$scope.appName = "Aparajita Finance";
	$scope.appBaseUrl = "http://127.0.0.1/www/aparajita_finance/";
	$rootScope.appApiUrl = $scope.appBaseUrl + "api";
	$rootScope.appLaravelApiUrl = $scope.appBaseUrl + "services";
	$rootScope.appData = {};
	// console.log($rootScope.appData);

	$rootScope.config={};
	
	$rootScope.isSecure=false;
	if($location.protocol()=='https'){
		$rootScope.isSecure=true;
	}
	
	
	$scope.showMessageAutoHide = false;
	$scope.appLoading=true;
	$scope.debug=0;
	$scope.Math = window.Math;
	// $scope.appUrl = "http://admin.billionaireblueprint.biz/services";
	// $scope.appUrl = "http://aparajita.turbotec.in/services";
	// $rootScope.baseUrlApi = $scope.appUrl+"/api";
	// $rootScope.applicationUrl = "http://127.0.0.1/afs/";
	$scope.currency = "₹";
    
	

	//logout
	$scope.logout = function(){
        $scope.userProfileShowHide();
		loginService.logout();
	}

    $scope.cur_page='';	
	$scope.setCurPage = function(newVal) {
		$scope.cur_page=newVal;
	};

    $scope.isVisibleUserProfile = false;
    $scope.userProfileShowHide = function(){
		console.log("chk", $scope.isVisibleUserProfile);
		$scope.isVisibleUserProfile = $scope.isVisibleUserProfile ? false : true;  
	};

	app.helpers($scope, $route, $rootScope, $location, $http, $routeParams, $timeout);
	app.basicControl($scope, $route, $rootScope, $location, $http, $routeParams);
}]);














