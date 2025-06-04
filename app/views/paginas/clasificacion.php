<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clasificación de la Liga</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/clasificacion.css">
</head>

<body>
    <?php require RUTA_APP . '/views/inc/header.php'; ?>
    
    <div class="container">
        <!-- Header de la página -->
        <div class="page-header">
            <h1 class="page-title">Clasificación de la Liga</h1>
            <p class="page-subtitle">Tabla actualizada con todas las estadísticas</p>
        </div>

        <!-- Tabla de clasificación -->
        <table class="classification-table">
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
                <?php if (!empty($datos['clasificacion'])): ?>
                    <?php $pos = 1; ?>
                    <?php foreach ($datos['clasificacion'] as $equipo): ?>
                        <tr class="<?= $pos <= 3 ? 'position-' . $pos : '' ?>">
                            <td>
                                <?php if ($pos <= 3): ?>
                                    <div class="position-medal medal-<?= $pos ?>">
                                        <?= $pos ?>
                                    </div>
                                <?php else: ?>
                                    <?= $pos ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo RUTA_URL; ?>/equipos/ver/<?= $equipo['equipo_id'] ?>" 
                                   class="team-link">
                                    <?= htmlspecialchars($equipo['nombre']) ?>
                                </a>
                            </td>
                            <td><?= $equipo['pj'] ?></td>
                            <td>
                                <?php if ($equipo['pg'] > 0): ?>
                                    <span class="stat-wins"><?= $equipo['pg'] ?></span>
                                <?php else: ?>
                                    <?= $equipo['pg'] ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $equipo['pe'] ?></td>
                            <td><?= $equipo['pp'] ?></td>
                            <td class="stat-goals-favor"><?= $equipo['gf'] ?></td>
                            <td class="stat-goals-against"><?= $equipo['gc'] ?></td>
                            <td>
                                <?php 
                                    $diff = $equipo['dg'];
                                    $diffClass = $diff > 0 ? 'stat-diff-positive' : ($diff < 0 ? 'stat-diff-negative' : '');
                                    $diffSymbol = $diff > 0 ? '+' : '';
                                ?>
                                <span class="<?= $diffClass ?>">
                                    <?= $diffSymbol . $diff ?>
                                </span>
                            </td>
                            <td>
                                <span class="stat-points"><?= $equipo['puntos'] ?></span>
                            </td>
                        </tr>
                        <?php $pos++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="no-data">
                            No hay datos para mostrar en la clasificación.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>