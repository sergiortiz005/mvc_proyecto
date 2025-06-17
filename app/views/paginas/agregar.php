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
    <title>Nuevo Equipo</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/equiposagregar.css">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Equipo</h1>

        <?php if (!empty($datos['error'])): ?>
            <p style="color: red;"><?php echo $datos['error']; ?></p>
        <?php endif; ?>

        <form action="<?php echo RUTA_URL; ?>/equipos/guardar" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del equipo:</label>
                <input type="text" name="nombre" id="nombre" required
                       value="<?php echo isset($datos['nombre']) ? htmlspecialchars($datos['nombre']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" name="ciudad" id="ciudad"
                       value="<?php echo isset($datos['ciudad']) ? htmlspecialchars($datos['ciudad']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="escudo">Escudo del equipo (imagen):</label>
                <input type="file" name="escudo" id="escudo" accept="image/*">
            </div>

            <div class="form-group">
                <input type="submit" value="Crear equipo">
            </div>
        </form>

        <p><a href="<?php echo RUTA_URL; ?>/equipos">Volver a la lista de equipos</a></p>
    </div>
</body>
</html>
