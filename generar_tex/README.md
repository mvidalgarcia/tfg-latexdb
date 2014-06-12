Sobre la generación del documento final LaTeX
=============================================

## Contenidos a generar

El script PHP debe generar los siguientes ficheros en una carpeta temporal,
para después comprimir esa carpeta y enviar el `.zip` al cliente:

* `examen.tex`: Es el documento "maestro" que incluye a los demás. Se genera
  según la plantilla `template-examen.tex`. He incluido a modo de "demo"
  un script `generar-examen.php` que puedes probar a ejecutar desde línea 
  de comandos, para que veas cómo es lo que sale. Puedes ejecutarlo así:

    ```
    $ php generar-examen.php > examen-ejemplo.tex
    ```

* El fichero `examen.sty` que he dejado en esta carpeta. Este fichero es
  incluido desde el "maestro" y es el que le da formato a todo el examen,
  oculta las soluciones o no, etc... Es el que yo uso habitualmente en el sistema
  actual. Quizás tenga que cambiarle alguna cosa dependiendo de si tenemos que
  cambiar el formato en que vienen las preguntas, respuestas, puntuaciones, etc.
* Un fichero `.tex` para cada problema del examen. Se generan según las
  plantillas `template-problema.tex` (si el problema tiene un enunciado general
  seguido de una o varias preguntas) o `template-preguntasola.tex` (si el
  problema no tiene enunciado general y después va seguido de una única
  pregunta). Actualmente este es el formato en que espera las cosas mi estilo
  `examen.sty`. Podemos cambiarlo si lo vemos necesario a algo más coherente.
* Un fichero para cada imagen `png` o `jpg` que use el examen. El nombre de
  estos ficheros también debería ser único y significativo, por lo que se me
  ocurre que podría ser la concatenación del `nombre_amigable` elegido por el
  usuario y el `id_imagen` que tenga en la base de datos. Así, uno de estos
  ficheros podría llamarse `captura-pantalla-0025.png`.

## Sobre los nombres de fichero

* El documento principal o maestro no importa cómo se llame. Podemos llamarlo
  siempre `examen.tex`, aunque quizás sería mejor para el usuario darle un nombre
  que le ayude a identificar el contenido, como por ejemplo `Sistemas
  Distribuidos-15-02-2014.tex`.
* Cada fichero de cada problema. El nombre de este fichero `.tex` tiene que
  generarse de modo que no aparezcan dos con el mismo nombre. Una posibilidad es
  usar como nombre de fichero el `id_problema` que use la base de datos, pero
  esto no es muy significativo para el usuario final. El nombre del fichero
  debería dar alguna pista de su contenido. Se me ocurre por ejemplo usar los
  tags del problema, concatenado con el número de preguntas que contiene
  y concatenado con el `id_problema`. Así, por ejemplo, el fichero podría
  llamarse `sockets-concurrencia-05-0014.tex` lo que sirve al usuario para saber
  de un golpe de vista de qué problema se trata (o al menos de qué trata el
  problema y cuántas preguntas tiene). El meter el `id` al final es para evitar
  que salgan nombres de fichero duplicados, si hay dos problemas con los mismos
  tags y número de preguntas.
* Los nombres de los ficheros de imágenes. Ya quedó dicho que debe ser
  `nombre-amigable-id_imagen.{png,jpg}`. Aquí aparece un pequeño problema de
  usabilidad. Cuando el usuario escribe su pregunta usando el interfaz web, usa
  código latex para incluir la figura, tal como
  `\includegraphics{nombre_fichero}` (latex no necesita que se especifique la
  extensión del fichero). Ese nombre que ponga ahí debería ser el nombre
  amigable, de modo que pueda poner: `\includegraphics{captura-pantalla}`, por
  ejemplo. Sin embargo, una vez se ha generado el .tex que el usuario descarga
  a su ordenador, cuando esto vaya a ser compilado localmente por pdflatex, esa
  línea debería haber cambiado a `\includegraphics{captura-pantalla-0025}`
  (siendo por ejemplo `25` el `id_imagen` para esa imagen), puesto que así será
  como se llamará realmente el fichero `.png` que el usuario tendrá en local (que
  le ha sido enviado por el servidor en el `.zip`).

    Por tanto, el servidor debe tener un poco más de "inteligencia" para
  manejar este caso. No debe limitarse a sacar texto de la base de datos sin más,
  sino que debe "preprocesarlo", buscando si se usa `\includegraphics` para en
  ese caso reescribir el argumento.

    Fíjate que, ya que `nombreamigable` es un atributo de la relación entre
  problemas e imágenes, el usuario puede usar el mismo nombre amigable en
  diferentes problemas sin que eso cause conflictos. Puedo tener un problema en
  el que uso `\includegraphics{captura-pantalla}` y otro problema distinto en que
  uso el mismo `\includegraphics`, pero cada uno se estaría refiriendo a una
  imagen diferente. Lo que no se puede permitir es el mismo nombre amigable
  repetido dentro de un mismo problema. Esto puede complicar un poco la interfaz
  o validación de subir imágenes y darles nombres.

* El nombre del `zip` final. Podría ser el mismo que el del documento maestro
  que contiene. Quizás se le podría añadir al nombre de fichero un "timestamp"

## Qué hará el usuario con el .zip

* Primero descomprimirlo. Le saldrá una carpeta de trabajo temporal

* Entrar en ella y hacer `pdflatex documento-maestro`

* Si todo compila sin problemas, tendrá el `.pdf` buscado. Si tiene errores,
  éstos pueden estar:

    - En el `examen.sty` (no lo creo, está bien testado desde hace años, pero
      en todo caso me correspondería a mí corregirlos)
    - En el `documento-maestro.tex`. Esto seria un error del script PHP que
      generó mal algo. O bien está mal el template (lo corregiría yo), o bien el
      script generó incorrectamente nombres de fichero, etc. También puede deberse
      a que el usuario metió código LaTeX no válido en alguna de las variables del
      documento (ej: en las instrucciones). Por nuestra parte tenemos que asegurar
      que la plantilla general es válida. Si el usuario después mete errores en sus
      "input", será él quien deba resolverlos
    - En cualquiera de las preguntas. De nuevo esto puede deberse a fallos en
      el template de la pregunta (tendríamos que corregirlos, probablemente yo), o en
      el script (ej. que no haga bien el postproceso de los `\includegraphics`), o en
      los propios enunciados y soluciones que metió el usuario. Nuestra
      responsabilidad es que la parte de templates y scripts esté correcta. Los
      errores que introduzca el usuario deberá resolverlos él.

* Si el usuario ha encontrado errores al compilar, y si nosotros hicimos bien
  nuestro trabajo, estos errores se deberán a que metió código no válido en los
  enunciados, soluciones, etc... El usuario entonces entrará otra vez en la
  aplicación web a corregir lo que estaba mal, y generará un nuevo `.zip`, que
  descomprimirá, recompilará, etc

* Si todo ha compilado bien, tendrá un examen listo para imprimir, que será del
  "Tipo A"

* El profesor seguramente querrá generar otros tipos de examen, con las mismas
  preguntas pero en diferente orden. Para ello, de momento y ya que no
  contemplamos esto en las especificaciones, deberá editar el
  `documento-maestro.tex`, cambiar donde pone `\tipo{A}` y poner `\tipo{B}`,
  "barajar" el orden en que aparecen los `\input` de las preguntas, y compilar de
  nuevo. Y así para tantos tipos como quiera.


    Una posible ampliación (bastante simple), sería que estos tipos "barajados"
  los genere el servidor también.


