'use strict';

var problemsControllers = angular.module("problemsControllers", []);

// Controlador para la vista de lista de problemas
// Carga la lista inicial (por GET al servidor) y define funciones
// de respuesta a los diferentes botones
problemsControllers.controller('ProblemListCtrl', function($scope, $http, $location) {
    // Rellenar la lista
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas = data;
    });

    // Cuando el usuario pincha en un tag
    $scope.filterTag = function (tag) {
        $scope.query=tag;  // Lo usamos como valor de la query
    };
    // En la X a la derecha del query, borramos la query
    $scope.clearQuery = function () {$scope.query=""; };

    // Al pinchar en el resumen de un problema, enviar a la vista "view"
    // donde se muestra el problema (solo para lectura)
    $scope.viewProblem = function (id) {
        $location = $location.path("/view/" + id);
    }
    // Si se pulsa el botón Borrar, se manda un método DELETE
    // al servidor PHP, el id va en la URL
    $scope.deleteProblem = function (id) {
        $http.delete("delete_problem.php?id_problema=" + id).error(function(data, status) {
            console.log(status, data);
        }).success(function(data, status) {
            console.log(status, data);
			$location.path("/delete/" + id);
        });
    }
    // Si se pulsa el botón Editar, se va a la vista "edit"
    // donde se pueden cambiar los datos del problema. Su correspondiente
    // controlador registrará acciones para cuando se dé a Guardar,
    $scope.editProblem = function (id) {
        $location = $location.path("/edit/" + id);
    }
    // Si se pulsa el botón "Problema nuevo" se va a la vista "new"
    // que en realidad carga el mismo parcial html, pero con un
    // id_problema especial para indicar que es nuevo, y todos los
    // datos del problema vacíos
    $scope.createNewProblem = function () {
        $location = $location.path("/new/");
    }
  });

// Este controlador maneja la vista /edit y la vista /new
// Proporciona un formulario para editar el problema, añadirle preguntas,
// reordenarlas, etc.. y registra funciones para manejar eventos en los
// botones. Una de las funciones más importantes será sendProblem() que
// recibe el problema ya editado y deberá enviarlo por POST si es nuevo 
// o por PUT si ya existía, al correspondiente servidor PHP.
// De momento esta función se limita a volcar en consola lo que recibe
// de la vista, para depuración.
problemsControllers.controller('ProblemDetailsCtrl', function($scope, $http, $routeParams) {
    // Si recibimos un id_problema, es la vista /edit/:id_problema o la vista /view/:id_problema
    if ($routeParams.id_problema) {
        // Entonces usamos el id para pedir datos del problema al servidor
        $scope.id_problema = $routeParams.id_problema;
        $http.get("get_problem.php?id_problema=" + $scope.id_problema).success(function(data){
            $scope.problema = data;
            // Los datos tal como vienen del servidor no son directamente usables, ya que
            // en el campo tags viene mucha información irrelevante
            // El siguiente código extrae de la lista tags solo los nombres de los
            // tags y los deja en un string, separados por comas
            var tags = [];
            angular.forEach(data.tags, function(v, k) {
                this.push(v["nombre"]);
            }, tags);
            $scope.problema.tags = tags.join(", ");
        });
    } else {
        // Si no recibimos un id_problema, es la vista /new
        // En este caso creamos un problema nuevo, con un id especial para
        // poder detectarlo más tarde cuando haya que enviarlo al servidor
        $scope.id_problema = "Nuevo";
        $scope.problema = {
            "id_problema":$scope.id_problema, "enunciado_general": "", "resumen":"", "posicion":"",
            "preguntas": [],
            "tags": "",
			"imagenes": []
        };
    }
    // Funciones de respuesta a clicks
    
    // Si se pulsa en "Pregunta nueva"
    $scope.addQuestion = function () {
        // Añadir al array de preguntas una más, con los campos vacíos, y la puntuación a 1 por defecto
        $scope.problema.preguntas.push({"id_pregunta":"", "enunciado":"","solucion":"","explicacion":"","puntuacion":1, "posicion":""});
    };

    // Si se pulsa la papelera para borrar pregunta
    $scope.deleteQuestion = function (index) {
        // Eliminar esa pregunta del array de preguntas (splice es para eso)
        $scope.problema.preguntas.splice(index, 1);
    }

    // Si se pulsa el botón "Guardar"
    $scope.sendProblem = function (p) {
        // Habría que preparar una petición POST o PUT al servidor con un JSON apropiado
        // Entre otras cosas habría que recorrer el array de preguntas para asignar a cada una
        // una posición que sea igual a su índice dentro de ese array (más uno)
        //
        // De momento me limito a volcar por consola lo que he recibido
		for (var i = 0; i < p.preguntas.length; i++)
			p.preguntas[i].posicion = i + 1;

        console.log(p);
		
		$http.post("save_problem.php", p).success(function(data){
        	// Volcar a consola la respuesta del servidor
        	console.log(data);
    	})
		.error(function(data){
			console.log("Error al guardar problema.");
		});

    }
});

// Controlador para la vista /delete/:id_problema
// Debe enviar una petición http DELETE al servidor
problemsControllers.controller('ProblemDeleteCtrl', function($scope, $http, $routeParams) {
    $scope.id_problema = $routeParams.id_problema;
    $http.post("delete_problem.php", {"id_problema": $scope.id_problema} ).success(function(data){
        // Me limito a volcar a consola la respuesta del servidor
        console.log(data);
    });
    // TODO: manejar una posible respuesta de error del servidor
});
