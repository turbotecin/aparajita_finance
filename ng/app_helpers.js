'use strict';
app.helpers = function ($scope, $route, $rootScope, $location, $http, $routeParams, $timeout) {
	// helper functions start

	$scope.lastPath = '';
	$scope.change_route = function (path) {
		if ($scope.lastPath == path) {
			$route.reload();
		} else {
			$scope.lastPath = path;
			$location.path(path);
		}

	};
	
	$rootScope.checkEmpty = function (mixedVar) {
		return $scope.checkEmpty(mixedVar);
	};

	$scope.checkEmpty = function (mixedVar) {
		if (typeof mixedVar === 'object') {
			for (key in mixedVar) {
				if (mixedVar.hasOwnProperty(key)) {
					return false;
				}
			}
			return true;
		} else {
			//if(typeof mixedVar === 'string' || typeof mixedVar === 'number'){
			var undef;
			var key;
			var i;
			var len;
			var emptyValues = [undef, null, 'null', false, 0, '', '0', '0.00', '0.0', 'empty', undefined, 'undefined'];
			mixedVar = $.trim(mixedVar);
			for (i = 0, len = emptyValues.length; i < len; i++) {
				if (mixedVar === emptyValues[i]) {
					return true;
				}
			}
		}
		return false;
	};


	$rootScope.showMessage = function (message, type, action) {
		// $scope.showMessage = false;
		$scope.showMessageText = "";
		$scope.showMessageAutoHide = action;
		$scope.showMessageClass = "";
		

		if (type == 'error') {
			$scope.showMessageClass = "alert-danger"
		}
		if (type == 'success') {
			$scope.showMessageClass = "alert-success"
		}

		$scope.showMessageText = message;
		// $scope.showMessage = true;


		$timeout(function () {
			$scope.showMessageText = '';
			// $scope.showMessage = false;
			$scope.showMessageAutoHide = false;
		}, 20000);
	};
}