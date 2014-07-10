# Software necesario

Este leeme detalla cómo hacerlo en Linux (en Ubuntu Server). En Windows habría que investigarlo.

## Pandoc

Se podría instalar con `apt-get install pandoc`, pero esto no instalaría la última versión, por lo que he preferido hacerlo por la vía más compleja pero que simplifica el conseguir posteriores actualizaciones.

1. Instalar `cabal` mediante `apt-get install cabal`. Esto instalará también `haskell-platform`. De este modo tendremos el "runtime" que necesita `pandoc`, que está escrito en _haskell_.
2. Usando `cabal`, que es una especie de "instalador" para programas escritos en _haskell_, instalamos `pandoc` con los comandos:

    ```
    $ sudo cabal update
    $ sudo cabal install --global pandoc
    ```

    Es importante hacerlos como `sudo` para que la instalación sea global y por tanto accesible a apache. De otro modo, sólo el usuario que haga la instalación podría usar `pandoc`. Tarda un montón porque se baja y compila muchas cosas.

3. Comprobemos que funciona:

```
$ pandoc --version
pandoc 1.12.3.3
[etc]
$ pandoc README-pandoc.txt -o README-pandoc.pdf
```
El segundo comando toma este mismo documento y genera un pdf usando como lenguaje intermedio LaTeX, por lo que requiere tener instalado TeXlive. Alternativamente se le puede pedir que genere una versión html y verla en el navegador.


## pandoc-php

Todo lo que sigue ya lo hice yo, y finalmente añadí la carpeta `Pandoc` al repositorio git, por lo que al clonar este repositorio y pasar a esta rama ya tendrás instalado `pandoc-php`. No obstante, por referencia, estos fueron los pasos necesarios:


Expandiendo las instrucciones [en su página](https://github.com/ryakad/pandoc-php) lo que se hace es:

1. Ponerse en la carpeta donde está la aplicación web (su `index.html`)
2. Instalar composer con `curl -s http://getcomposer.org/installer | php` lo que crea un arhivo `composer.phar` en la carpeta actual.
3. Escribir un archivo `composer.json` que contenga:

    ```json
    {
        "require": {
            "ryakad/pandoc-php": "dev-master"
        
         }
    }
    ```
4. Ejecutar el comando `./composer.phar install`. Esto crea una carpeta `vendor` en la que deja el paquete seleccionado.
5. Opcionalmente, `mv vendor/ryakad/pandoc-php/src/Pandoc .` para dejarlo más accesible.


# Probar si funciona

He incluido un fichero llamado `pandoc-test.php` que declara una cadena de caracteres conteniendo markdown y usa pandoc para convertirla en HTML tras lo cual la imprime con `echo`. Conectando un navegador a este script, debería verse el resultado ya formateado.
