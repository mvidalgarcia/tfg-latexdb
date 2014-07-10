'use strict';

var problemsCollection = angular.module('problemsCollection', [
        'ngRoute', 
        'problemsControllers',
        'ui.sortable',
		'ui.bootstrap',
        'ngTagsInput'
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
       when('/edit/:id_problema/:copy', {
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
           }).
       when('/view-doc/:id_doc', {
             templateUrl: 'partials/doc-view.html',
             controller:  'DocDetailsCtrl'
           }).
	   when('/edit-doc/:id_doc', {
             templateUrl: 'partials/doc-details.html',
             controller:  'DocDetailsCtrl'
           }).
	   when('/new-doc', {
             templateUrl: 'partials/doc-details.html',
             controller:  'DocDetailsCtrl'
           }).
       when('/delete-doc/:id_doc', {
               templateUrl: 'partials/doc-delete.html',
           }).
	   otherwise({
             redirectTo: '/list-doc'
          });
    }]);
