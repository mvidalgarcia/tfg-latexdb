<html>
<head>
 <title>Ejemplo de markdown desde PHP</title>
 <meta charset="utf-8">
</head>
<body>
<?php

require_once("./Pandoc/Pandoc.php");
require_once("./Pandoc/PandocException.php");

use Pandoc\Pandoc;
use Pandoc\PandocException;

$prueba = <<<'FIN'
# Título

Este es un _ejemplo_ escrito en [markdown](http://daringfireball.net/projects/markdown/)
y convertido en HTML por [pandoc][1], directamente desde PHP.

He aquí una lista de items:

* Uno
* Dos
* Tres

Y una pequeña fórmula: $h^2 = a^2 + b^2$. 

[1]: http://johnmacfarlane.net/pandoc/
FIN;

setlocale(LC_ALL, "es_ES.UTF-8");
$pandoc = new Pandoc();
$html = $pandoc->convert($prueba, "markdown", "html");
echo ($html);
?>
</body>
