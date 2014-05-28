'use strict';

var problemsCollection = angular.module("problemsCollection", []);

problemsCollection.controller('ProblemListCtrl', function($scope, $http) {
    $http.get("preguntas-ejemplo.json").success(function(data){
        console.log(data);
    });
  });


