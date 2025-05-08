<?php
$equipos = range(1, 20); 
$totalEquipos = count($equipos);
$partidos = [];
$fechaBase = strtotime("2025-05-04");

$diasSemana = ['viernes', 'sábado', 'domingo', 'lunes'];

for ($j = 0; $j < 38; $j++) {
    shuffle($equipos); 

   
    $partidos[] = [
        'jornada' => $j + 1,
        'fecha' => date('Y-m-d', $fechaBase),
        'hora' => '20:00:00', 
        'id_equipo_local' => $equipos[0],
        'id_equipo_visitante' => $equipos[19]
    ];

    $fechaSábado = strtotime("+1 day", $fechaBase); 
    for ($i = 1; $i <= 4; $i++) {
        $partidos[] = [
            'jornada' => $j + 1,
            'fecha' => date('Y-m-d', strtotime("+$i days", $fechaSábado)),
            'hora' => '20:00:00',
            'id_equipo_local' => $equipos[$i],
            'id_equipo_visitante' => $equipos[$i + 1]
        ];
    }

    $fechaDomingo = strtotime("+2 days", $fechaBase); 
    for ($i = 5; $i <= 8; $i++) {
        $partidos[] = [
            'jornada' => $j + 1,
            'fecha' => date('Y-m-d', strtotime("+$i days", $fechaDomingo)),
            'hora' => '20:00:00',
            'id_equipo_local' => $equipos[$i],
            'id_equipo_visitante' => $equipos[$i + 1]
        ];
    }

    $fechaLunes = strtotime("+3 days", $fechaBase);
    $partidos[] = [
        'jornada' => $j + 1,
        'fecha' => date('Y-m-d', $fechaLunes),
        'hora' => '20:00:00',
        'id_equipo_local' => $equipos[18],
        'id_equipo_visitante' => $equipos[19]
    ];

    $fechaBase = strtotime("+7 days", $fechaBase);
}

$sql = "INSERT INTO partidos (fecha, hora, jornada, id_equipo_local, id_equipo_visitante) VALUES\n";
$values = [];

foreach ($partidos as $p) {
    $values[] = "('{$p['fecha']}', '{$p['hora']}', {$p['jornada']}, {$p['id_equipo_local']}, {$p['id_equipo_visitante']})";
}

$sql .= implode(",\n", $values) . ";";

file_put_contents("insert_partidos.sql", $sql);
echo "Script SQL generado con éxito.";
?>
