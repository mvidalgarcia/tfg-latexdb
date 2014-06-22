<?php

class Problema { 
 	/* Atributos */
	public $id_problema;
    public $enunciado_general;
    public $resumen;
	public $posicion; 								// Posición del problema dentro de un documento final
	public $num_preguntas;							// Número de preguntas que tiene el problema
    public $puntos;									// Puntuación total del problema
	public $id_docs_abiertos = array();				// Array que guarda los ids de documentos abiertos a los que pertenece el problema, si existen
	public $id_docs_cerrados_publicados = array();	// Array que guarda los ids de documentos cerrados/publicados a los que pertenece el problema, si existen
	public $preguntas = array();					// Composición clase Pregunta
	public $tags = array();							// Composición clase Tag
    public $imagenes = array();						// Composición clase Imagen
    public $id_padre;                               // Si el problema ha sido clonado de otro, aquí el id del original. Si no, null
} 

?>
