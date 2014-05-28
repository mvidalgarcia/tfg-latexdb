'use strict';

var problemsCollection = angular.module("problemsCollection", []);

problemsCollection.controller('ProblemListCtrl', function($scope) {
    $scope.problemas = [
      { 'resumen': 'Problema sobre AJAX',
        'tags': ['javascript', 'html', 'XHR'],
        'id': 100 },
      { 'resumen': 'Problema sobre AngularJS',
        'tags': ['javascript', 'html', 'XHR'],
        'id': 101 },
      { 'resumen': 'Problema sobre HTML5',
        'tags': ['html'],
        'id': 102 },
      { 'resumen': 'Problema sobre Proxies',
        'tags': ['http'],
        'id': 103 }
    ];
  });


