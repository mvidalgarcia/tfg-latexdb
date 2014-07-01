# Como asegurarse de que todo está en utf8

## La base de datos

En el script de creación añadir, la BD debe crearse como

    select 'Create DB "tfgdb" CHARACTER SET utf8 COLLATE utf8_general_ci' as 'Action';
    create database tfgdb CHARACTER SET utf8 COLLATE utf8_general_ci;

y cuando desde PHP se conecta a ella (fichero `singleton_db.php`), tras la conexión (en el constructor), hay que poner:

        $this->connection->exec("set names utf8");

## El HTML que sirve el servidor

Todos los `.html` estáticos y los `.phtml` de template deben incluir lo de `<meta charset="utf-8"/>` (esto estaba bien).

## El JSON que se envía

Con lo anterior ya debería salir correctamente sin más conversiones.
