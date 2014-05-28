'use strict';

var problemsCollection = angular.module("problemsCollection", []);

problemsCollection.controller('ProblemListCtrl', function($scope, $http) {
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas = data;
    });
  });


