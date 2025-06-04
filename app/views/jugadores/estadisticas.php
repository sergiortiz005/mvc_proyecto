<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Jugador - <?= htmlspecialchars($datos['jugador']->nombre) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/estadisticas.css">
</head>

<body>
    <div class="container">
        <!-- Header del jugador -->
        <div class="player-header">
            <a href="<?= RUTA_URL ?>/jugador" class="back-link">Volver a la lista de jugadores</a>
            
            <h1 class="player-name"><?= htmlspecialchars($datos['jugador']->nombre) ?></h1>
            
            <div class="player-info">
                <div class="player-info-item">
                    <span class="player-info-label">Dorsal</span>
                    <span class="player-info-value">#<?= $datos['jugador']->dorsal ?></span>
                </div>
                <div class="player-info-item">
                    <span class="player-info-label">Posición</span>
                    <span class="player-info-value"><?= htmlspecialchars($datos['jugador']->posicion) ?></span>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <!-- Sección del equipo -->
            <div class="team-section">
                <h2 class="section-title">Equipo Actual</h2>
                <div class="team-info">
                    <div class="team-details">
                        <div class="team-name"><?= htmlspecialchars($datos['equipo']->nombre) ?></div>
                        <div class="team-city"><?= htmlspecialchars($datos['equipo']->ciudad) ?></div>
                    </div>
                    <img src="<?= RUTA_URL . '/public/img/escudos/' . $datos['equipo']->escudo ?>" 
                         alt="Escudo del equipo <?= htmlspecialchars($datos['equipo']->nombre) ?>" 
                         class="team-logo">
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="stats-section">
                <h2 class="section-title">Estadísticas por Partido</h2>
                <?php if (empty($datos['estadisticas'])): ?>
                    <div class="no-stats">
                        No hay estadísticas disponibles para este jugador.
                    </div>
                <?php else: ?>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Rival</th>
                                <th>Local/Visitante</th>
                                <th>Resultado</th>
                                <th>Goles</th>
                                <th>Asistencias</th>
                                <th>Tarjetas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['estadisticas'] as $stat): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($stat->fecha)) ?></td>
                                    <td>
                                        <?php 
                                            // Rival es el equipo contrario al del jugador:
                                            if ($stat->local_visitante === 'Local') {
                                                echo htmlspecialchars($stat->equipo_visitante);
                                            } else {
                                                echo htmlspecialchars($stat->equipo_local);
                                            }
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($stat->local_visitante) ?></td>
                                    <td>
                                        <span class="result">
                                            <?= htmlspecialchars($stat->goles_local) ?> - <?= htmlspecialchars($stat->goles_visitante) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ((int)$stat->goles > 0): ?>
                                            <span class="stat-badge goals"><?= (int)$stat->goles ?></span>
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ((int)$stat->asistencias > 0): ?>
                                            <span class="stat-badge assists"><?= (int)$stat->asistencias ?></span>
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $tarjetas = [];
                                            if ($stat->tarjetas_amarillas > 0) {
                                                $tarjetas[] = $stat->tarjetas_amarillas . ' amarilla' . ($stat->tarjetas_amarillas > 1 ? 's' : '');
                                            }
                                            if ($stat->tarjetas_rojas > 0) {
                                                $tarjetas[] = $stat->tarjetas_rojas . ' roja' . ($stat->tarjetas_rojas > 1 ? 's' : '');
                                            }
                                            echo $tarjetas ? implode(', ', $tarjetas) : 'Ninguna';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 Liga Deportiva</p>
    </footer>
</body>

</html>