<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . RUTA_URL . '/usuario/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Jugador</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/jugadoresagregar.css">
</head>

<body>
    <div class="container">
        <h2>Añadir Nuevo Jugador</h2>

        <form action="<?= RUTA_URL ?>/jugador/guardar" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?= $datos['nombre'] ?? '' ?>" required>

            <label for="dorsal">Dorsal:</label>
            <input type="number" name="dorsal" min="1" value="<?= $datos['dorsal'] ?? '' ?>" required>
            <?php if (!empty($datos['errores']['dorsal'])): ?>
                <p class="error"><?= $datos['errores']['dorsal'] ?></p>
            <?php endif; ?>

            <label for="posicion">Posición:</label>
            <input type="text" name="posicion" value="<?= $datos['posicion'] ?? '' ?>" required>

            <label for="id_equipo">Equipo:</label>
            <select name="id_equipo" required>
                <option value="">-- Selecciona un equipo --</option>
                <?php foreach ($datos['equipos'] as $equipo): ?>
                    <option value="<?= $equipo->id ?>"
                        <?= isset($datos['id_equipo']) && $datos['id_equipo'] == $equipo->id ? 'selected' : '' ?>>
                        <?= $equipo->nombre ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Guardar Jugador" class="btn">
            <a href="<?= RUTA_URL ?>/jugador" class="btn cancelar">Cancelar</a>
        </form>
    </div>
</body>

</html>
