$(function() {
	if (!(typeof twig_preguntas === 'undefined'))
		console.log (twig_preguntas);
	if (!(typeof twig_npreguntas === 'undefined'))
		console.log (twig_npreguntas);
	/****** Inicializaciones ******/
	var numPreguntas;
	// Si la variable está definida es que provengo del boton Ver/Editar.
	// Cargar el número de preguntas que deben aparecer en la interfaz
	if (!(typeof twig_npreguntas === 'undefined'))
	{
		console.log ("twig_npreguntas NO es NULL!");
		numPreguntas = twig_npreguntas;
	}
	else
	{
		console.log ("twig_npreguntas es NULL!");
		var numPreguntas = localStorage.getItem("numPreguntas");
		if (numPreguntas == null)
			localStorage.setItem("numPreguntas", 1);	
	}
	
	// Dibujar el número de formularios para las preguntas de los problemas correspondientes
	//var numPreguntas = document.getElementById('numPreguntas').value;
	var idForm = document.getElementById('formProblemas');
	for (var i = 1; i <= numPreguntas; i++)
	{ 
	   	// Si es la última pregunta que se imprime se añade un enlace para poder eliminarla.
		if (i == numPreguntas)
			idForm.innerHTML += "<h4>Pregunta "+i+"<button id='decrementarPreguntas'>Eliminar pregunta</button></br>";
		else	
			idForm.innerHTML += "<h4>Pregunta "+i+"</h4>";
		// Caso de "Crear ejercicio"
		if (typeof twig_npreguntas === 'undefined')
		{
			idForm.innerHTML += "<label for='enunciado"+i+"'>Enunciado: </label><br/>";
			idForm.innerHTML += "<textarea id='enunciado"+i+"' name='enunciado"+i+"' rows='3' cols='80'>"+
			"Introduce aquí el enunciado de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='solucion"+i+"'>Solución: </label><br/>";
			idForm.innerHTML += "<textarea id='solucion"+i+"' name='solucion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la solución de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='explicacion"+i+"'>Explicación: </label><br/>";
			idForm.innerHTML += "<textarea id='explicacion"+i+"' name='explicacion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la explicación de la pregunta...</textarea><br/>";
		}
		// Caso de "Ver/Editar"
	   	else
		{
			idForm.innerHTML += "<label for='enunciado"+i+"'>Enunciado: </label><br/>";
			idForm.innerHTML += "<textarea id='enunciado"+i+"' name='enunciado"+i+"' rows='3' cols='80'>"+ twig_preguntas[i].enunciado +"</textarea><br/>";
			idForm.innerHTML += "<label for='solucion"+i+"'>Solución: </label><br/>";
			idForm.innerHTML += "<textarea id='solucion"+i+"' name='solucion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la solución de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='explicacion"+i+"'>Explicación: </label><br/>";
			idForm.innerHTML += "<textarea id='explicacion"+i+"' name='explicacion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la explicación de la pregunta...</textarea><br/>";
		}
		// Si es la última pregunta que se imprime se añade un enlace para poder añadir una nueva pregunta.
		if (i == numPreguntas)
			idForm.innerHTML += "<button id='incrementarPreguntas'>Añadir pregunta</button>";
	}

	/****** Manejadores de eventos ******/
	
	// Evento que al pulsar el botón de la IU "Añadir pregunta", añade un nuevo formulario para una pregunta más
	$("#incrementarPreguntas").click(function() 
	{
		//Incrementar numPreguntas
		localStorage.setItem("numPreguntas", parseInt(numPreguntas) + 1);
		location.reload();
	});
	
	// Evento que al pulsar el botón de la IU "Eliminar pregunta", elimina el formulario de la última pregunta
	$("#decrementarPreguntas").click(function()
	{
		//Decrementar numPreguntas
		if (!(numPreguntas-1 < 1))
			localStorage.setItem("numPreguntas", parseInt(numPreguntas) - 1);
		location.reload();
	});

});	
