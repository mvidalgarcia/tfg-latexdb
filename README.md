## Instrucciones para la puesta en marcha de la aplicación.
Se ha empleado un entorno Linux (Ubuntu Server) para el desarrollo de esta aplicación, con la distribución XAMPP versión 1.8.3-3

1. En primera instancia hay que crear una base de datos auxiliar llamada 'test', para ello basta con acceder a la línea de comandos de mysql e introducir:  
`mysql> create database test;`.
2. Crear un usuario de base de datos con nick `tfguser` y password `tfgpass`. Esto se puede hacer en la propia línea de comandos de mysql con:  
`mysql> grant usage on *.* to tfguser@localhost identified by 'tfgpass';`  
`mysql> grant all privileges on tfgdb.* to tfguser@localhost;`
3. Ejecutar el script `tfgscript.sql`. Una forma de hacerlo es, estando dentro de la línea de comandos de mysql, ejecutar el comando:  
`mysql> source /ruta/al/archivo/tfgscript.sql;`
4. Instalar Twig en tu carpeta de trabajo donde están todos los ficheros. Para ello hay dos opciones:
 1.  Primera opción: Situándose en la carpeta de trabajo ejecutar el comando `git clone git://github.com/fabpot/Twig.git`. Para ello es necesario tener instalado git en tu máquina.
 2.  Segunda opción:  
a. Instalar composer:  
	```
	curl -s http://getcomposer.org/installer | php
	```  
(inicialmente no me deja, tengo que añadir una línea a `/etc/php5/cli/php.ini`) Esto crea un archivo llamado `composer.phar`, en la misma carpeta en la que estoy.  
b. Crear un archivo llamado `composer.json` que contenga:  
```
	{
	    "require": {
		"twig/twig": "1.*"
	    }
	}
	```  
c. Ejecutar el comando `php composer.phar install`  
d. Mover el contenido de la carpeta `vendor/twig/twig` a `Twig`

