Para poder poner a funcionar el software, he encontrado varios problemas.

# Creación de la base de datos inicial

Es necesario crear una base de datos llamada `test`. Una vez creada, al ejecutar el script `tfgscript.sql` da un error al crear el campo `id_pregunta`. Probé entonces a ejecutar ese script desde `sqlbuddy` (un interfaz web para acceder a la base de datos) y parecía que no hacía nada, pero tras reiniciar el navegador la bbdd estaba creada. No sé si bien...

# Instalación de Twig

Aunque el repositorio tiene una carpeta Twig, está vacía.

Para instalar Twig he tenido que hacer lo siguiente (como root):

1. Instalar composer:

	```
	curl -s http://getcomposer.org/installer | php
	```

    (inicialmente no me deja, tengo que añadir una línea a `/etc/php5/cli/php.ini`) Esto crea un archivo llamado `composer.phar`, en la misma carpeta en la que estoy.

2. Crear un archivo llamado `composer.json` que contenga:

	```
	{
	    "require": {
		"twig/twig": "1.*"
	    }
	}
	```

3. Ejecutar el comando `php composer.phar install`

4. Mover el contenido de la carpeta `vendor/twig/twig` a `Twig`

# Permisos en la base de datos

Parece ser que los scripts php intentan conectar con la bbdd bajo el usuario `tfguser`, el cual no existe. Lo creo mediante sqlbuddy y le doy permisos para la bbdd `tfgdb`. Le pongo como contraseña la que averiguo al leer el fichero `singleton_db.php`.

Con lo anterior parece que ya va tirando...



