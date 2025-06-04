<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($datos['equipo']->nombre) ?> - Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/equipo_detalle.css">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
</head>

<body>
    
    <header>
        <img src="<?= RUTA_URL . "/public/img/escudos/" . rawurlencode($datos['equipo']->nombre) . ".png" ?>"
            alt="Escudo de <?= htmlspecialchars($datos['equipo']->nombre) ?>" />
        <div class="info">
            <h1><?= htmlspecialchars($datos['equipo']->nombre) ?></h1>
            <p><?= htmlspecialchars($datos['equipo']->ciudad) ?></p>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <nav class="tabs" id="tabs">
                <button class="active" data-tab="resultados">Resultados</button>
                <button data-tab="plantilla">Plantilla</button>
                <button data-tab="clasificacion">Clasificación</button>
            </nav>

            <div class="tab-content">
                <!-- Resultados -->
                <section id="resultados" class="active">
                    <h2>Últimos partidos</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Jornada</th>
                                <th>Rival</th>
                                <th>Resultado</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['resultados'] as $partido): ?>
                                <?php
                                $esLocal = $partido->id_equipo_local == $datos['equipo']->id;
                                $nombreRival = $esLocal ? $partido->nombre_visitante : $partido->nombre_local;
                                $golesEquipo = $esLocal ? $partido->goles_local : $partido->goles_visitante;
                                $golesRival = $esLocal ? $partido->goles_visitante : $partido->goles_local;

                                if ($golesEquipo > $golesRival) {
                                    $resultadoLetra = 'Victoria';
                                    $claseResultado = 'resultado-g';
                                } elseif ($golesEquipo < $golesRival) {
                                    $resultadoLetra = 'Derrota';
                                    $claseResultado = 'resultado-p';
                                } else {
                                    $resultadoLetra = 'Empate';
                                    $claseResultado = 'resultado-e';
                                }
                                ?>
                                <tr>
                                    <td><?= $partido->jornada ?></td>
                                    <td>
                                        <div class="team-info">
                                            <span>vs <?= htmlspecialchars($nombreRival) ?></span>
                                            <img src="<?= RUTA_URL . "/public/img/escudos/" . rawurlencode($nombreRival) . ".png" ?>"
                                                alt="Escudo de <?= htmlspecialchars($nombreRival) ?>">
                                        </div>
                                    </td>
                                    <td><strong><?= $golesEquipo ?> - <?= $golesRival ?></strong></td>
                                    <td><span class="<?= $claseResultado ?>"><?= $resultadoLetra ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <!-- Plantilla -->
                <section id="plantilla">
                    <h2>Plantilla</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Dorsal</th>
                                <th>Jugador</th>
                                <th>Posición</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['jugadores'] as $jugador): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($jugador->dorsal) ?></strong></td>
                                    <td><?= htmlspecialchars($jugador->nombre) ?></td>
                                    <td><span style="background: #e5e7eb; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 500;"><?= htmlspecialchars($jugador->posicion) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <!-- Clasificación -->
                <section id="clasificacion">
                    <h2>Clasificación</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Pos</th>
                                <th>Equipo</th>
                                <th>PJ</th>
                                <th>PG</th>
                                <th>PE</th>
                                <th>PP</th>
                                <th>GF</th>
                                <th>GC</th>
                                <th>DG</th>
                                <th>Pts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $pos = 1; ?>
                            <?php foreach ($datos['clasificacion'] as $fila): ?>
                                <tr>
                                    <td><strong><?= $pos++ ?></strong></td>
                                    <td><?= htmlspecialchars($fila['nombre'] ?? 'Equipo ' . ($fila['equipo_id'] ?? '-')) ?></td>
                                    <td><?= $fila['pj'] ?? '-' ?></td>
                                    <td><?= $fila['pg'] ?? '-' ?></td>
                                    <td><?= $fila['pe'] ?? '-' ?></td>
                                    <td><?= $fila['pp'] ?? '-' ?></td>
                                    <td><?= $fila['gf'] ?? '-' ?></td>
                                    <td><?= $fila['gc'] ?? '-' ?></td>
                                    <td><?= $fila['dg'] ?? '-' ?></td>
                                    <td><strong><?= $fila['puntos'] ?? '-' ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>

        <div class="sidebar">
            <div class="calendar-container">
                <h2>Calendario</h2>
                <div id="calendario"></div>
            </div>
            
            <div class="next-matches">
                <h3>Próximos Partidos</h3>
                <?php 
                // Mostrar próximos 3 partidos del calendario
                $proximosPartidos = array_slice($datos['calendario'], 0, 3);
                foreach ($proximosPartidos as $partido): 
                    $esLocal = $partido->id_equipo_local == $datos['equipo']->id;
                    $rival = $esLocal ? $partido->nombre_visitante : $partido->nombre_local;
                    $fecha = date('d/m', strtotime($partido->fecha));
                    $jornada = $partido->jornada ?? '';
                ?>
                    <div class="match-item">
                        <div>
                            <div class="match-opponent">J<?= $jornada ?> vs <?= htmlspecialchars($rival) ?></div>
                            <div class="match-date"><?= $fecha ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Tabs functionality
        const tabs = document.querySelectorAll('nav.tabs button');
        const sections = document.querySelectorAll('section');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                sections.forEach(s => {
                    if (s.id === tab.getAttribute('data-tab')) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
                tab.classList.add('active');
            });
        });

        // Calendar
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendario');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                dayMaxEvents: 2,
                events: [
                    <?php foreach ($datos['calendario'] as $partido):
                        $esLocal = $partido->id_equipo_local == $datos['equipo']->id;
                        $rival = $esLocal ? $partido->nombre_visitante : $partido->nombre_local;
                        $fecha = $partido->fecha;
                        $jornada = $partido->jornada ?? '';
                        $escudo = RUTA_URL . "/public/img/escudos/" . rawurlencode($rival) . ".png";
                        ?>
                        {
                            title: "J<?= $jornada ?> vs <?= htmlspecialchars($rival) ?>",
                            start: "<?= $fecha ?>",
                            display: 'block',
                            extendedProps: {
                                escudo: "<?= $escudo ?>",
                                rival: "<?= htmlspecialchars($rival) ?>",
                                jornada: "<?= $jornada ?>",
                                esLocal: <?= $esLocal ? 'true' : 'false' ?>
                            }
                        },
                    <?php endforeach; ?>
                ],
                eventDidMount: function (info) {
                    const escudo = info.event.extendedProps.escudo;
                    const rival = info.event.extendedProps.rival;
                    const jornada = info.event.extendedProps.jornada;
                    
                    // Limpiar el contenido del evento
                    info.el.innerHTML = '';
                    
                    if (escudo) {
                        const img = document.createElement('img');
                        img.src = escudo;
                        img.alt = rival;
                        img.title = `Jornada ${jornada} vs ${rival}`;
                        info.el.appendChild(img);
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>