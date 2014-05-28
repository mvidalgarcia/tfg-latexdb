'use strict';

var problemsControllers = angular.module("problemsControllers", []);

problemsControllers.controller('ProblemListCtrl', function($scope, $http) {
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas = data;
    });
  });

problemsControllers.controller('ProblemDetailsCtrl', function($scope, $http, $routeParams) {
    $scope.id_problema = $routeParams.id_problema;
    $http.get("get_problem.php?id_problema=" + $scope.id_problema).success(function(data){
        $scope.problema = data;
        console.log(data);
    });
});

problemsControllers.controller('ProblemDeleteCtrl', function($scope, $http, $routeParams) {
    $scope.id_problema = $routeParams.id_problema;
    $http.post("delete_problem.php", {"id_problema": $scope.id_problema} ).success(function(data){
        console.log("Exito");
        console.log(data);
    });
});
