'use strict';

var problemsControllers = angular.module("problemsControllers", []);

problemsControllers.controller('ProblemListCtrl', function($scope, $http) {
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas = data;
    });
  });

problemsControllers.controller('ProblemDetailsCtrl', function($scope, $routeParams) {
    $scope.id_problema = $routeParams.id_problema;
});
