<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piedra Papel Tijera</title>
</head>
<body>
    <h1>Piedra, Papel, Tijera!</h1>
    <h4>Actualice la página para mostrar otra partida</h4>
    <?php
        // Eligen que sacar
        $j1 = random_int(1,3);
        $j2 = random_int(1,3);
        $piedra = 1;
        $papel = 2;
        $tijera = 3;
        // Emoticonos de piedra, papel y tijera
        define ('PIEDRA1',  "&#x1F91C;");
        define ('PIEDRA2',  "&#x1F91B;");
        define ('TIJERAS',  "&#x1F596;");
        define ('PAPEL',    "&#x1F91A;");

        echo"<h3>Jugador 1 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Jugador 2</h3>";
        echo "<div style='font-size: 7em'>";
        // Emoticono del J1
        switch ($j1) {
            case $piedra:
                echo PIEDRA1, "&nbsp";
                break;
            case $papel:
                echo PAPEL, "&nbsp";
                break;
            case $tijera:
                echo TIJERAS, "&nbsp";
                break;
        }
        // Emoticono del J2
        switch ($j2) {
            case $piedra:
                echo PIEDRA2;
                break;
            case $papel:
                echo PAPEL;
                break;
            case $tijera:
                echo TIJERAS;
                break;
        }
        echo "</div>";
        // Elige quien ha ganado
        if ($j1 == $piedra ) {
            if ($j2 == $tijera) {
                echo "<h3>Gana Jugador 1</h3>";
            } elseif ($j2 == $papel) {
                echo "<h3>Gana Jugador 2</h3>";
            } else {
                echo "<h3>Empate</h3>";
            }
        }elseif ($j1 == $papel) {
            if ($j2 == $tijera) {
                echo "<h3>Gana Jugador 2</h3>";
            } elseif ($j2 == $papel) {
                echo "<h3>Empate</h3>";
            } else {
                echo "<h3>Gana Jugador 1</h3>";
            }
        }elseif ($j1 == $tijera) {
            if ($j2 == $tijera) {
                echo "<h3>Empate</h3>";
            } elseif ($j2 == $papel) {
                echo "<h3>Gana Jugador 1</h3>";
            } else {
                echo "<h3>Gana Jugador 2</h3>";
            }
        }
    ?>
</body>
</html>