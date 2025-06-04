<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Jugadores</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/tabla_jugadores.css">
</head>

<body>
    <div class="container">
        <?php require RUTA_APP . '/views/inc/header.php'; ?>

        <h2>Listado de Jugadores</h2>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <a href="<?php echo RUTA_URL; ?>/jugador/nuevo" class="btn-nuevo">Añadir Jugador</a>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dorsal</th>
                    <th>Posición</th>
                    <th>Equipo</th>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos['jugadores'] as $jugador): ?>
                    <tr>
                        <td>
                            <a href="<?= RUTA_URL . "/jugador/ver/" . $jugador->id ?>">
                                <?= $jugador->nombre; ?>
                            </a>
                        </td>
                        <td><?= $jugador->dorsal; ?></td>
                        <td><?= $jugador->posicion; ?></td>
                        <td>
                            <img src="<?= RUTA_URL . '/public/img/escudos/' . $jugador->escudo; ?>" alt="Escudo"
                                style="height:25px; vertical-align:middle; margin-right:6px;">
                        </td>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <td>
                                <a href="<?= RUTA_URL . "/jugador/editar/" . $jugador->id ?>" class="btn editar">Editar</a>
                                <a href="<?= RUTA_URL . "/jugador/borrar/" . $jugador->id ?>" class="btn borrar"
                                    onclick="return confirm('¿Estás seguro de que quieres borrar al jugador <?= $jugador->nombre ?>?');">
                                    Borrar
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
