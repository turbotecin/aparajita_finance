'use strict';

app.controller('goldLoanCtrl', ['$scope', 'loginService', '$route', '$rootScope', '$location', '$http', '$routeParams', function($scope, loginService, $route, $rootScope, $location, $http, $routeParams){
	
    if($scope.checkEmpty($routeParams.action)){
        $scope.action = "";
    }else{
        $scope.action = $routeParams.action;
    }

	$scope.setCurPage("Gold Loan");
	console.log($scope.cur_page, $rootScope.appData);

    if($scope.checkEmpty($rootScope.appData)){
        console.log("App Data is empty", $rootScope.appData);
        $scope.loadAppData();
        // console.log("App Data called", $rootScope.appData);
    }else{
        console.log("App data in not empty");
    }
    // console.log($rootScope.appData);

    
    $scope.form = {};
    $scope.formError = {};
    $scope.formData = {};
    $scope.product_list = [];
    
    $scope.init_formData = function(){
        $scope.formData.productKey = "";
        $scope.formData.productId = "";
        $scope.formData.productName = "";
        $scope.formData.productQty = "";
        $scope.formData.productWeight = "";
        $scope.formData.caretKey = "";
        $scope.formData.caretId = "";
        $scope.formData.caretName = "";
        $scope.formData.caretPercentage = "";
        $scope.formData.goldValue = "";
    }

    $scope.get_product_details = function(){
        $scope.product_row = $rootScope.appData.products[$scope.formData.productKey];
        $scope.formData.productId = $scope.product_row.id;
        $scope.formData.productName = $scope.product_row.name;
    }

    $scope.get_caret_details = function(){
        $scope.caret_row = $rootScope.appData.carets[$scope.formData.caretKey];
        $scope.formData.caretId = $scope.caret_row.id;
        $scope.formData.caretName = $scope.caret_row.name;
        $scope.formData.caretPercentage = $scope.caret_row.percentage;
        // console.log($scope.formData.caretId, $scope.formData.caretPercentage);
    };

    $scope.addProductRow = function() {
        $scope.formData.goldValue = ($scope.formData.productWeight * $rootScope.appData.gold_rate) * $scope.formData.caretPercentage/100;
        $scope.product_list.push(angular.copy($scope.formData));
        $scope.init_formData();
        // console.log($scope.formData, $scope.product_list);
    }

    $scope.removeProductRow = function(index) {
        $scope.product_list.splice(index, 1);
    }
    $scope.init_formData();

}]);