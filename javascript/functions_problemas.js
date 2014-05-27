$(function() {
    // Funcion interna para añadir una pregunta al formulario
    add_pregunta = function(i, last, enunciado, solucion, explicacion) {
			idForm.innerHTML += "<h4>Pregunta "+i+"</h4>";
            if (last) idForm.innerHTML += " <button id='decrementarPreguntas'>Eliminar pregunta</button><br/>";
			idForm.innerHTML += "<label for='enunciado"+i+"'>Enunciado: </label><br/>";
			idForm.innerHTML += "<textarea id='enunciado"+i+"' name='enunciado"+i+"' rows='3' cols='80'>"+
			                     enunciado + "</textarea><br/>";
			idForm.innerHTML += "<label for='solucion"+i+"'>Solución: </label><br/>";
			idForm.innerHTML += "<textarea id='solucion"+i+"' name='solucion"+i+"' rows='3' cols='80'>"+
        	                     solucion + "</textarea><br/>";
			idForm.innerHTML += "<label for='explicacion"+i+"'>Explicación: </label><br/>";
			idForm.innerHTML += "<textarea id='explicacion"+i+"' name='explicacion"+i+"' rows='3' cols='80'>"+
        	                    explicacion + "</textarea><br/>";
    }

    // DEBUG
	console.log ("twig_preguntas", twig_preguntas);
	console.log ("twig_npreguntas", twig_npreguntas);

	/****** Inicializaciones ******/
	var numPreguntas;
	// Si la variable está definida es que provengo del boton Ver/Editar.
	// Cargar el número de preguntas que deben aparecer en la interfaz
	if (!(typeof twig_npreguntas === 'undefined'))
	{
		numPreguntas = twig_npreguntas;
	}
	else
	{
        numPreguntas = 0;
	}
    console.log("numPreguntas:", numPreguntas);
	
	// Dibujar el número de formularios para las preguntas de los problemas correspondientes
	//var numPreguntas = document.getElementById('numPreguntas').value;
	var idForm = document.getElementById('formProblemas');
    var last = false;
	for (var i = 1; i <= numPreguntas; i++)
	{ 
	   	// Si es la última pregunta que se imprime se añade un enlace para poder eliminarla.
    
        if (i == numPreguntas) last = true;
        // Rellenar con el contenido de esa pregunta
        add_pregunta(i, last,
                        twig_preguntas[i-1].enunciado,
                        twig_preguntas[i-1].solucion, 
                        twig_preguntas[i-1].explicacion);
	}
    // Añadir botón para crear pregunta adicional
    idForm.innerHTML += "<button id='incrementarPreguntas'>Añadir pregunta</button>";

	/****** Manejadores de eventos ******/
	
	// Evento que al pulsar el botón de la IU "Añadir pregunta", añade un nuevo formulario para una pregunta más.
	$("#incrementarPreguntas").click(function() 
	{
        $("#incrementarPreguntas").remove();
        add_pregunta(i, true, "Introduce aquí el enunciado...", 
                        "Introduce aquí la solución...",
                        "Introduce aquí la explicación..."
                    );
        idForm.innerHTML += "<button id='incrementarPreguntas'>Añadir pregunta</button>";
	}
	);
	
	// Evento que al pulsar el botón de la IU "Eliminar pregunta", elimina el formulario de la última pregunta.
	$("#decrementarPreguntas").click(function()
	{
		//Decrementar numPreguntas
		if (!(numPreguntas-1 < 1))
			localStorage.setItem("numPreguntas", parseInt(numPreguntas) - 1);
		location.reload();
	});

});	
