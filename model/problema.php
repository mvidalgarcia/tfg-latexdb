<?php

class Problema { 
 	/* Atributos */
	public $id_problema;
    public $enunciado_general;
    public $resumen;
	public $posicion; 						// Posición del problema dentro de un documento final
	public $num_preguntas;					// Número de preguntas que tiene el problema
    public $puntos;                         // Puntuación total del problema
	public $estaEnDocAbierto;				// Booleano que indica si el problema está en algún documento en estado 'abierto'
	public $estaEnDocCerradoPublicado;		// Booleano que indica si el problema está en algún documento en estado 'cerrado' o 'publicado'
	public $preguntas = array();			// Composición clase Pregunta
	public $tags = array();					// Composición clase Tag
	public $imagenes = array();				// Composición clase Imagen
} 

?>
