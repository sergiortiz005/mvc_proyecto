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
    <title>Editar Jugador</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/formulario_jugadores.css">
</head>
<body>
<div class="form-container">
    <h2>Editar Jugador</h2>
    <form action="<?php echo RUTA_URL; ?>/jugador/actualizar" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($datos['jugador']->id ?? ''); ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['jugador']->nombre ?? ''); ?>" required>
        <?php if (!empty($datos['errores']['nombre'])): ?>
            <p class="error"><?php echo $datos['errores']['nombre']; ?></p>
        <?php endif; ?>

        <label for="dorsal">Dorsal:</label>
        <input type="number" name="dorsal" value="<?php echo htmlspecialchars($datos['jugador']->dorsal ?? ''); ?>" required>
        <?php if (!empty($datos['errores']['dorsal'])): ?>
            <p class="error"><?php echo $datos['errores']['dorsal']; ?></p>
        <?php endif; ?>

        <label for="posicion">Posición:</label>
        <input type="text" name="posicion" value="<?php echo htmlspecialchars($datos['jugador']->posicion ?? ''); ?>" required>
        <?php if (!empty($datos['errores']['posicion'])): ?>
            <p class="error"><?php echo $datos['errores']['posicion']; ?></p>
        <?php endif; ?>

        <label for="id_equipo">Equipo:</label>
        <select name="id_equipo" required>
            <option value="">-- Selecciona un equipo --</option>
            <?php foreach ($datos['equipos'] as $equipo): ?>
                <option value="<?php echo $equipo->id; ?>"
                    <?php 
                        if (isset($datos['jugador']->id_equipo) && $equipo->id == $datos['jugador']->id_equipo) {
                            echo 'selected';
                        }
                    ?>>
                    <?php echo htmlspecialchars($equipo->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($datos['errores']['id_equipo'])): ?>
            <p class="error"><?php echo $datos['errores']['id_equipo']; ?></p>
        <?php endif; ?>

        <input type="submit" value="Actualizar jugador" class="btn">
        <p><a href="<?php echo RUTA_URL; ?>/jugador">Volver al listado de jugadores</a></p>
    </form>
</div>
</body>
</html>
