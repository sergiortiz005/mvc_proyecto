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
    <title>Editar equipo</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/formulario_equipos.css">
</head>

<body>
    <div class="form-container">

        <h2>Editar Equipo</h2>

        <!-- Mostrar error si existe -->
        <?php if (!empty($datos['error'])): ?>
            <div style="color: red; font-weight: bold; margin-bottom: 15px;">
                <?php echo $datos['error']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo RUTA_URL; ?>/equipos/actualizar/<?php echo $datos['equipo']->id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $datos['equipo']->id; ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['equipo']->nombre, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="ciudad">Ciudad:</label>
            <input type="text" name="ciudad" value="<?php echo htmlspecialchars($datos['equipo']->ciudad, ENT_QUOTES, 'UTF-8'); ?>">

            <label>Escudo actual:</label>
            <div class="current-image">
                <img src="<?php echo RUTA_URL . '/public/img/escudos/' . $datos['equipo']->nombre . ".png"; ?>" alt="Escudo actual" style="max-width:150px;">
            </div>
            <br><br>

            <label for="escudo">Nuevo escudo (opcional):</label>
            <input type="file" name="escudo" accept="image/*">

            <input type="submit" value="Actualizar equipo">
            <p><a href="<?php echo RUTA_URL; ?>/equipos">Volver a la lista de equipos</a></p>

        </form>
    </div>
</body>

</html>
