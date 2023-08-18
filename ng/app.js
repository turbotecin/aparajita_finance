var app = angular.module('app', ['ngRoute', 'moment-picker']); //, 'ngPrint'

app.run(function($rootScope, $location, loginService){
	//prevent going to homepage if not loggedin
	var routePermit = ['/home'];
	$rootScope.$on('$routeChangeStart', function(){
		if(routePermit.indexOf($location.path()) !=-1){
			var connected = loginService.islogged();
			connected.then(function(response){
				if(!response.data){
					$location.path('/');
				}
			});
		}
	});
	//prevent going back to login page if sessino is set
	var sessionStarted = ['/'];
	$rootScope.$on('$routeChangeStart', function(){
		if(sessionStarted.indexOf($location.path()) !=-1){
			var cantgoback = loginService.islogged();
			cantgoback.then(function(response){
				if(response.data){
					$location.path('/home');
				}
			});
		}
	});
});

app.factory('loginService', function($http, $rootScope, $location, sessionService){
	return{
		login: function(user, $scope){
			var validate = $http.post($rootScope.appApiUrl + '/login.php', user);
			validate.then(function(response){
				var uid = response.data.user;
				if(uid){
					sessionService.set('user',uid);
					// console.log(response.data, uid);
					if(uid){
						$scope.loadAppData();
					}
					$location.path('/home');
				}
				
				else{
					$scope.successLogin = false;
					$scope.errorLogin = true;
					$scope.errorMsg = response.data.message;
				}
			});
		},
		logout: function(){
			sessionService.destroy('user');
			$location.path('/');
		},
		islogged: function(){
			var checkSession = $http.post($rootScope.appApiUrl + '/session.php');
			return checkSession;
		},
		fetchuser: function(){
			var user = $http.get($rootScope.appApiUrl + '/fetch.php');
			return user;
		}
	}
});

app.factory('sessionService', ['$http', '$rootScope', function($http, $rootScope){
	return{
		set: function(key, value){
			return sessionStorage.setItem(key, value);
		},
		get: function(key){
			return sessionStorage.getItem(key);
		},
		destroy: function(key){
			$http.post($rootScope.appApiUrl + '/logout.php');
			return sessionStorage.removeItem(key);
		}
	};
}]);
