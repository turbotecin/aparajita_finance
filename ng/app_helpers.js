'use strict';
app.helpers = function ($scope, $route, $rootScope, $location, $http, $routeParams) {
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
}