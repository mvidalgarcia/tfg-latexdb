'use strict';

var problemsCollection = angular.module('problemsCollection', [
        'ngRoute', 
        'problemsControllers',
        'ui.sortable'
        ]);

problemsCollection.config(['$routeProvider',
    function($routeProvider) {
       $routeProvider.
           // Problemas
		   when('/list', {
             templateUrl: 'partials/problem-list.html',
             controller:  'ProblemListCtrl'
           }).
           when('/view/:id_problema', {
             templateUrl: 'partials/problem-view.html',
             controller:  'ProblemDetailsCtrl'
           }).
           when('/edit/:id_problema', {
             templateUrl: 'partials/problem-details.html',
             controller:  'ProblemDetailsCtrl'
           }).
           when('/new', {
             templateUrl: 'partials/problem-details.html',
             controller:  'ProblemDetailsCtrl'
           }).
           when('/delete/:id_problema', {
               templateUrl: 'partials/problem-delete.html',
           }).
		  // Documentos finales
		   when('/list-doc', {
             templateUrl: 'partials/doc-list.html',
             controller:  'DocListCtrl'
           }).           otherwise({
             redirectTo: '/list'
          });
    }]);
