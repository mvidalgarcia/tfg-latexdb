'use strict';

var problemsCollection = angular.module('problemsCollection', [
        'ngRoute', 
        'problemsControllers'
        ]);

problemsCollection.config(['$routeProvider',
    function($routeProvider) {
       $routeProvider.
           when('/list', {
             templateUrl: 'partials/problem-list.html',
             controller:  'ProblemListCtrl'
           }).
           when('/edit/:id_problema', {
             templateUrl: 'partials/problem-details.html',
             controller:  'ProblemDetailsCtrl'
           }).
           when('/delete/:id_problema', {
               templateUrl: 'partials/problem-delete.html',
               controller:  'ProblemDeleteCtrl'
           }).
           otherwise({
             redirectTo: '/list'
          });
    }]);
