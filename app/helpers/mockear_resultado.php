<?php
$jornada_inicio = 1;  
$jornada_fin = 22;    
$totalEquipos = 20;
$partidosPorJornada = $totalEquipos / 2; 

$idPrimerPartido = 1;

$updates = [];

for ($jornada = $jornada_inicio; $jornada <= $jornada_fin; $jornada++) {
    for ($partidoEnJornada = 0; $partidoEnJornada < $partidosPorJornada; $partidoEnJornada++) {
        $idPartido = $idPrimerPartido + ($jornada - 1) * $partidosPorJornada + $partidoEnJornada;
        
        $goles_local = rand(0, 5);
        $goles_visitante = rand(0, 5);

        $updates[] = "UPDATE partidos SET goles_local = $goles_local, goles_visitante = $goles_visitante, jugado = 1 WHERE id = $idPartido;";
    }
}

$sql_script = implode("\n", $updates);
file_put_contents("update_resultados_mockeados.sql", $sql_script);

echo "Archivo SQL generado: update_resultados_mockeados.sql\n";
?>
