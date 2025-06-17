<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/cambiarcontrasena.css">
</head>
<body>
    <div class="container">
        <h2>Cambiar contraseña</h2>
        <form action="<?= RUTA_URL ?>/usuario/cambiarContrasena" method="POST" class="form">
            <div class="input-group">
                <label for="contrasena_actual">Contraseña actual:</label>
                <input type="password" id="contrasena_actual" name="contrasena_actual" required>
                <?php if (!empty($datos['errores']['contrasena_actual'])): ?>
                    <p class="error"><?= $datos['errores']['contrasena_actual'] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="nueva_contrasena">Nueva contraseña:</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
                <?php if (!empty($datos['errores']['nueva_contrasena'])): ?>
                    <p class="error"><?= $datos['errores']['nueva_contrasena'] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="confirmar_contrasena">Confirmar nueva contraseña:</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
                <?php if (!empty($datos['errores']['confirmar_contrasena'])): ?>
                    <p class="error"><?= $datos['errores']['confirmar_contrasena'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
        </form>

        <div class="back-link">
            <a href="<?= RUTA_URL ?>/usuario/miCuenta">← Volver</a>
        </div>
    </div>
</body>
</html>
