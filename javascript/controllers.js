'use strict';

var problemsControllers = angular.module("problemsControllers", []);


/***** Controladores para problemas *****/

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

// Este controlador maneja la vista /view /edit y la vista /new
// Proporciona un formulario para editar el problema, añadirle preguntas,
// reordenarlas, etc.. y registra funciones para manejar eventos en los
// botones. Una de las funciones más importantes será sendProblem() que
// recibe el problema ya editado y deberá enviarlo por POST si es nuevo 
// o por PUT si ya existía, al correspondiente servidor PHP.
// De momento esta función se limita a volcar en consola lo que recibe
// de la vista, para depuración.
problemsControllers.controller('ProblemDetailsCtrl', function($scope, $http, $routeParams, $location) {
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
			
			// Pasar las puntuaciones a integer
			for (var i = 0; i < $scope.problema.preguntas.length; i++)
				$scope.problema.preguntas[i].puntuacion = parseInt($scope.problema.preguntas[i].puntuacion);
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
        	$location = $location.path("/list/");
    	})
		.error(function(data){
			console.log("Error al guardar problema.");
		});

    };

    $scope.editThisProblem = function () {
        $location = $location.path("/edit/" + $scope.problema.id_problema);
    };
});


/***** Controladores para documentos finales *****/

// Controlador para la vista de lista de documentos finales
// Carga la lista inicial (por GET al servidor) y define funciones
// de respuesta a los diferentes botones
problemsControllers.controller('DocListCtrl', function($scope, $http, $location) {
    // Rellenar la lista
    $http.get("get_docs_list.php").success(function(data){
        $scope.docs = data;
    });

    // Cuando el usuario pincha en un estado
    $scope.filterState = function (state) {
        $scope.query=state;  // Lo usamos como valor de la query
    };
    // En la X a la derecha del query, borramos la query
    $scope.clearQuery = function () {$scope.query=""; };

    // Al pinchar en el resumen de un doc, enviar a la vista "view-doc"
    // donde se muestra el documento (solo para lectura)
    $scope.viewDoc = function (id) {
        $location = $location.path("/view-doc/" + id);
    }
    // Si se pulsa el botón Borrar, se manda un método DELETE
    // al servidor PHP, el id va en la URL
    $scope.deleteDoc = function (id, estado) {
		// Si el documento está abierto, se puede borrar.
		if (estado == "abierto") {
        	$http.delete("delete_doc.php?id_doc=" + id).error(function(data, status) {
            	console.log(status, data);
	        }).success(function(data, status) {
    	        console.log(status, data);
				$location.path("/delete-doc/" + id);
	        });
		}
	}
	
    // Si se pulsa el botón Editar, se va a la vista "edit"
    // donde se pueden cambiar los datos del documento. Su correspondiente
    // controlador registrará acciones para cuando se dé a Guardar,
    $scope.editDoc = function (id, estado) {
		// Si el documento está abierto, se puede borrar.
		if (estado == "abierto") {
        	$location = $location.path("/edit-doc/" + id);
		}
    }
    // Si se pulsa el botón "Nuevo Documento" se va a la vista "new-doc"
    // que en realidad carga el mismo parcial html, pero con un
    // id_doc especial para indicar que es nuevo, y todos los
    // datos del documento vacíos
    $scope.createNewDoc = function () {
        $location = $location.path("/new-doc/");
    }
	
	// Función que retorna un mensaje mostrado en un "bocadillo"
	// cuando se pasa el ratón por encima del botón Borrar 
	// en el caso de que el documento no esté cerrado/publicado.
	$scope.showPopBorrar = function (estado) {
    	if (estado != "abierto")
			return "Solo se pueden borrar abiertos!";
		else
			return "";
	}

	// Función que retorna un mensaje mostrado en un "bocadillo"
	// cuando se pasa el ratón por encima del botón Editar 
	// en el caso de que el documento no esté cerrado/publicado.
	$scope.showPopEditar = function (estado) {
    	if (estado != "abierto")
			return "Solo se pueden editar abiertos!";
		else
			return "";
	}

});

// Este controlador maneja la vista /view-doc, /edit-doc y la vista /new-doc
problemsControllers.controller('DocDetailsCtrl', function($scope, $http, $routeParams, $location) {
    
	// En cualquier caso, hay que cargar los resúmenes y tags de todos los
	// problemas almacenados en la aplicación para mostrarlos en la lista dragable.
	// Rellenar la lista
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas_bd = data;
    });

	// Si recibimos un id_doc, es la vista /edit/:id_doc o la vista /view/:id_doc
    if ($routeParams.id_doc) {
        // Entonces usamos el id para pedir datos del documento al servidor
        $scope.id_doc = $routeParams.id_doc;
        $http.get("get_doc.php?id_doc=" + $scope.id_doc).success(function(data){
            $scope.doc = data;
			$scope.doc_aux = angular.copy($scope.doc);
			// Ordenar en función de la posición ya que según lo saca de base de datos
			// lo saca en orden "aleatorio" y hay que mostrarselo al usuario en el orden 
			// que lo guardó. Nos apoyamos en una variable auxiliar.
			for (var i = 0; i < $scope.doc.problemas.length; i++)
				$scope.doc_aux.problemas[$scope.doc.problemas[i].posicion-1] = $scope.doc.problemas[i];
			
			$scope.doc.problemas = $scope.doc_aux.problemas;
			console.log($scope.doc_aux);
        });
    } else {
        // Si no recibimos un id_doc, es la vista /new-doc
        // En este caso creamos un documento nuevo, con un id especial para
        // poder detectarlo más tarde cuando haya que enviarlo al servidor.
        $scope.id_doc = "Nuevo";
        $scope.doc = {
            "id_doc":$scope.id_doc, "titulacion": "", "asignatura":"", "convocatoria":"", "instrucciones":"", "fecha":"", "estado":"abierto",
            "problemas": []
        };
    }
    // Funciones de respuesta a clicks
    
    $scope.vars = {'query': "" }; 
    // Si se pulsa el botón "Guardar".
    $scope.sendDoc = function (doc) {

		// Habría que preparar una petición POST o PUT al servidor con un JSON apropiado
		// Entre otras cosas habría que recorrer el array de problemas para asignar a cada una
		// una posición que sea igual a su índice dentro de ese array (más uno)
		//
        // De momento me limito a volcar por consola lo que he recibido
		for (var i = 0; i < doc.problemas.length; i++)
			doc.problemas[i].posicion = i + 1;
			
		console.log(doc);
		
		$http.post("save_doc.php", doc).success(function(data){
        	// Volcar a consola la respuesta del servidor
        	console.log(data);
        	$location = $location.path("/list-doc/");
    	})
		.error(function(data){
			console.log("Error al guardar documento.");
		});
	} 
	

    $scope.editThisDoc = function () {
		// Solo se pueden editar documentos en estado 'abierto'.
		if ($scope.doc.estado == "abierto")
	        $location = $location.path("/edit-doc/" + $scope.doc.id_doc);
    };
	
	// Función que retorna un mensaje mostrado en un "bocadillo"
	// cuando se pasa el ratón por encima del botón Editar 
	// en el caso de que el documento no esté cerrado/publicado.
	$scope.showPopEditar = function (estado) {
    	if (estado != "abierto")
			return "Solo se pueden editar abiertos!";
		else
			return "";
	}

	/*** Funciones para las listas de problemas dragables. ***/

	// Define las listas que están conectadas entre sí, y que
	// por tanto es posible arrastrar elementos entre ellas.
    $scope.sortableOptions  = {
        connectWith: '.connector'
    }

    // DEBUG, llama a una función callback cada vez que cambia el valor de "model"
	// El valor 'true' de $watch sirve para que también se actualice
    // en el caso de que el valor cambie, no solo la referencia.
	// De esta forma al cambiar el orden de los problemas de un mismo panel también 
	// se llama a la función callback.
    /*$scope.$watch("problemas_bd", function(value) {
		if (!angular.isUndefined($scope.problemas_bd))
        console.log("Problemas_BD: " + value.map(function(e){return e.id_problema}).join(','));
    },true);

    // DEBUG, llama a una función callback cada vez que cambia el valor de "source"
    $scope.$watch("doc.problemas", function(value) {
		if (!angular.isUndefined($scope.doc.problemas))
        	console.log("Doc.Problemas: " + value.map(function(e){return e.id_problema}).join(','));
    },true);*/

	// Al cargar la página por primera vez las variables problemas_bd/doc.problemas 
	// aun no está cargada y se genera un error en consola. Se controla comprobando
	// si la variable está definida.
    $scope.sourceEmpty = function() {
		if (angular.isUndefined($scope.problemas_bd))
			return false;
		else
        	return $scope.problemas_bd.length == 0;
    }

    $scope.targetEmpty = function() {
		if (angular.isUndefined($scope.doc))
			return false;
		else
        	return $scope.doc.problemas.length == 0;
    }
	
	// En la X a la derecha del query, borramos la query
    $scope.clearQuery = function() { $scope.vars.query=""; };
	
	// Cuando el usuario pincha en un tag
    $scope.filterTag = function (tag) {
        $scope.vars.query=tag;  // Lo usamos como valor de la query
    };

});


// Función (controller) que controla qué sección del menú de navegación está activa.
function HeaderController($scope, $location) 
{ 
    $scope.isActive = function (viewLocation) { 
        return viewLocation === $location.path();
    };
}

