app.config(function($routeProvider){
	$routeProvider
	.when('/', {templateUrl: 'views/login_new.html', controller: 'loginCtrl'})
	.when('/home', {templateUrl: 'views/home.html', controller: 'homeCtrl'})

	.when('/goldloan', {templateUrl: 'views/gold_loan/list.html', controller: 'goldLoanCtrl'})
	.when('/goldloan/:action', {templateUrl: 'views/gold_loan/add_edit.html', controller: 'goldLoanCtrl'})
	
	.when('/cashloan', {templateUrl: 'views/cash_loan/list.html', controller: 'cashLoanCtrl'})
	.when('/cashloan/:action', {templateUrl: 'views/cash_loan/add_edit.html', controller: 'cashLoanCtrl'})
	.when('/cashloan/:action/:loanId', {templateUrl: 'views/cash_loan/print.html', controller: 'cashLoanCtrl'})
	
	.when('/customer', {templateUrl: 'views/customer/list.html', controller: 'customerCtrl'})
	.when('/customer/:action', {templateUrl: 'views/customer/add_edit.html', controller: 'customerCtrl'})

	.when('/cashflow', {templateUrl: 'views/cashflow/list.html', controller: 'cashFlowCtrl'})
	
	.otherwise({redirectTo: '/'});
});