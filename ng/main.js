$(document).ready(function () {
	//console.log('ready js');
	onLoadData();
});

function onLoadData() {
	$ngscope = angular.element(document.getElementById("AppController")).scope();
	$ngrootScope = angular.element(document.getElementById("AppController")).scope().$root;
}