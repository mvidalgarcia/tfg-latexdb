# **Manual de usuario y del instalador.** #

## 1. Instrucciones para instalación de la aplicación.
* En el desarrollo, pruebas y puesta en marcha de esta aplicación se ha empleado un entorno **Unix/Linux**, concretamente Ubuntu Server 12.04.4 LTS en su versión de 64 bits (se puede descargar [aquí](http://releases.ubuntu.com/precise/), bajo el nombre ``ubuntu-12.04.4-server-amd64.iso``). En cualquier caso, las instrucciones que se explicarán a continuación son válidas para la puesta en marcha de la aplicación en cualquier otra distribución de Unix/Linux actual.  
  
* Debido a que parte de la aplicación ha sido desarrollada en el lenguaje de programación **PHP**, el servidor web utilizado ha sido **Apache** y el Sistema de Gestión de Base de Datos utilizado ha sido **MySQL**, se ha optado por utilizar una solución de servidor web "empaquetado" como es XAMPP. XAMPP es multiplataforma e incluye Apache, MySQL, PHP y Pearl en un "todo en uno". Concretamente la versión utilizada ha sido la 1.8.3-3 y se puede descargar de en este [sitio](https://www.apachefriends.org/download.html) para diferentes sistemas operativos, incluido Linux. Sin embargo, se podrían instalar cada uno de los componentes por separado (PHP, Apache y MySQL) en el sistema Linux y los siguiente pasos serían igualmente válidos.

1. En primera instancia hay que crear una base de datos auxiliar llamada 'test', para ello basta con acceder a la línea de comandos de mysql e introducir:  
`mysql> create database test;`.
2. Crear un usuario de base de datos con nick `tfguser` y password `tfgpass`. Esto se puede hacer en la propia línea de comandos de mysql con:  
`mysql> grant usage on *.* to tfguser@localhost identified by 'tfgpass';`  
`mysql> grant all privileges on tfgdb.* to tfguser@localhost;`
3. Ejecutar el script `tfgscript.sql`. Una forma de hacerlo es, estando dentro de la línea de comandos de mysql, ejecutar el comando:  
`mysql> source /ruta/al/archivo/tfgscript.sql;`
4. Instalar Twig en tu carpeta de trabajo donde están todos los ficheros. Para ello hay dos opciones:  
  - Primera opción: Situándose en la carpeta de trabajo ejecutar el comando `git clone git://github.com/fabpot/Twig.git`. Para ello es necesario tener instalado git en tu máquina.  
  - Segunda opción:  
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