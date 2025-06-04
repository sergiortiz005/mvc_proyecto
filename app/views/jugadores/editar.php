<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    // No autorizado, redirige a la página principal o login
    header('Location: ' . RUTA_URL . '/usuario/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Jugador</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/formulario_jugadores.css">
</head>
<body>
<div class="form-container">
    <h2>Editar Jugador</h2>
    <form action="<?php echo RUTA_URL; ?>/jugador/actualizar" method="POST">
        <input type="hidden" name="id" value="<?php echo $datos['jugador']->id; ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $datos['jugador']->nombre; ?>" required>

        <label for="dorsal">Dorsal:</label>
        <input type="number" name="dorsal" value="<?php echo $datos['jugador']->dorsal; ?>" required>

        <label for="posicion">Posición:</label>
        <input type="text" name="posicion" value="<?php echo $datos['jugador']->posicion; ?>" required>

        <label for="id_equipo">Equipo:</label>
        <select name="id_equipo" required>
            <?php foreach ($datos['equipos'] as $equipo): ?>
                <option value="<?php echo $equipo->id; ?>" <?php echo ($equipo->id == $datos['jugador']->id_equipo) ? 'selected' : ''; ?>>
                    <?php echo $equipo->nombre; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Actualizar jugador">
        <p><a href="<?php echo RUTA_URL; ?>/jugador">Volver al listado de jugadores</a></p>
    </form>
</div>
</body>
</html>
