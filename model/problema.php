<?php

class Problema { 
 	/* Atributos */
	public $id_problema;
    public $enunciado_general;
    public $resumen;
	public $posicion; 				// Posición del problema dentro de un documento final
	public $num_preguntas;
	public $preguntas = array();	// Composición clase Pregunta
	public $tags = array();		// Composición clase Tag
	public $imagenes = array();	// Composición clase Imagen
} 

?>
