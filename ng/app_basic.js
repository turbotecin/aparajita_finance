'use strict';
app.basicControl = function($scope, $route, $rootScope, $location, $http, $routeParams) {

    $scope.basic_data = function() {
        if ($scope.checkEmpty($rootScope.config)) {
            $scope.change_route('/login');
        } else {
            $scope.checkLogin();
            // $scope.change_route('/dashboard');
        }
        
        $scope.loadAppData();
    }
    
    $scope.loadAppData = function(){
	    $http.get($rootScope.appLaravelApiUrl + '/appdata')
        .then(function(data) {
            var data = data.data;
            // console.log(data);
            if (data.status == 'success') {
                $rootScope.appData.gold_rate = data.response.gold_rate;
                $rootScope.appData.products = data.response.products;
                $rootScope.appData.carets = data.response.carets;
                $rootScope.appData.total_amount = data.response.total_amount;
                // console.log($scope.appData);
            }
        }, function(response) {
           
        })
        .catch(function onError(response) {
		})
        .finally(function() {});
    }

    $scope.checkLogin = function() {
        $http.post($rootScope.baseUrlApi + '/checklogin', {
                'inputData': $rootScope.config
            })
            .then(function(data) {
                var data = data.data;
                //console.log(data);
                if (data.status == 'success') {

                    $rootScope.config = data.config;
                    $rootScope.setStorageJson('config', data.config);
                    $rootScope.staff = data.staff;
                    if ($scope.cur_page == 'Login') {
                        $scope.change_route('/dashboard');
                    }

                } else if (data.status == 'valid_error') {
                    $scope.change_route('/login');
                } else if (data.status == 'error') {
                    $scope.change_route('/login');
                }
            }, function(response) {
                console.log("Errror Loading ", response);
                $scope.change_route('/login');
            })
            .catch(function onError(response) {

                console.log("Network Errror ", response);
                $scope.change_route('/login');
            })
            .finally(function() {});

    }

    $scope.logOutUser = function() {
        $rootScope.staff = {};
        var config = $scope.getStorageJson('config');
        config.token = "";
        $scope.setStorageJson('config', config);
        $rootScope.config = config;
        $scope.change_route('/login');
    }
	

}