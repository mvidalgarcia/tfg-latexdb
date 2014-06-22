-- MySQL dump 10.14  Distrib 5.5.38-MariaDB, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: tfgdb
-- ------------------------------------------------------
-- Server version	5.5.38-MariaDB-1~precise-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `doc_final`
--

DROP TABLE IF EXISTS `doc_final`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_final` (
  `id_doc` int(11) NOT NULL AUTO_INCREMENT,
  `titulacion` varchar(80) DEFAULT NULL,
  `asignatura` varchar(80) DEFAULT NULL,
  `convocatoria` varchar(40) DEFAULT NULL,
  `instrucciones` text,
  `fecha` date DEFAULT NULL,
  `estado` enum('abierto','cerrado','publicado') NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `Comentario` text NOT NULL,
  PRIMARY KEY (`id_doc`),
  KEY `id_padre` (`id_padre`),
  CONSTRAINT `doc_final_ibfk_1` FOREIGN KEY (`id_padre`) REFERENCES `doc_final` (`id_doc`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_final`
--

LOCK TABLES `doc_final` WRITE;
/*!40000 ALTER TABLE `doc_final` DISABLE KEYS */;
INSERT INTO `doc_final` VALUES (305,'Ingeniería Informática','Fundamentos de Informática','Control de los temas 4 y 5','El examen tiene dos partes, y cada una puntúa de 0 a 10. Es necesario un mínimo de 4 puntos en cada parte.\n\nLa parte de programación tiene 12 preguntas, por lo que cada una vale 0.83. La parte de bases de\ndatos tiene 8 preguntas por lo que cada una vale 1.25.\n\nLa parte de programación representa el 45\\% de la nota final de teoria, y la de bases de datos el\n25\\% (correspondiendo el 30\\% restante al primer control ya realizado).','2011-01-27','abierto',NULL,''),(306,'Ingeniería Informática','Fundamentos de informática','Extraordinaria de Junio','El examen cubre todo el temario. Todas las preguntas, salvo indicación expresa de lo contrario, tienen la misma puntuación (0.4). Las respuestas equivocadas no restan puntos.\nDebes responder en los huecos reservados para ello, sin exceder el espacio disponible.','2011-05-24','abierto',NULL,'');
/*!40000 ALTER TABLE `doc_final` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagen`
--

DROP TABLE IF EXISTS `imagen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagen` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `url` text,
  PRIMARY KEY (`id_imagen`)
) ENGINE=InnoDB AUTO_INCREMENT=405 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagen`
--

LOCK TABLES `imagen` WRITE;
/*!40000 ALTER TABLE `imagen` DISABLE KEYS */;
INSERT INTO `imagen` VALUES (400,'http://localhost/tfg/imagen1.png'),(401,'http://localhost/tfg/imagen2.png'),(402,'http://localhost/tfg/imagen3.png'),(403,'http://localhost/tfg/imagen4.png'),(404,'http://localhost/tfg/imagen5.png');
/*!40000 ALTER TABLE `imagen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pregunta`
--

DROP TABLE IF EXISTS `pregunta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pregunta` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `enunciado` text NOT NULL,
  `solucion` text,
  `explicacion` text,
  `puntuacion` int(11) NOT NULL DEFAULT '1',
  `posicion` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `id_problema` (`id_problema`),
  CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=353 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pregunta`
--

LOCK TABLES `pregunta` WRITE;
/*!40000 ALTER TABLE `pregunta` DISABLE KEYS */;
INSERT INTO `pregunta` VALUES (293,'Define qué es una instancia de una base de datos e indica si cambia a menudo o no.','Es la información que contiene una BD en un instante dado. Cambia continuamente, debido a las altas, bajas y modificaciones','',1,1,112),(294,'Cuando, para relacionar dos tablas dadas, se crea una tercera tabla\nque contiene las PK de las primeras, ¿qué tipo de relación entre\ntablas implementamos?','n:n','',1,1,113),(295,'Inventa un ejemplo para una tabla adicional que tenga una relación 1:1\n    con la tabla de estrellas.     \\TamanioHueco{5}','Una tabla que almacene datos adicionales sobre cada estrella, como\n      por ejemplo su color, distancia a la que se encuentra, tamaño, tipo\n      de estrella, etc.','',1,1,114),(296,'Escribe qué campos tendría la tabla ·Estrellas·, usando la notación\nempleada en clase para marcar las claves propias (·@·) y\nlas ajenas (subrayado).     \\TamanioHueco{9}','\\begin{Verbatim}[fontsize=\\small,commandchars=\\·\\«\\»]\n-@Nombre\n-Magnitud\n-Ascension_recta\n-Declinación\n-·ul«Constelación»\n\\end{Verbatim}','',1,2,114),(297,'Escribe qué campos tendría la tabla ·Constelaciones·, usando la notación\nempleada en clase para marcar las claves propias (·@·) y\nlas ajenas (subrayado).\n\\TamanioHueco{7}','\\begin{Verbatim}[fontsize=\\small,commandchars=\\·\\«\\»]\n-@Nombre\n\\end{Verbatim}','',1,3,114),(298,'Como ayuda a las cuestiones siguientes, se proporciona seguidamente un\nbreve recordatorio de la sintaxis de algunas consultas~SQL.\n\n\\begin{Verbatim}[fontsize=\\small,frame=single,commandchars=\\·\\«\\»]\n·textnormal«Consulta simple:»\nSELECT ·emph«campos» FROM ·emph«tabla» WHERE ·emph«condición»;\n\n·textnormal«Agrupación y totales:»\nSELECT ·emph«campos», funcion(·emph«campo») FROM ·emph«tabla»\n       WHERE ·emph«condición» GROUP BY ·emph«campo»;\n       ·textnormal«Funciones:» COUNT(), SUM(), AVG(), ...\n\n·textnormal«Composición de tablas:»\nSELECT ·emph«campos» FROM ·emph«tabla1» INNER JOIN ·emph«tabla2»\n    ON ·emph«tabla1.clave_ajena»=·emph«tabla2.clave_propia»;\n\\end{Verbatim}\n\nEscribe la consulta SQL que muestre las estrellas de magnitud menor de\n2 (que son visibles a simple vista).    \\TamanioHueco{3}','\\begin{Verbatim}[fontsize=\\small]\nSELECT Nombre FROM Estrellas\n       WHERE Magnitud<2.0;\n\\end{Verbatim}','',1,4,114),(299,'¿Cuál es el cometido del compilador de C?','El compilador traduce el código C (código fuente) en una versión ejecutable por la CPU (código máquina). Durante la traducción detecta posibles errores de sintaxis','',1,1,109),(308,'¿Cuáles son las cuatro etapas en el ciclo de vida de una base de datos?\n(aplicables también a otros desarrollos de software)','Análisis, Diseño, Implementación y Pruebas y mantenimiento.','',1,1,115),(309,'Pon un par de ejemplos de datos cartográficos que suelan almacenarse en\n formato vectorial en las bases de datos geográficas.','Trazado de carreteras, cursos de ríos, contornos de lagos, de regiones o países.','',1,1,116),(310,'\\def\\dato{49} Convierte el número \\dato (decimal) a su representación binaria con 8 bits y\n  a hexadecimal','Binario: 110001\nHex: 31h','',1,1,117),(311,'Codifica en binario, en 8 bits, el número negativo -51','11001101','',1,1,118),(312,'Un disco duro pone en su caja que su capacidad es de  512\\,GB. \nEl comprador, que desconoce el estándar internacional de unidades,\n cree equivocadamente que GB son lo mismo que GiB. En realidad el disco\n ¿tiene más o menos bytes de lo que él cree? ¿cuántos más o menos? Puedes\n dejar indicada la operación en la respuesta, pero las unidades (bytes)\n han de estar correctas. Recuerda que $1024=2^{10}$.','Tiene menos: $512\\times(2^{30}-10^{9})$ = $512\\times{73\\,741\\,824}$ =\n{37\\,755\\,813\\,888} (unos 35\\,GiB).','',1,1,119),(313,'¿Cuáles son los nombres de los dos bloques que aparecen sin etiquetar?','CPU y Memoria','',1,1,120),(314,'¿Cuáles son los nombres de los dos buses que aparecen sin etiquetar?','Bus de direcciones y bus de datos.','',1,2,120),(315,'Describe brevemente qué misión tiene el registro ·PC· dentro una CPU.','El registro PC (\\emph{Program Counter}) contiene la dirección de\n memoria a la que se irá a buscar la próxima instrucción a ejecutar.','',1,1,121),(316,'Une con líneas los siguientes ejemplos de Sistema Operativo con su tipo\n  (nota: pueden quedar puntos sin unir, o varias líneas ir al mismo punto).\n\n  \\begin{respuestaespecial}\\centerline{\n      \\begin{tikzpicture}[x=0.7cm]\n        \\tikzset{punto/.style={below=5mm of anterior, fill=black, inner sep=.5mm, circle},\n                 raya/.style={color=solucion, decorate, decoration=bent}}\n        % Columna de sistemas operativos\n        \\coordinate (anterior) at (0,0);\n        \\foreach \\p in {\n           Linux,\n           Android,\n           MS-DOS,\n           Windows {3.1}}{\n             \\node[punto,label=left:\\p] (\\p) {}; \\coordinate (anterior) at (\\p);\n          }\n        % Columna de tipos de SO\n        \\coordinate (anterior) at (2,.2);\n        \\foreach \\p in {\n           Monousuario y monotarea,\n           Monousuario y multitarea,\n           Multiusuario y monotarea,\n           Multiusuario y multitarea,\n           Empotrado}{\n             \\node[punto,label=right:\\p] (\\p) {}; \\coordinate (anterior) at (\\p);\n          }\n        % Solución (unir los puntos)\n        \\draw[raya]\n           (Linux)         -- (Multiusuario y multitarea)\n           (Android)       -- (Empotrado)\n           (Android)       -- (Monousuario y multitarea)\n           (MS-DOS)        -- (Monousuario y monotarea)\n           (Windows {3.1}) -- (Monousuario y multitarea)\n          ;\n       \\end{tikzpicture}\n  }\n  \\end{respuestaespecial}','','',1,1,122),(326,'Se tienen dos tablas, que llamaremos A y B. Se quiere implementar una\n  relación 1:n de modo que a 1 registro de la tabla A se puedan asociar\n  muchos registros de la tabla B, pero a cada registro de la tabla B sólo\n  se puede asociar uno de la A. ¿Cómo lo haríamos? Indica si debemos crear\n  otra tabla, o si hay que añadir un campo a alguna de las existentes (y a\n  cuál) y qué contendría la tabla o campo añadido.\n  \\TamanioHueco{7}','Habría que añadir un campo a la tabla B, conteniendo la PK del elemento\n    de la A con el que se relaciona cada elemento de B.','',1,1,126),(327,'Escribe qué campos tendría la tabla ·Usuarios·, con la notación\n    empleada en clase para marcar las claves propias (·@·) y\n    las ajenas (subrayado).\n    \\TamanioHueco{9}','\\begin{Verbatim}[fontsize=\\small,commandchars=\\·\\«\\»]\n-@Nick\n-Nombre\n-Contraseña\n-email\n      \\end{Verbatim}','',1,1,127),(328,'Escribe qué campos tendría la tabla ·Preguntas·, usando la notación\n    empleada en clase para marcar las claves propias (·@·) y\n    las ajenas (subrayado).\n    \\TamanioHueco{7}','\\begin{Verbatim}[fontsize=\\small,commandchars=\\·\\«\\»]\n-@IDpregunta\n-Titulo\n-Contenido\n-·ul«Nick» (del autor)\n      \\end{Verbatim}','',1,2,127),(329,'Escribe qué campos tendría la tabla ·Respuestas·, usando la notación\n    empleada en clase para marcar las claves propias (·@·) y\n    las ajenas (subrayado).\n    \\TamanioHueco{7}','\\begin{Verbatim}[fontsize=\\small,commandchars=\\·\\«\\»]\n-@IDrespuesta\n-Contenido\n-·ul«Nick» (del autor)\n-·ul«IDpregunta»\n      \\end{Verbatim}','',1,3,127),(330,'¿Crees que serían necesarias más tablas de las indicadas en las\n    preguntas anteriores para completar las relaciones en la base de datos?\n    Justifica tu respuesta\n    \\TamanioHueco{5}','No son necesarias más tablas, puesto que no hay relaciones $n:n$.','',1,4,127),(331,'Observa el siguiente código y escribe en el recuadro inferior qué saldrá\n  por pantalla\n  \\begin{listadoC}\na=4;  b=4;  c=5;\nif (a==b)\n  if (b<c)\n      cout << \"Uno\" << endl;\n  else\n      cout << \"Dos\" << endl;\ncout << \"Tres\" << endl;\n  \\end{listadoC}\n  \\TamanioHueco{3}','Uno\\\\\n    Tres','',1,1,125),(332,'¿Qué falta en los dos huecos del listado anterior?\n  \\TamanioHueco{4}','·indice_maximo·\\\\\n    ·datos[indice_maximo]·\\\\','',1,1,124),(333,'Escribe cómo sería el código completo de la función ·BuscarIndiceMaximo· que es\n  llamada desde el programa anterior.\n  \\TamanioHueco{24}','\\begin{Verbatim}[fontsize=\\small]\nint BuscarIndiceMaximo(int datos[], int cuantos)\n{\n  int imax, max=datos[0];\n  int i;\n  for (i=0; i<cuantos; i++)\n    if (datos[i]>max) {\n       max=datos[i];\n       imax=i;\n    }\n  return imax;\n}\n    \\end{Verbatim}','',3,2,124),(334,'Si dicha función se llama ·EsPrimo·, y el parámetro que recibe se llama\n  ·dato·, escribe la cabecera de dicha función.','·bool EsPrimo(int dato)·','',1,1,123),(335,'\\begin{enunciado}\n  Lo que sigue sería el código preliminar de dicha función (se ha omitido la cabecera,\n  que sería la que has puesto en la respuesta anterior). Este código, tal\n  como está, no funcionará correctamente.\n  \\begin{listadoC}\n// cabecera omitida\n{\n  bool esprimo=true;     // Supongamos que es primo\n  int divisor;\n  while (divisor<dato) {\n    if (dato%divisor==0) // si el resto es cero...\n       esprimo=false;    // ...no es primo\n    divisor++;           // probar otro\n  }\n  return esprimo;       // retornar resultado\n }\n  \\end{listadoC}\n\\end{enunciado}\n\n  El código anterior no funciona correctamente porque la variable ·divisor·\n  no ha sido inicializada con ningún valor. ¿Qué tendrías que añadir antes\n  del bucle ·while· para inicializarla correctamente?','·divisor=2;·','Hay que ir probando con diferentes divisores, comenzando con ·2·.\n    Comenzar con ·0· sería un error, ya que se intentaría una división por\n    cero. También sería un error comenzar con ·1·, ya que todos los enteros\n    son divisibles por ·1·, sean primos o no.',1,2,123),(336,'El código anterior no es eficiente porque incluso si ya ha detectado que\n  es primo, sigue probando con todos los enteros siguientes, y esto ya no\n  sería necesario. Reescribe el código para que sea más eficiente.\n  \\TamanioHueco{18}','\\begin{Verbatim}[fontsize=\\small]\n{\n// no necesitamos declarar la esprimo\nint divisor;\ndivisor=2;\nwhile (divisor<dato) {\n  if (dato%divisor==0)\n     return false;\n  divisor++;\n}\n// Si llegamos aqui sin haber encontrado\n// divisores es que era primo\nreturn true;\n}\n    \\end{Verbatim}','',1,3,123),(337,'\\begin{enunciado}\n  A continuación se muestra el programa principal (·main·) que,\n  haciendo uso de la función anterior, debe imprimir los 10 primeros\n  números primos mayores de 1000. El programa contiene partes tapadas sobre\n  las que se harán preguntas. El tamaño de la zona tapada no guarda\n  relación con la longitud de la respuesta.\n  \\begin{listadoC}\n#include<iostream>\nusing namespace std;\n\n// Omitido el código de la funcion EsPrimo\n// que vendría aquí\n\nint main(void)\n{\n  int n=1000;\n  int contador=0;\n  bool esprimo;\n\n  while(·falta«contador<10               ») {\n    esprimo=·falta«EsPrimo(n)     »;\n    if (esprimo) {\n      cout << n << endl;\n      ·falta«contador++;        »·label«c.primos.001.1»\n    }\n    n=n+1;\n  }\n  return 0;\n}\n\\end{listadoC}\n\\end{enunciado}\n\n  ¿Qué falta en el hueco de la condición del ·while·?','·(contador < 10)·','',1,4,123),(338,'¿Qué falta en el hueco donde se asigna un valor a la variable ·esprimo·?','·esprimo=EsPrimo(n)·','',1,5,123),(339,'¿Qué falta en el hueco de la línea~\\ref{c.primos.001.1}?','·contador++;· o ·contador=contador+1;·','',1,6,123),(340,'Cuando ejecutemos el programa ¿cuántas líneas aparecerán en pantalla?','35 líneas (5 iteraciones de ·i· por 7 de ·j·)','',1,1,105),(341,'¿Cuántas veces aparecerá el número cero en la salida del programa?','11 veces','En la primera iteración del bucle externo, la ·i· vale 0 por lo que independientemente \nde ·j· el resultado será cero, luego salen 7 ceros en esta primera iteración del bucle \nexterior. En cada una de las cuatro restantes iteraciones del bucle exterior,  aparecerá\n·0· cuando ·j· es cero, lo que ocurre una sola vez por cada iteración exterior, lo cual \nnos da cuatro ceros más.',1,2,105),(342,'El código anterior contiene un error. Señala en qué línea aparece, y describe en qué consiste, y qué modificación deberías hacer (y en qué líneas) para arreglarlo.','Linea 8, se usa una variable ·j· sin haberla declarado.\nModificaría la línea 5 para dejarla así: ·int i,j;·','',1,1,110),(343,'Una vez resuelto el error, se compila y se ejecuta el código\n¿cuántas líneas imprimirá el programa antes de terminar?','100 líneas (10 iteraciones de i por 10 de j)','',1,2,110),(344,'¿Y cuántas veces aparecerá el número 15 en la salida del programa?','4 veces.','',1,3,110),(345,'¿Qué falta en el hueco de la línea~\\ref{leer:array:inicializacion}?','·contador=0·','',1,1,111),(346,'¿Qué debería ir en la línea~\\ref{leer:array:if:nota10}?','·if (nota > 10.0)·','',1,2,111),(347,'Completa la asignación del array en la línea~\\ref{leer:array:asignar:notas}','·notas[contador]=nota;·','',1,3,111),(348,'¿Qué debería ir en la línea~\\ref{leer:array:incrementar:contador}?','·contador=contador+1;· o ·contador++;·','',1,4,111),(349,'Completa la condición del bucle ·do while·, en la línea~\\ref{leer:array:condicion:while}','·while (nota>0 && contador < 1000);·','',1,5,111),(350,'Completa el ·return· de la línea~\\ref{leer:array:return:contador}','·return contador;·','',1,6,111),(351,'A continuación se muestra un caso en el que se llama a la función anterior\ndesde otra (en este caso desde la función ·main()·). De nuevo el código\ncontiene huecos tapados para que respondas más adelante.\n\n\\begin{listadoC}\nint main()\n{\n  int i;\n  float n[1000];\n  int cuantas;\n\n  ·falta«cuantas   » = pide_notas(·falta«  n »);·label«leer:array:llamada»\n  // Imprimir las notas que se han leido\n  for (i=0; i<cuantas; i++)\n    cout << \"Nota \" << i+1 << \"=\" << n[i] << endl;\n\n  // Calcular la nota media\n  cout << \"La nota media del curso es \";\n  cout << nota_media(n, cuantas) << endl;\n}\n\\end{listadoC}\n\n  Completa la línea~\\ref{leer:array:llamada}','·cuantas = pide_notas( n );·','',1,7,111),(352,'Escribe el código de la función ·nota_media· que es llamada en la última\nlínea de ·main()·. Debes escribir la función completa, con su cabecera,\nparámetros, variables locales (si las requiere) y algoritmo que calcule\nel promedio de las notas recibidas. La función retorna (en un ·float·)\nel promedio calculado. Puedes asumir que el segundo parámetro es siempre\nmayor de cero, para simplificar el código.','\\begin{Verbatim}[fontsize=\\small]\nfloat nota_media(float n[], int c)\n{\n   float suma=0.0;\n   int i;\n   for (i=0; i<c; i++)\n     suma+=n[i];\n   return (suma/c);\n}\n\\end{Verbatim}','',1,8,111);
/*!40000 ALTER TABLE `pregunta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problema`
--

DROP TABLE IF EXISTS `problema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problema` (
  `id_problema` int(11) NOT NULL AUTO_INCREMENT,
  `enunciado_general` text,
  `resumen` text NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_problema`),
  UNIQUE KEY `id_padre` (`id_padre`),
  CONSTRAINT `problema_ibfk_1` FOREIGN KEY (`id_padre`) REFERENCES `problema` (`id_problema`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problema`
--

LOCK TABLES `problema` WRITE;
/*!40000 ALTER TABLE `problema` DISABLE KEYS */;
INSERT INTO `problema` VALUES (105,'Observa el siguiente programa:\n\n\\begin{listadoC}\n#include<iostream>\nusing namespace std;\nint main()\n{\n  int i,j;\n\n  for(i=0;i<5;i++)\n    for (j=0; j<7; j++)\n      cout << i*j << endl;\n  return 0;\n}\n\\end{listadoC}','Razonar sobre ejecución de bucles',NULL),(109,'','Compilador C',NULL),(110,'Observa el siguiente programa:\n\n\\begin{listadoC}\n#include<iostream>\nusing namespace std;\nint main()\n{\n  int i;\n  for(i=0;i<10;i++)\n    for (j=0; j<10; j++)\n       cout << i+j << endl;\nreturn 0;\n}\n\\end{listadoC}','Razonar sobre ejecución de bucles (variante)',105),(111,'Como parte de un programa que maneja las notas de un conjunto de alumnos,\nse necesita una función que pida al usuario una serie de notas y las\nintroduzca en un array (vector), que recibe como parámetro.\n\n  Se ha estimado que el máximo número de alumnos que el progama necesitará\nmanejar es de 1000, y de este modo, los vectores utilizados en el\nprograma tienen esta dimensión.\n\n  La función ·pide_notas· se ocupa de pedir las notas al usuario. Se\ndesconoce de antemano cuántas notas habrá, por lo que la función pide\nnotas mientras la nota introducida sea positiva. El usuario introducirá\nuna nota negativa para indicar que ya no hay más. La función debe\ncomprobar que la nota introducida sea menor de 10, repitiendo la pregunta\nen caso contrario. Asimismo, debe contar cuántas notas se han introducido\ny si se alcanza el límite máximo previsto (1000) no admitir más.\n\n La función retornará el número de notas introducidas, y el array con\ndichas notas.\n\n  El siguiente fragmento de código muestra la implementación de esta\nfunción, pero algunas líneas se han ocultado y se preguntará sobre ellas\nmás adelante.\n\n\\begin{listadoC}\nint pide_notas(float notas[])\n{\n    int contador·falta« =0;       »·label«leer:array:inicializacion»\n    float nota;\n\n    do\n    {\n       do {\n        cout << \"Introduzca nota (negativa termina):\";\n        cin >> nota;\n        ·falta«if (nota>10.0)       »·label«leer:array:if:nota10»\n          cout << \"Error: debe ser <= 10.0\" <<endl;\n       } while (nota>10.0);\n\n       if (nota>=0) {\n          notas[·falta«contador    »]=·falta«nota;   »·label«leer:array:asignar:notas»\n          contador·falta«=contador+1;      »·label«leer:array:incrementar:contador»\n       }\n\n    } while (nota>=0 ·falta«&& contador<1000         »);·label«leer:array:condicion:while»\n    return ·falta«contador;    »·label«leer:array:return:contador»\n}\n\\end{listadoC}','Procesar listado de notas mediante arrays',NULL),(112,'','Definir instancia BBDD',NULL),(113,'','Relacion con varias PK',NULL),(114,'Se quiere crear una base de datos para astronomía, que almacene\ninformación sobre estrellas y constelaciones. Para cada estrella\nse deben almacenar los datos de su nombre, su magnitud (un número real\nque mide su brillo aparente) y su posición en la bóveda celeste (la cual\nse guarda en dos cantidades llamadas ``Ascensión recta\'\' y\n``Declinación\'\', que trataremos como de tipo texto en este ejemplo para\nsimplificar).\n\n  Las estrellas forman constelaciones, que se identifican por un nombre, de\nmodo que cada estrella pertenece a una (y solo una) constelación, y las\nconstelaciones pueden estar formadas por varias estrellas.','Base de datos de astronomía',NULL),(115,'','Ciclo de vida de una BBDD',NULL),(116,'','Bases de datos GIS, datos vectoriales',NULL),(117,'','Convertir a hex y bin',NULL),(118,'','Codificar negativo en C-2',NULL),(119,'','Diferencia entre GB y GiB, caso concreto',NULL),(120,'La figura siguiente muestra los componentes básicos de la ``arquitectura\nVon Neumann\'\', y su interconexión.\n\n\\centerline{\\includegraphics[page=1,scale=0.8]{von-neuman}}','Componentes arquitectura Von Neumann',NULL),(121,'','Describir registro PC',NULL),(122,'','Unir con líneas nombres de OS y tipos',NULL),(123,'Se quieren averiguar los primeros 10 números primos mayores de 1000. Para\nello decidimos escribir un programa. Comenzamos por escribir una función\nque recibe un único parámetro entero y devuelve un booleano que vale ·true·\nsi el dato recibido es primo o bien ·false· si no lo es.','Funciones para detección de números primos',NULL),(124,'El siguiente código muestra una función ·main· que pide al usuario una\nserie de datos que almacena en un array, y después llama a una función para\nbuscar el máximo entre sus elementos. \\textbf{Observa que} la función\ndevuelve \\emph{el índice} del elemento que tiene el máximo, y no el valor\nen sí.\n\n\\begin{listadoC}\nint  main()\n{\n   int datos[100]; // Como maximo se admitirán 100\n   int indice_maximo;\n   int cuantos,i;\n\n   // Vamos a leer los datos\n   // Esta funcion devuelve cuántos datos ha leido\n   // y deja en el array los datos leídos\n   cuantos=LeerDatos(datos);\n\n   // Vamos a buscar el índice del máximo\n   indice_maximo=BuscarIndiceMaximo(datos,cuantos);\n\n   // Imprimimos el resultado\n   cout << \"El mayor está en la posición \";\n   cout << ·falta«indice_maximo;       »\n   cout << \" y vale \";\n   cout << ·falta«datos[indice_maximo] » << endl;\n\n   return 0;\n }\n\\end{listadoC}','Calcular la posición en que aparece el máximo de un array',NULL),(125,'','Deducir qué saldrá en un if-else anidado',NULL),(126,'','Identificar en qué tabla se añade atributo en la relación 1:n',NULL),(127,'Se quiere crear un servicio web en el que los usuarios puedan registrarse\n  para plantear preguntas y también para responder a las preguntas que\n  otros planteen. Para almacenar toda la información sobre los usuarios,\n  las preguntas y las respuestas, se utiliza una base de datos relacional.\n\n  Para cada usuario debe almacenarse su \\emph{nick} (el nombre que\n  aparecerá a modo de firma, y que debe ser único para cada usuario), su\n  nombre real, su contraseña y su dirección de correo electrónico.\n\n  Cada usuario puede plantear el número de preguntas que desee, y para cada\n  pregunta se almacenará un título y un contenido. En principio es posible\n  que aparezcan títulos y contenidos repetidos (un administrador se\n  ocuparía de eliminar estos casos, pero en el diseño de la base de datos\n  no se tienen en cuenta).\n\n  Cada usuario también puede responder a cualquiera de las preguntas\n  planteadas. Una pregunta puede recibir varias respuestas, de diferentes\n  usuarios o del mismo. Para cada respuesta se almacena simplemente el\n  texto con la respuesta.\n\n  Además de lo que se ha mencionado más arriba, cada tabla deberá\n  posiblemente almacenar otros campos que actúen como clave ajena para\n  relacionar entre sí las diferentes tablas. El alumno debe pensar qué\n  campos adicionales pueden ser necesarios.','Diseño de una BBDD para un sitio de preguntas y respuestas tipo stackoverflow',NULL);
/*!40000 ALTER TABLE `problema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problema_doc_final`
--

DROP TABLE IF EXISTS `problema_doc_final`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problema_doc_final` (
  `id_doc` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  `posicion` int(11) NOT NULL,
  `salto_columna` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_doc`,`id_problema`),
  UNIQUE KEY `id_doc` (`id_doc`,`posicion`),
  KEY `id_problema` (`id_problema`),
  CONSTRAINT `problema_doc_final_ibfk_1` FOREIGN KEY (`id_doc`) REFERENCES `doc_final` (`id_doc`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `problema_doc_final_ibfk_2` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problema_doc_final`
--

LOCK TABLES `problema_doc_final` WRITE;
/*!40000 ALTER TABLE `problema_doc_final` DISABLE KEYS */;
INSERT INTO `problema_doc_final` VALUES (305,109,1,0),(305,110,2,0),(305,111,3,0),(305,112,4,0),(305,113,5,0),(305,114,6,0),(305,115,7,0),(305,116,8,0),(306,105,7,0),(306,117,1,0),(306,118,2,0),(306,119,3,0),(306,120,4,0),(306,121,5,0),(306,122,6,0),(306,123,8,0),(306,124,9,0),(306,125,10,0),(306,126,11,0),(306,127,12,0);
/*!40000 ALTER TABLE `problema_doc_final` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problema_imagen`
--

DROP TABLE IF EXISTS `problema_imagen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problema_imagen` (
  `id_problema` int(11) NOT NULL,
  `id_imagen` int(11) NOT NULL,
  `nombre_amigable` varchar(40) NOT NULL,
  PRIMARY KEY (`id_problema`,`id_imagen`),
  UNIQUE KEY `nombre_amigable` (`nombre_amigable`),
  KEY `id_imagen` (`id_imagen`),
  CONSTRAINT `problema_imagen_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `problema_imagen_ibfk_2` FOREIGN KEY (`id_imagen`) REFERENCES `imagen` (`id_imagen`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problema_imagen`
--

LOCK TABLES `problema_imagen` WRITE;
/*!40000 ALTER TABLE `problema_imagen` DISABLE KEYS */;
/*!40000 ALTER TABLE `problema_imagen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problema_tag`
--

DROP TABLE IF EXISTS `problema_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problema_tag` (
  `id_problema` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_problema`,`id_tag`),
  KEY `id_tag` (`id_tag`),
  CONSTRAINT `problema_tag_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `problema_tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problema_tag`
--

LOCK TABLES `problema_tag` WRITE;
/*!40000 ALTER TABLE `problema_tag` DISABLE KEYS */;
INSERT INTO `problema_tag` VALUES (105,507),(105,509),(105,519),(109,507),(109,509),(110,507),(110,509),(110,519),(111,507),(111,509),(111,517),(112,507),(112,510),(113,507),(113,510),(114,507),(114,510),(115,507),(115,510),(116,507),(116,510),(117,507),(117,511),(118,507),(118,511),(119,507),(119,512),(120,507),(120,513),(121,507),(121,513),(121,514),(122,507),(122,515),(123,507),(123,509),(123,518),(124,507),(124,509),(124,517),(125,507),(125,509),(125,516),(126,507),(126,510),(127,507),(127,510);
/*!40000 ALTER TABLE `problema_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id_tag` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id_tag`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=520 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (517,'arrays'),(511,'bases'),(510,'BBDD'),(519,'bucles'),(509,'C'),(516,'condiciones'),(507,'fi'),(518,'funciones'),(512,'información'),(514,'registros'),(515,'so'),(513,'von-neumann');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-22 22:58:53
