# **Manual de usuario y del instalador.** #

## 1. Instrucciones para instalación de la aplicación.
* En el desarrollo, pruebas y puesta en marcha de esta aplicación se ha empleado un entorno **Unix/Linux**, concretamente Ubuntu Server 12.04.4 LTS en su versión de 64 bits (se puede descargar [aquí](http://releases.ubuntu.com/precise/), bajo el nombre ``ubuntu-12.04.4-server-amd64.iso``). En cualquier caso, las instrucciones que se explicarán a continuación son válidas para la puesta en marcha de la aplicación en cualquier otra distribución de Unix/Linux actual.  
  
* Debido a que parte de la aplicación ha sido desarrollada en el lenguaje de programación **PHP**, el servidor web utilizado ha sido **Apache** y el Sistema de Gestión de Base de Datos utilizado ha sido **MySQL**, se ha optado por utilizar una solución de servidor web "empaquetado" como es XAMPP. XAMPP es multiplataforma e incluye Apache, MySQL, PHP y Pearl en un "todo en uno". Concretamente la versión utilizada ha sido la 1.8.3-3 y se puede descargar de en este [sitio](https://www.apachefriends.org/download.html) para diferentes sistemas operativos, incluido Linux. Sin embargo, se podrían instalar cada uno de los componentes por separado (PHP, Apache y MySQL) en el sistema Linux y los siguiente pasos serían igualmente válidos.  
  
Una vez tenemos nuestro equipo con la distribución de Linux elegida con PHP, Apache y MySQL instalados en ella hay que realizar los siguiente pasos:
  
1. Copiar el directorio `tfg` donde está la aplicación en el directorio `htdocs` del servidor Apache. Si has realizado la instalación de XAMPP la ruta por defecto es `/opt/lampp/htdocs`.
2. Poner en marcha el servidor web Apache y MySQL en el equipo, así como tener activado PHP. Con XAMPP simplemente con el comando `sudo /opt/lampp/xampp start` se pone todo en marcha.
3. Entrar en la línea de comandos de MySQL. Con XAMPP realizando el comando `sudo /opt/lampp/bin/mysql -u root` aparecerá el prompt de MySQL `mysql>`.
4. Crear una base de datos auxiliar llamada `test`, para ello una vez en la línea de comandos de mysql introducir `mysql> create database test;`.
5. Crear un usuario de base de datos con nick `tfguser` y password `tfgpass`. Esto se puede hacer en la propia línea de comandos de mysql con:  
`mysql> grant usage on *.* to tfguser@localhost identified by 'tfgpass';`  
`mysql> grant all privileges on tfgdb.* to tfguser@localhost;`
6. Ejecutar el script `tfgscript.sql` contenido en la raíz del directorio `tfg` de la aplicación. Para hacerlo, estando dentro de la línea de comandos de mysql, ejecutar el comando `mysql> source /ruta/al/archivo/tfgscript.sql;`. Salir de MySQL con `mysql> quit`.
7. Instalar Twig en el directorio `tfg` donde están todos los ficheros. Para ello hay dos opciones:  
  - Primera opción: Situándose en el directorio `tfg` ejecutar el comando `git clone git://github.com/fabpot/Twig.git`. Para ello es necesario tener instalado Git en tu máquina.  
  - Segunda opción:  
a. Instalar composer:  
	```
	curl -s http://getcomposer.org/installer | php
	```  
(inicialmente no deja, hay que añadir una línea a `/etc/php5/cli/php.ini`) Esto crea un archivo llamado `composer.phar`, en la misma carpeta en la que estoy.  
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
8. Comprobar la dirección IP de tu máquina (se puede hacer con el comando `ifconfig`) y en un navegador web ir a la dirección `http://mi-ip/tfg`. Si por ejemplo la dirección IP de tu equipo es la `192.168.1.10`, escribir en la barra de direcciones del navegador la dirección `http://192.168.1.19/tfg`. En este momento se debería abrir la página principal de la aplicación web.