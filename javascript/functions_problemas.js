$(function() {
	if (!(typeof twig_preguntas === 'undefined'))
		alert (twig_preguntas);
	if (!(typeof twig_npreguntas === 'undefined'))
		alert (twig_npreguntas);
	/****** Inicializaciones ******/
	var numPreguntas;
	// Si la variable está definida es que provengo del boton Ver/Editar.
	// Cargar el número de preguntas que deben aparecer en la interfaz
	if (!(typeof twig_npreguntas === 'undefined'))
	{
		alert ("twig_npreguntas NO es NULL!");
		numPreguntas = twig_npreguntas;
	}
	else
	{
		alert ("twig_npreguntas es NULL!");
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
		// Caso de "Crear ejercicio".
		if (typeof twig_npreguntas === 'undefined')
		{
			idForm.innerHTML += "<label for='enunciado"+i+"'>Enunciado: </label><span class='error'>* <?php echo $enunciadoErr;?></span><br/>";
			idForm.innerHTML += "<textarea id='enunciado"+i+"' name='enunciado"+i+"' rows='3' cols='80'>"+
			"Introduce aquí el enunciado de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='solucion"+i+"'>Solución: </label><br/>";
			idForm.innerHTML += "<textarea id='solucion"+i+"' name='solucion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la solución de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='explicacion"+i+"'>Explicación: </label><br/>";
			idForm.innerHTML += "<textarea id='explicacion"+i+"' name='explicacion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la explicación de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='puntuacion"+i+"'>Puntuación: </label><span class='error'>* <?php echo $puntuacionErr;?></span><br/>";
			idForm.innerHTML += "<input type='number' id='puntuacion"+i+"' name='puntuacion"+i+"' value='1'><br/>";

		}
		// Caso de "Ver/Editar".
	   	else
		{
			idForm.innerHTML += "<label for='enunciado"+i+"'>Enunciado: </label><span class='error'>* <?php echo $enunciadoErr;?></span><br/>";
			idForm.innerHTML += "<textarea id='enunciado"+i+"' name='enunciado"+i+"' rows='3' cols='80'>"+ twig_preguntas[i].enunciado +"</textarea><br/>";
			idForm.innerHTML += "<label for='solucion"+i+"'>Solución: </label><br/>";
			idForm.innerHTML += "<textarea id='solucion"+i+"' name='solucion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la solución de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='explicacion"+i+"'>Explicación: </label><br/>";
			idForm.innerHTML += "<textarea id='explicacion"+i+"' name='explicacion"+i+"' rows='3' cols='80'> "+
        	"Introduce aquí la explicación de la pregunta...</textarea><br/>";
			idForm.innerHTML += "<label for='puntuacion"+i+"'>Puntuación: </label><span class='error'>* <?php echo $puntuacionErr;?></span><br/>";
			idForm.innerHTML += "<input type='number' id='puntuacion"+i+"' name='puntuacion"+i+"' value='1'><br/>";
		}
		// Si es la última pregunta que se imprime se añade un enlace para poder añadir una nueva pregunta.
		if (i == numPreguntas)
			idForm.innerHTML += "<button id='incrementarPreguntas'>Añadir pregunta</button>";

	}
	// Elemento input HTML oculto donde se indica el número de preguntas actual. 
	// Sirve para saber el número de preguntas al enviar el formulario al script PHP.
	idForm.innerHTML += "<input type='hidden' name='npreg' value='"+numPreguntas+"'>"; 

	/****** Manejadores de eventos ******/
	
	// Evento que al pulsar el botón de la IU "Añadir pregunta", añade un nuevo formulario para una pregunta más.
	$("#incrementarPreguntas").click(function() 
	{
		//Incrementar numPreguntas
		localStorage.setItem("numPreguntas", parseInt(numPreguntas) + 1);
		location.reload();
	});
	
	// Evento que al pulsar el botón de la IU "Eliminar pregunta", elimina el formulario de la última pregunta.
	$("#decrementarPreguntas").click(function()
	{
		//Decrementar numPreguntas
		if (!(numPreguntas-1 < 1))
			localStorage.setItem("numPreguntas", parseInt(numPreguntas) - 1);
		location.reload();
	});

});	
