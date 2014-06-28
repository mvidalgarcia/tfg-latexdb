'use strict';

var problemsControllers = angular.module("problemsControllers", ['ui.bootstrap', 'ngReallyClickModule']);


/***** Controladores para problemas *****/

// Controlador para la vista de lista de problemas
// Carga la lista inicial (por GET al servidor) y define funciones
// de respuesta a los diferentes botones
problemsControllers.controller('ProblemListCtrl', function($scope, $http, $location, $filter) {
    // Rellenar la lista
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas = data;
    });

    $scope.vars = {'query': "" }; 

    // Retorna la lista de los problemas elegidos por lo que hay en el query
    // El query se interpreta como una serie de términos separados por espacios
    // Si un término comienza por # o + se busca sólo en la lista de tags
    // Esta función debe ser llamada cada vez que cambie la query
    $scope.filtrar = function () {
        var queries = $scope.vars.query.split(" ");
        $scope.vars.elegidos = $scope.problemas;
        for (var i=0; i<queries.length; i++) {
            if (queries[i]==" ") continue;
            if (queries[i].indexOf("#")==0 || queries[i].indexOf("+")==0) 
                $scope.vars.elegidos = $filter("filter")($scope.vars.elegidos, {'tags': queries[i].slice(1)});
            else
                $scope.vars.elegidos = $filter("filter")($scope.vars.elegidos, queries[i]);
        }
    }

    // Esta función retorna un booleano que indica si el problema dado está o no 
    // en la lista de elegidos, y se usa para controlar el ng-show de cada problema
    $scope.elegido = function (problema) {
        if ($scope.vars.elegidos) // Si la lista existe
            return ($scope.vars.elegidos.indexOf(problema)!=-1);
        else 
            return true;
    }

    // Cuando el usuario pincha en un tag
    $scope.filterTag = function (tag) {
        $scope.vars.query += " #" + tag;  // Lo usamos como valor de la query
        $scope.filtrar();
    };
    // En la X a la derecha del query, borramos la query
    $scope.clearQuery = function () {
        $scope.vars.query=""; 
        $scope.filtrar();
    };

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

	// Chequea previamente si es un problema que no está en ningún documento
	// para proseguir con la edición.
	$scope.mightEditProblem = function(problema) {
        console.log(problema);
		if (problema.id_docs_cerrados_publicados.length == 0 && problema.id_docs_abiertos.length == 0)
			$scope.editProblem(problema.id_problema);
	}


	// Función que devuelve el mensaje que se debe mostrar en un diálogo
	// cuando el usuario trata de editar un problema que pertenece
	// a un documento 'abierto' o 'cerrado/publicado'.
	$scope.getDialogMsgEdit = function(problema) {
		if (angular.isUndefined(problema))
			return "";
		
		var msg;
		// Si está en algún documento cerrado o publicado no se permite editarlo
		// y se informa al usuario en qué documentos aparece el problema. Se le 
		// permitirá al usuario realizar una copia del problema.
		if (problema.id_docs_cerrados_publicados.length > 0) {
				msg = "Este problema no puede ser editado debido a que pertenece a los documentos con estado 'cerrado' o 'publicado' siguientes: ";
			for (var i=0; i < problema.id_docs_cerrados_publicados.length; i++) {
				msg += problema.id_docs_cerrados_publicados[i]["id_doc"];
				if (i != problema.id_docs_cerrados_publicados.length - 1)
					msg += ", ";
			}
			msg += ".";
			msg += "<br><br>Sin embargo, es posible hacer una copia del problema y realizar las modificaciones oportunas sobre ella."
		}
		// Si solo está en documentos abiertos, se le informará en cuales
		// y se le permitirá la edición del problema.
		else if (problema.id_docs_abiertos.length > 0) {
			msg = "Este problema pertenece a los documentos con estado 'abierto' siguientes: ";
			for (var i=0; i < problema.id_docs_abiertos.length; i++) {
				msg += problema.id_docs_abiertos[i]["id_doc"];
				if (i != problema.id_docs_abiertos.length - 1)
					msg += ", ";
			}
			msg += ".";
			msg += "<br><strong>¿Está seguro que desea modificar su contenido?</strong>"+
				   "<br><small>Las modificaciones realizadas se verán reflejadas en todos los documentos en los que aparezca este problema.</small>"

		}
		return msg;
	}
	
	
	// Función que devuelve el mensaje que se debe mostrar en un diálogo
	// cuando el usuario trata de eliminar un problema que pertenece
	// a un documento 'abierto' o 'cerrado/publicado'.
	$scope.getDialogMsgDelete = function(problema) {
		if (angular.isUndefined(problema))
			return "";
		
		var msg;
		// Si no está en ningún documento, se le pedirá confirmación al usuario
		// antes de proceder a eliminarlo.
		if (problema.id_docs_cerrados_publicados.length == 0 && problema.id_docs_abiertos.length == 0)
			msg = "<strong>¿Está seguro de que quiere eliminar este problema?</strong><br>Una vez eliminado no podrá volver a recuperarlo.";
		// Si está en algún documento cerrado o publicado no se permite eliminarlo
		// y se informa al usuario en qué documentos aparece el problema.
		else if (problema.id_docs_cerrados_publicados.length > 0) {
				msg = "Este problema no puede ser eliminado debido a que pertenece a los documentos con estado 'cerrado' o 'publicado' siguientes: ";
			for (var i=0; i < problema.id_docs_cerrados_publicados.length; i++) {
				msg += problema.id_docs_cerrados_publicados[i]["id_doc"];
				if (i != problema.id_docs_cerrados_publicados.length - 1)
					msg += ", ";
			}
			msg += ".";
		}
		// Si solo está en documentos abiertos, tampoco se permite eliminarlo
		// pero se le dan unas breves indicaciones al usuario sobre como podría
		// eliminarlo.
		else if (problema.id_docs_abiertos.length > 0) {
			msg = "Este problema no puede ser eliminado debido a que pertenece a los documentos con estado 'abierto' siguientes: ";
			for (var i=0; i < problema.id_docs_abiertos.length; i++) {
				msg += problema.id_docs_abiertos[i]["id_doc"];
				if (i != problema.id_docs_abiertos.length - 1)
					msg += ", ";
			}
			msg += ".";
			msg += "<br><small>Para eliminar este problema debe eliminarlo previamente de los documentos indicados.</small>";
		}
		return msg;
	}


	// Función que realiza la copia de un problema cuando acepta el dialogo
	// el usuario en el caso de que el problema perteneza a documentos abiertos.
	$scope.copyProblem = function(id) {
		$location = $location.path("/edit/" + id + "/copy" );
	}

	// Establece que dialogo se debe usar en función de los parámetros de cada problema
	// al darle al botón editar.
	$scope.editDialogButtons = function(problema) {
		if (angular.isUndefined(problema))
			return;
		else if (problema.id_docs_cerrados_publicados.length > 0)
			return "permitir-copia";
	}
	
	// Establece que dialogo se debe usar en función de los parámetros de cada problema
	// al darle al botón borrar.
	$scope.deleteDialogButtons = function(problema) {
		if (angular.isUndefined(problema))
			return;
		else if (problema.id_docs_cerrados_publicados.length > 0 || problema.id_docs_abiertos.length > 0)
			return "solo-volver";
	}


  });

problemsControllers.controller('ProblemViewCtrl', function($scope, $http, $routeParams, $location) {
    if ($routeParams.id_problema) {
        $scope.id_problema = $routeParams.id_problema;
        $http.get("get_problem_parsed.php?id_problema=" + $scope.id_problema).success(function(data){
            $scope.problema = data;
            console.log(data);
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

			// Si ademas del id_problema, recibimos un parámetro "copy"
			if ($routeParams.copy) {
				$scope.id_problema = "Copia";
				$scope.problema.id_problema = $scope.id_problema;
				$scope.problema.resumen += " (copia)";
			}
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
			window.history.back();
    	})
		.error(function(data){
			console.log("Error al guardar problema.");
		});

    };

});


/***** Controladores para documentos finales *****/

// Controlador para la vista de lista de documentos finales
// Carga la lista inicial (por GET al servidor) y define funciones
// de respuesta a los diferentes botones
problemsControllers.controller('DocListCtrl', function($scope, $http, $location, $filter) {
    // Rellenar la lista
    $http.get("get_docs_list.php").success(function(data){
        $scope.docs = data;
    });

    $scope.vars = {'query': "" }; 

    // Retorna la lista de los documentos elegidos por lo que hay en el query
    // El query se interpreta como una serie de términos separados por espacios
    // Cada término puede llevar un prefijo, que indica el campo donde se busca
    //   "t:" Titulación
    //   "a:" Asignatura
    //   "c:" Convocatoria
    //   "f:" Fecha
    // Si no hay tal prefijo, se busca en cualquiera de los campos
    // Esta función debe ser llamada cada vez que cambie la query
    $scope.filtrar = function () {
        var queries = $scope.vars.query.split(" ");
        var obj_query, txt_query;
        $scope.vars.elegidos = $scope.docs;
        for (var i=0; i<queries.length; i++) {
            if (queries[i]==" ") continue;
            if (queries[i].indexOf(":")==1) { //tenemos prefijo 
                txt_query = queries[i].slice(2);
                switch(queries[i].charAt(0)){
                    case "t": obj_query = {'titulacion': txt_query };
                              break;
                    case "a": obj_query = {'asignatura': txt_query };
                              break;
                    case "c": obj_query = {'convocatoria': txt_query };
                              break;
                    case "f": obj_query = {'fecha': txt_query };
                              break;
                }
            } else {
                obj_query = queries[i];
            }
            $scope.vars.elegidos = $filter("filter")($scope.vars.elegidos, obj_query);
        }
    }

    // Esta función retorna un booleano que indica si el problema dado está o no 
    // en la lista de elegidos, y se usa para controlar el ng-show de cada problema
    $scope.elegido = function (problema) {
        if ($scope.vars.elegidos) // Si la lista existe
            return ($scope.vars.elegidos.indexOf(problema)!=-1);
        else 
            return true;
    }
    // Cuando el usuario pincha en un estado
    $scope.filterState = function (state) {
        $scope.vars.query += " " + state;  // Lo usamos como valor de la query
        $scope.filtrar();
    };
    // En la X a la derecha del query, borramos la query
    $scope.clearQuery = function () {
        $scope.vars.query=""; 
        $scope.filtrar();
    };


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
	
// Función que envía la información necesaria al servidor para que este genere y
// permita al usuario descargar los ficheros LaTeX correspondientes al documento seleccionado.
	$scope.downloadDoc = function (id) {
			
		var info = {"id_doc":id, 
					"con_soluciones": $("#con-soluciones"+id).is(':checked'), 
					"con_explicaciones":$("#con-explicaciones"+id).is(':checked')};

		$http.post("generar_examen.php", info).success(function(data){
        	console.log("Descargando el documento " + id + ".");
			console.log("Con soluciones: " + $("#con-soluciones"+id).is(':checked'))	
			console.log("Con explicaciones: " + $("#con-explicaciones"+id).is(':checked'))
        	// Volcar a consola la respuesta del servidor
			console.log(data);
			// Acceder por GET a la url recibida
            window.location.href= data.url;
    	})
		.error(function(data){
			console.log("Error al descargar el documento " + id + ".");
		});

	}
	
	// Función que gestiona los eventos de cambio de estado de los documentos
	// cuando se pulsa los botones 'Abrir', 'Cerrar' o 'Publicar'.
	// Actualiza el estado del documento correspondiente en base de datos y en la vista.
	$scope.changeStatus = function (doc, nuevo_estado) {
		var info = {"id_doc":doc.id_doc, "nuevo_estado":nuevo_estado };
		$http.post("change_doc_status.php", info).success(function(data){
        	console.log("Cambiando estado de documento " + doc.id_doc + " al estado " + nuevo_estado + ".");
        	// Volcar a consola la respuesta del servidor
			console.log(data);
			// Actualizamos el estado en la vista para que sea inmediatamente visible el cambio.
			doc.estado = nuevo_estado;
    	})
		.error(function(data){
			console.log("Error al cambiar el estado del documento " + doc.id_doc + " al estado " + nuevo_estado + ".");
		});


	}

});

// Este controlador maneja la vista /view-doc, /edit-doc y la vista /new-doc
problemsControllers.controller('DocDetailsCtrl', function($scope, $http, $routeParams, $location, $filter) {
    
	// En cualquier caso, hay que cargar los resúmenes y tags de todos los
	// problemas almacenados en la aplicación para mostrarlos en la lista dragable.
	// Rellenar la lista
    $http.get("get_problems_list.php").success(function(data){
        $scope.problemas_bd = data;
        $scope.actualizar_lista_problemas();
    });

    $scope.actualizar_puntuacion = function () {
        if ($scope.doc) {
            var total_puntos = 0;
            for (var i=0; i<$scope.doc.problemas.length; i++)
              total_puntos += $scope.doc.problemas[i].puntos;
            $scope.doc.total_puntos = total_puntos;
        }
    }

    $scope.actualizar_lista_problemas = function () {
        if ($scope.problemas_bd && $scope.doc) {
            for (var i=0; i<$scope.doc.problemas.length; i++) {
                for (var j=0; j<$scope.problemas_bd.length; j++) {
                    if ($scope.problemas_bd[j].id_problema == $scope.doc.problemas[i].id_problema)
                    $scope.problemas_bd.splice(j,1);
                }         
            }
         }
    }

    $scope.quitarProblema = function (index) {
        // Meterlo en la lista general de problemas
        $scope.problemas_bd.push($scope.doc.problemas[index]);
        // Sacarlo de la lista del documento
        $scope.doc.problemas.splice(index,1);
    }

    $scope.anadirProblema = function (index) {
        // Meterlo en la lista del documento
        $scope.doc.problemas.push($scope.problemas_bd[index]);
        // Sacarlo de la lista general de problemas
        $scope.problemas_bd.splice(index,1);
    }

    $scope.vaciarDocumento = function () {
        // Meterlos todos en la lista general
        Array.prototype.push.apply($scope.problemas_bd, $scope.doc.problemas);
        // Y quitarlos del documento
        $scope.doc.problemas = [];
    }

	// Si recibimos un id_doc, es la vista /edit/:id_doc o la vista /view/:id_doc
    if ($routeParams.id_doc) {
        // Entonces usamos el id para pedir datos del documento al servidor
        $scope.id_doc = $routeParams.id_doc;
        $http.get("get_doc.php?id_doc=" + $scope.id_doc).success(function(data){
            $scope.doc = data;
            $scope.actualizar_puntuacion();
            $scope.actualizar_lista_problemas();
        });
    } else {
        // Si no recibimos un id_doc, es la vista /new-doc
        // En este caso creamos un documento nuevo, con un id especial para
        // poder detectarlo más tarde cuando haya que enviarlo al servidor.
        $scope.id_doc = "Nuevo";
        $scope.doc = {
            "id_doc":$scope.id_doc, "titulacion": "", "asignatura":"", "convocatoria":"", "instrucciones":"", "fecha":"", "estado":"abierto",
            "problemas": [],
            "total_puntos": 0
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
			window.history.back();
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
        connectWith: '.connector',
        placeholder: 'beingDragged'
    }

    // Esta función es llamada cuando cambia el valor de la query (via ng-change)
    // para actualizar la lista de los que encajan en el filtro
    //
    // Esta función implementa la siguiente sintaxis de queries
    // La query se compone de varias palabras separadas por espacios. Para cada una de ellas:
    // - Si comienza por + o #, se omite este primer carácter y resto se considera un tag
    // - En otro caso no se considera tag
    // Los términos considerados tags deben aparecer en la lista de tags del problema
    // Los no considerados tags deben aparecer en cualquier otra cadena del problema (ej:su resumen)
    // El problema es elegido si supera **todas** las palabras de la query
    //
    // Así por ejemplo, la query "+C +fi prog" encajará con todos los problemas que tengan tags
    // que contengan "C" y "fi" y además tengan "prog" en su resumen (o tags)
    $scope.filtrar = function () {
        var queries = $scope.vars.query.split(" ");
        $scope.vars.elegidos = $scope.problemas_bd;
        for (var i=0; i<queries.length; i++) {
            if (queries[i].indexOf("#")==0 || queries[i].indexOf("+")==0) 
                $scope.vars.elegidos = $filter("filter")($scope.vars.elegidos, {'tags': queries[i].slice(1)});
            else
                $scope.vars.elegidos = $filter("filter")($scope.vars.elegidos, queries[i]);
        }
    }

    // Esta función retorna true si el problema debe mostrarse en la lista
    // de seleccionables, y false si no. Es usada por la directiva ng-show en la
    // lista de problemas, en lugar de usar un filtro angular, de este modo el
    // arrastrar y soltar funciona aunque haya elementos filtrados
    //
    // Para determinar si está elegido o no, lo buscamos en la lista
    // de elegidos (actualizada desde filtrar() cada vez que cambia la query)
    $scope.elegido = function (problema) {
        if ($scope.vars.elegidos) // Si la lista existe
            return ($scope.vars.elegidos.indexOf(problema)!=-1);
        else 
            return true;

    }

    // DEBUG, llama a una función callback cada vez que cambia el valor de "model"
	// El valor 'true' de $watch sirve para que también se actualice
    // en el caso de que el valor cambie, no solo la referencia.
	// De esta forma al cambiar el orden de los problemas de un mismo panel también 
	// se llama a la función callback.
    $scope.$watch("problemas_bd", function(value) {
        $scope.filtrar();
		// if (!angular.isUndefined($scope.problemas_bd))
        // console.log("Problemas_BD: " + value.map(function(e){return e.id_problema}).join(','));
    },true);

    
    // DEBUG, llama a una función callback cada vez que cambia el valor de "source"
    $scope.$watch("doc.problemas", function(value) {
        $scope.actualizar_puntuacion();
    },true);


	// Al cargar la página por primera vez las variables problemas_bd/doc.problemas 
	// aun no está cargada y se genera un error en consola. Se controla comprobando
	// si la variable está definida.
    $scope.sourceEmpty = function() {
		if (angular.isUndefined($scope.problemas_bd))
			return false;
        if ($scope.vars.elegidos)
            return ($scope.vars.elegidos.length ==0);
	    return ($scope.problemas_bd.length == 0);
    }

    $scope.targetEmpty = function() {
		if (angular.isUndefined($scope.doc))
			return false;
		else
        	return $scope.doc.problemas.length == 0;
    }
	
	// En la X a la derecha del query, borramos la query
    $scope.clearQuery = function() { $scope.vars.query=""; $scope.filtrar();};
	
	// Cuando el usuario pincha en un tag
    $scope.filterTag = function (tag) {
        $scope.vars.query += " #" + tag;  // Lo añadimos como busqueda por tag a la query
		$scope.filtrar();
    };

});


// Función (controller) que controla qué sección del menú de navegación está activa.
function HeaderController($scope, $location) 
{ 
    $scope.isActive = function (viewLocation) { 
        return viewLocation === $location.path();
    };

    $scope.isCollapsed =true;

}

// Directiva para crear diálogos. Permite ejecutar una función cuando se da a OK.
angular.module('ngReallyClickModule', ['ui.bootstrap'])
  .directive('ngReallyClick', ['$modal',
    function($modal) {
		
	  // Controlador del diálogo. Maneja los eventos de los botones del diálogo.
      var ModalInstanceCtrl = function($scope, $modalInstance) {
        $scope.ok = function() {
          $modalInstance.close();
        };

        $scope.cancel = function() {
          $modalInstance.dismiss('cancel');
        };
      };

      return {
        restrict: 'A', //Directiva en forma de atributo
        // Atributos
		scope:{
          ngReallyClick:"&" //Binding de una función en atributo ng-really-click
        },
        link: function(scope, element, attrs) {
          // Función bind
		  element.bind('click', function() {
			// Mensaje que se muestra en el diálogo. Se escribe en otro atributo ng-really-message.
            var message = attrs.ngReallyMessage || "";
			var custom_buttons = attrs.customDialogButtons;
			
			// Si no se escribe nada en ng-really-message NO se muestra ningún diálogo.
			if (message != "") {
				var modalHtml;		
				// Generar un diálogo diferente en función de si hay que cambiar los botones del diálogo.
				if (custom_buttons == "solo-volver") {
					// Dialogo quitando botones. Mostrando solo un botón Volver. Adecuado para advertencias.
    	        	modalHtml = '<div class="modal-body">' + message + '</div>';
        	   	 	modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="cancel()">Volver</button></div>';
				}
				// Diálogo con un botón que permita hacer la copia de un problema.
				else if (custom_buttons == "permitir-copia") {
					modalHtml = '<div class="modal-body">' + message + '</div>';
        	   	 	modalHtml += '<div class="modal-footer" ng-controller="ProblemListCtrl"><button class="btn btn-success" ng-click="copyProblem('+attrs.problema+'); cancel()">Hacer copia</button><button  class="btn btn-warning" ng-click="cancel()">Cancelar</button></div>';
				}
				else {
					// Dialogo habitual.
    	        	modalHtml = '<div class="modal-body">' + message + '</div>';
        	   	 	modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="ok()">OK</button><button  class="btn btn-warning" ng-click="cancel()">Cancelar</button></div>';
				}
			
				// Creación del diálogo: con su template HTML y su controlador que gestiona OK y Cancelar
            	var modalInstance = $modal.open({
            	  template: modalHtml,
            	  controller: ModalInstanceCtrl
           	 	});
	
				// Si se da a ok se ejecuta la función que esté en ng-really-click.
            	modalInstance.result.then(function() {
					scope.ngReallyClick(); //raise an error : $digest already in progress
				}, function() {
           		//Modal dismissed
            	});
            	//*/
            }
			
          });

        }
      }
    }
  ]);
